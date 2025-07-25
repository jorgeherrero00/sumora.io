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
    public $timeout = 600; // 10 minutos

    public function __construct(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }

    public function handle()
    {
        Log::info('🎯 Entró al Job correctamente', ['meeting_id' => $this->meeting->id]);

        $pathOriginal = storage_path('app/public/' . $this->meeting->archivo);
        $nuevoPath = $pathOriginal;

        // 1. Si es video → extraer audio
        if ($this->meeting->formato_origen === 'video') {
            Log::info('🎬 Es un vídeo, extrayendo audio');
            $nombreBase = pathinfo($this->meeting->archivo, PATHINFO_FILENAME);
            $nuevoNombre = $nombreBase . '.mp3';
            $nuevoPath = storage_path('app/public/reuniones/' . $nuevoNombre);

            try {
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
            } catch (\Throwable $e) {
                Log::error('❌ Error al extraer audio', [
                    'mensaje' => $e->getMessage(),
                    'archivo' => $pathOriginal
                ]);
                return;
            }
        }

        // Verificar que el archivo existe
        if (!file_exists($nuevoPath)) {
            Log::error('❌ Archivo no encontrado', ['path' => $nuevoPath]);
            return;
        }

        // 2. Transcripción con Whisper
        Log::info('🧠 Enviando a Whisper', ['archivo' => basename($nuevoPath)]);

        try {
            $response = Http::timeout(300) // 5 minutos
                ->attach('file', fopen($nuevoPath, 'r'), basename($nuevoPath))
                ->withToken(config('services.openai.key'))
                ->post('https://api.openai.com/v1/audio/transcriptions', [
                    'model' => 'whisper-1',
                    'language' => 'es',
                ]);

            if (!$response->ok()) {
                Log::error('❌ Error en transcripción Whisper', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return;
            }

            $transcripcion = $response->json('text') ?? '';
            Log::info('📝 Transcripción recibida', ['preview' => Str::limit($transcripcion, 100)]);

        } catch (\Throwable $e) {
            Log::error('❌ Error al llamar a Whisper', [
                'mensaje' => $e->getMessage(),
                'linea' => $e->getLine(),
            ]);
            return;
        }

        // 3. Análisis completo con GPT (Resumen + Tareas + Insights)
        if ($transcripcion) {
            $prompt = <<<PROMPT
Eres un asistente de productividad. A partir de esta transcripción de una reunión:

-----
$transcripcion
-----

Extrae:
1. Un resumen ejecutivo claro y estructurado.
2. Lista de tareas con responsables si se mencionan.
3. Decisiones importantes tomadas (si las hay).
4. Insight conductual (por ejemplo, quién habló más, tono general, dinámicas del equipo).

Formato: Markdown.
PROMPT;

            try {
                Log::info('🧾 Enviando a GPT para análisis completo');
                $respuestaAnalisis = Http::withToken(config('services.openai.key'))
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => 'gpt-4o-mini-2024-07-18',
                        'messages' => [
                            ['role' => 'system', 'content' => 'Eres un asistente experto en análisis de reuniones.'],
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'temperature' => 0.5,
                    ]);

                if (!$respuestaAnalisis->ok()) {
                    Log::error('❌ Error en GPT análisis', [
                        'status' => $respuestaAnalisis->status(),
                        'body' => $respuestaAnalisis->body()
                    ]);
                    return;
                }

                $analisisCompleto = $respuestaAnalisis->json('choices.0.message.content') ?? '';
                Log::info('✅ Análisis completo generado', ['preview' => Str::limit($analisisCompleto, 150)]);

            } catch (\Throwable $e) {
                Log::error('❌ Error al generar análisis', [
                    'mensaje' => $e->getMessage(),
                    'linea' => $e->getLine(),
                ]);
                return;
            }

            // 4. Extraer tareas específicas del análisis
            $promptTareas = <<<TXT
Del siguiente análisis de reunión, extrae SOLO las tareas pendientes en formato de lista numerada:

$analisisCompleto

Formato requerido:
1. [Responsable]: [Acción concreta] (Fecha si la hay)

Si no hay tareas claras, responde: "NINGUNA"
TXT;

            try {
                Log::info('🗂 Extrayendo tareas específicas');
                $respuestaTareas = Http::withToken(config('services.openai.key'))
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => 'gpt-4o-mini-2024-07-18',
                        'messages' => [
                            ['role' => 'system', 'content' => 'Eres un asistente que extrae tareas de análisis de reuniones.'],
                            ['role' => 'user', 'content' => $promptTareas],
                        ],
                    ]);

                $tareasTexto = $respuestaTareas->json('choices.0.message.content') ?? '';
                Log::info('✅ Tareas extraídas', ['tareas_texto' => $tareasTexto]);

                // Guardar tareas en la base de datos
                if (strtoupper(trim($tareasTexto)) !== 'NINGUNA') {
                    $tareas = explode("\n", $tareasTexto);

                    foreach ($tareas as $linea) {
                        $descripcion = preg_replace('/^\d+\.\s*/', '', trim($linea));
                        if ($descripcion && strlen($descripcion) > 3) {
                            $this->meeting->tasks()->create([
                                'descripcion' => $descripcion,
                            ]);
                            Log::info('📌 Tarea guardada', ['descripcion' => $descripcion]);
                        }
                    }
                }

            } catch (\Throwable $e) {
                Log::error('❌ Error al extraer tareas', [
                    'mensaje' => $e->getMessage(),
                ]);
            }
        }

        // 5. Guardar transcripción y análisis completo
        $this->meeting->update([
            'transcripcion' => $transcripcion,
            'resumen' => $analisisCompleto ?? '', // Ahora guardamos el análisis completo como resumen
        ]);
        Log::info('🗃 Reunión actualizada en DB');

        // 6. Enviar a N8N
        $this->enviarWebhookN8N();
    }

    private function enviarWebhookN8N()
    {
        try {
            $payload = [
                'reunion_id' => $this->meeting->id,
                'titulo' => $this->meeting->titulo,
                'resumen' => $this->meeting->resumen,
                'tareas' => $this->meeting->tasks->pluck('descripcion')->toArray(),
                'email_usuario' => $this->meeting->user->email,
                'fecha_procesado' => now()->toISOString(),
            ];

            // Agregar datos de Google Sheets si está habilitado
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
                            'fecha' => $this->meeting->created_at->format('Y-m-d H:i:s'),
                        ],
                    ];
                }
            }

            Log::info('🚀 Enviando webhook a N8N', [
                'url' => env('N8N_WEBHOOK_URL'),
                'meeting_id' => $this->meeting->id,
            ]);

            $respuestaN8n = Http::timeout(30)->post(env('N8N_WEBHOOK_URL'), $payload);
            
            Log::info('✅ Respuesta de N8N', [
                'status' => $respuestaN8n->status(),
                'meeting_id' => $this->meeting->id,
            ]);

        } catch (\Throwable $e) {
            Log::error('❌ Error al enviar webhook a N8N', [
                'mensaje' => $e->getMessage(),
                'meeting_id' => $this->meeting->id,
            ]);
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error('❌ Job ProcesarReunionSubida falló', [
            'meeting_id' => $this->meeting->id,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}