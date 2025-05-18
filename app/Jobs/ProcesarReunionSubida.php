<?php

namespace App\Jobs;

use App\Models\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


use FFMpeg;

class ProcesarReunionSubida implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $meeting;

    public function __construct(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }

    public function handle()
{
    Log::info('🎯 Entró al Job correctamente');

    $pathOriginal = storage_path('app/public/' . $this->meeting->archivo);
    $nuevoPath = $pathOriginal;

    // 1. Si es video → extraer audio
    if ($this->meeting->formato_origen === 'video') {
        Log::info('🎬 Es un vídeo, extrayendo audio');
        $nombreBase = pathinfo($this->meeting->archivo, PATHINFO_FILENAME);
        $nuevoNombre = $nombreBase . '.mp3';
        $nuevoPath = storage_path('app/public/reuniones/' . $nuevoNombre);

        $ffmpeg = \FFMpeg\FFMpeg::create([
            'ffmpeg.binaries' => 'C:\ffmpeg\bin\ffmpeg.exe',
            'ffprobe.binaries' => 'C:\ffmpeg\bin\ffprobe.exe',
        ]);

        $video = $ffmpeg->open($pathOriginal);
        $video->save(new \FFMpeg\Format\Audio\Mp3(), $nuevoPath);

        Storage::disk('public')->delete($this->meeting->archivo);
        $this->meeting->update([
            'archivo' => 'reuniones/' . $nuevoNombre,
            'formato_origen' => 'audio_extraido',
        ]);
        Log::info('✅ Audio extraído y archivo actualizado');
    }

    // 2. Transcripción con Whisper
    Log::info('🧠 Enviando a Whisper', ['archivo' => $nuevoPath]);

    $response = Http::attach(
        'file',
        fopen($nuevoPath, 'r'),
        basename($nuevoPath)
    )->withToken(config('services.openai.key'))->post('https://api.openai.com/v1/audio/transcriptions', [
        'model' => 'whisper-1',
    ]);

$transcripcionRaw = $response->json()['text'] ?? '';
$transcripcion = is_string($transcripcionRaw) ? $transcripcionRaw : (string) $transcripcionRaw;
    Log::info('📝 Transcripción recibida', ['transcripcion_preview' => Str::limit($transcripcion, 100)]);

    // 3. RESUMEN
    $resumen = '';
    if ($transcripcion) {
        $promptResumen = <<<TXT
        Actúa como un asistente profesional y redacta un resumen ejecutivo de esta reunión.

        Incluye:

        1. Objetivo de la reunión.
        2. Puntos clave tratados.
        3. Decisiones tomadas.
        4. Próximos pasos (si los hay).

        Sé claro, profesional y directo. Redacta en un tono neutro y ordenado.

        Texto de la reunión:
        ---
        $transcripcion
        TXT;
        try {
    Log::info('🧾 Enviando a GPT para resumen');
    $respuestaResumen = Http::withToken(config('services.openai.key'))->post('https://api.openai.com/v1/chat/completions', [
        'model' => 'gpt-4.1-nano-2025-04-14',
        'messages' => [
            ['role' => 'system', 'content' => 'Eres un asistente experto en resumir reuniones.'],
            ['role' => 'user', 'content' => $promptResumen],
        ],
    ]);

    $resumen = $respuestaResumen->json()['choices'][0]['message']['content'] ?? '';
    Log::info('✅ Resumen generado', ['resumen_preview' => Str::limit($resumen, 100)]);
} catch (\Throwable $e) {
    Log::error('❌ Error al generar resumen', [
        'mensaje' => $e->getMessage(),
        'linea' => $e->getLine(),
        'archivo' => $e->getFile(),
    ]);
    return;
}

    }

    // 4. TAREAS
    $tareasTexto = '';
$promptTareas = <<<TXT
Extrae una lista clara y numerada de tareas pendientes encontradas en esta transcripción.

Por cada tarea, indica:
- Responsable (si aparece).
- Acción concreta.
- Fecha límite (si se menciona).

Formato:
1. [Responsable]: [Acción] (Fecha si la hay)

Ejemplo:
1. Marta: Redactar el copy de la landing page (antes del viernes)

Si no hay tareas, responde sólo con: "NINGUNA"

Texto de la reunión:
---
$transcripcion
TXT;


    Log::info('🗂 Enviando a GPT para tareas');
    $respuestaTareas = Http::withToken(config('services.openai.key'))->post('https://api.openai.com/v1/chat/completions', [
        'model' => 'gpt-4.1-nano-2025-04-14',
        'messages' => [
            ['role' => 'system', 'content' => 'Eres un asistente que extrae tareas de reuniones.'],
            ['role' => 'user', 'content' => $promptTareas],
        ],
    ]);

    $tareasTexto = $respuestaTareas->json()['choices'][0]['message']['content'] ?? '';
    Log::info('✅ Tareas extraídas', ['tareas_texto' => $tareasTexto]);

    if (strtoupper(trim($tareasTexto)) !== 'NINGUNA') {
        $tareas = explode("\n", $tareasTexto);

        foreach ($tareas as $linea) {
            $descripcion = preg_replace('/^\d+\.\s*/', '', trim($linea));
            if ($descripcion) {
                $this->meeting->tasks()->create([
                    'descripcion' => $descripcion,
                ]);
                Log::info('📌 Tarea guardada', ['descripcion' => $descripcion]);
            }
        }
    }

    // 5. Guardar resumen y transcripción
    $this->meeting->update([
        'transcripcion' => $transcripcion,
        'resumen' => $resumen,
    ]);
    Log::info('🗃 Reunión actualizada en DB');

    // 6. Enviar a N8N
    $payload = [
    'reunion_id' => $this->meeting->id,
    'titulo' => $this->meeting->titulo,
    'resumen' => $this->meeting->resumen,
    'tareas' => $this->meeting->tasks->pluck('descripcion')->toArray(),
    'email_usuario' => $this->meeting->user->email,
];

if ($this->meeting->guardar_en_google_sheets) {
    $integration = $this->meeting->user->integrations()->where('tipo', 'google_sheets')->first();
    $config = json_decode($integration?->config ?? '{}', true);

    if ($integration && isset($config['spreadsheet_id'])) {
        $payload['google_sheets'] = [
            'access_token' => $integration->token,
            'spreadsheet_id' => $config['spreadsheet_id'],
            'sheet_name' => $config['sheet_name'] ?? 'Hoja 1',
            'contenido' => [
                'titulo' => $this->meeting->titulo,
                'resumen' => $this->meeting->resumen,
                'tareas' => $this->meeting->tasks->pluck('descripcion')->toArray(),
            ],
        ];
    }
}
    Log::info('🚀 Enviando webhook a N8N', [
        'url' => env('N8N_WEBHOOK_URL'),
        'payload' => $payload,
    ]);

    $respuestaN8n = Http::post(env('N8N_WEBHOOK_URL'), $payload);
    Log::info('✅ Respuesta de N8N', [
        'status' => $respuestaN8n->status(),
        'body' => $respuestaN8n->body(),
    ]);
}


}
