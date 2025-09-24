<?php

namespace App\Jobs;

use App\Models\Meeting;
use App\Services\NotionService;
use App\Services\SlackService;
use App\Services\GoogleSheetsService;
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
        Log::info('🎯 Iniciando procesamiento de reunión', ['meeting_id' => $this->meeting->id]);

        try {
            // 1. Procesar archivo (video → audio si es necesario)
            $audioPath = $this->procesarArchivo();
            
            // 2. Transcribir con Whisper
            $transcripcion = $this->transcribirAudio($audioPath);
            Log::info('📝 Transcripción completada', ['length' => strlen($transcripcion)]);

            // 🚨 Si no hay nada transcrito, no seguir con GPT
            Log::info('Transcripcion válida?', ['valida' => $this->transcripcionValida($transcripcion)]);
        if (!$this->transcripcionValida($transcripcion)) {
            $this->meeting->update([
                'transcripcion' => $transcripcion,
                'resumen'       => '⚠️ No se detectó contenido hablado en el audio.',
            ]);

            Log::warning('⚠️ Reunión sin contenido hablado', [
                'meeting_id' => $this->meeting->id,
                'transcripcion' => $transcripcion
            ]);

            return;
        }
            
            // 3. Generar resumen y tareas con GPT
            $resultado = $this->generarResumenYTareas($transcripcion);
            
            // 4. Guardar en base de datos
            $this->guardarResultados($transcripcion, $resultado);
            
            // 5. Enviar a integraciones
            $this->enviarAIntegraciones($resultado);
            
            // 6. Notificar vía webhook/email
            $this->enviarNotificaciones($resultado);

            Log::info('✅ Reunión procesada exitosamente', ['meeting_id' => $this->meeting->id]);

        } catch (\Exception $e) {
            Log::error('❌ Error procesando reunión', [
                'meeting_id' => $this->meeting->id,
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }

    private function procesarArchivo()
    {
        $pathOriginal = storage_path('app/public/' . $this->meeting->archivo);
        
        if ($this->meeting->formato_origen === 'video') {
            Log::info('🎬 Convirtiendo video a audio');
            
            $nombreBase = pathinfo($this->meeting->archivo, PATHINFO_FILENAME);
            $nuevoNombre = $nombreBase . '.mp3';
            $nuevoPath = storage_path('app/public/reuniones/' . $nuevoNombre);

            $ffmpeg = \FFMpeg\FFMpeg::create([
                'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
                'ffprobe.binaries' => '/usr/bin/ffprobe',
            ]);

            $video = $ffmpeg->open($pathOriginal);
            $video->save(new \FFMpeg\Format\Audio\Mp3(), $nuevoPath);

            // Actualizar registro
            Storage::disk('public')->delete($this->meeting->archivo);
            $this->meeting->update([
                'archivo' => 'reuniones/' . $nuevoNombre,
                'formato_origen' => 'audio_extraido',
            ]);
            
            return $nuevoPath;
        }
        
        return $pathOriginal;
    }

    private function transcribirAudio($audioPath)
    {
        Log::info('🧠 Transcribiendo audio con Whisper');

        $response = Http::timeout(300)
            ->attach('file', fopen($audioPath, 'r'), basename($audioPath))
            ->withToken(config('services.openai.key'))
            ->post('https://api.openai.com/v1/audio/transcriptions', [
                'model' => 'whisper-1',
            ]);

        if (!$response->successful()) {
            throw new \Exception('Error en transcripción: ' . $response->body());
        }

        $transcripcion = $response->json()['text'] ?? '';
        Log::info('📝 Transcripción completada', ['length' => strlen($transcripcion)]);
        
        return $transcripcion;
    }

    private function generarResumenYTareas($transcripcion)
    {
        Log::info('🤖 Generando resumen y tareas con GPT-4');

        // Generar resumen
        $resumen = $this->generarResumen($transcripcion);
        
        // Extraer tareas
        $tareas = $this->extraerTareas($transcripcion);

        return [
            'resumen' => $resumen,
            'tareas' => $tareas
        ];
    }

    private function generarResumen($transcripcion)
    {
        $prompt = <<<TXT
        Actúa como un asistente profesional y redacta un resumen ejecutivo de esta reunión.

        Incluye:
        1. Objetivo de la reunión
        2. Puntos clave tratados
        3. Decisiones tomadas
        4. Próximos pasos (si los hay)

        Sé claro, profesional y directo. Redacta en un tono neutro y ordenado.

        Texto de la reunión:
        ---
        $transcripcion
        TXT;

        $response = Http::withToken(config('services.openai.key'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'Eres un asistente experto en resumir reuniones.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

        if (!$response->successful()) {
            throw new \Exception('Error generando resumen: ' . $response->body());
        }

        return $response->json()['choices'][0]['message']['content'] ?? '';
    }

    private function extraerTareas($transcripcion)
    {
        $prompt = <<<TXT
        Extrae una lista clara y numerada de tareas pendientes encontradas en esta transcripción.

        Por cada tarea, indica:
        - Responsable (si aparece)
        - Acción concreta
        - Fecha límite (si se menciona)

        Formato:
        1. [Responsable]: [Acción] (Fecha si la hay)

        Si no hay tareas, responde sólo con: "NINGUNA"

        Texto de la reunión:
        ---
        $transcripcion
        TXT;

        $response = Http::withToken(config('services.openai.key'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'Eres un asistente que extrae tareas de reuniones.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

        if (!$response->successful()) {
            throw new \Exception('Error extrayendo tareas: ' . $response->body());
        }

        $tareasTexto = $response->json()['choices'][0]['message']['content'] ?? '';
        
        if (strtoupper(trim($tareasTexto)) === 'NINGUNA') {
            return [];
        }

        // Procesar líneas de tareas
        $tareas = [];
        $lineas = explode("\n", $tareasTexto);
        
        foreach ($lineas as $linea) {
            $descripcion = preg_replace('/^\d+\.\s*/', '', trim($linea));
            if (!empty($descripcion)) {
                $tareas[] = $descripcion;
            }
        }

        return $tareas;
    }

    private function guardarResultados($transcripcion, $resultado)
    {
        Log::info('💾 Guardando resultados en base de datos');

        // Actualizar meeting
        $this->meeting->update([
            'transcripcion' => $transcripcion,
            'resumen' => $resultado['resumen'],
        ]);

        // Guardar tareas
        foreach ($resultado['tareas'] as $tareaDescripcion) {
            $this->meeting->tasks()->create([
                'descripcion' => $tareaDescripcion,
            ]);
        }
    }

    private function enviarAIntegraciones($resultado)
    {
        Log::info('🔗 Enviando a integraciones activas');

        $user = $this->meeting->user;
        $integrations = $user->integrations;

        foreach ($integrations as $integration) {
            try {
                switch ($integration->tipo) {
                    case 'notion':
                        $this->enviarANotion($integration, $resultado);
                        break;
                    
                    case 'slack':
                        $this->enviarASlack($integration, $resultado);
                        break;
                    
                    case 'google_sheets':
                        $this->enviarAGoogleSheets($integration, $resultado);
                        break;
                }
            } catch (\Exception $e) {
                Log::error("❌ Error enviando a {$integration->tipo}", [
                    'integration_id' => $integration->id,
                    'error' => $e->getMessage()
                ]);
                // Continúa con otras integraciones aunque una falle
            }
        }
    }

    private function enviarANotion($integration, $resultado)
    {
        $config = json_decode($integration->config ?? '{}', true);
        $databaseId = $config['database_id'] ?? null;

        if (!$databaseId) {
            Log::warning('⚠️ Notion: No se ha configurado database_id');
            return;
        }

        $notion = new NotionService($integration->token);
        $response = $notion->enviarResumenReunion(
            $databaseId,
            $this->meeting->titulo ?? 'Reunión sin título',
            $resultado['resumen'],
            $resultado['tareas']
        );

        if ($response['success']) {
            Log::info('✅ Enviado a Notion exitosamente');
        } else {
            Log::error('❌ Error enviando a Notion', ['error' => $response['error']]);
        }
    }

    private function enviarASlack($integration, $resultado)
    {
        $config = json_decode($integration->config ?? '{}', true);
        $canal = $config['channel'] ?? '#general';

        $slack = new SlackService($integration->token);
        $response = $slack->enviarResumenReunion(
            $canal,
            $this->meeting->titulo ?? 'Reunión sin título',
            $resultado['resumen'],
            $resultado['tareas']
        );

        if ($response['success']) {
            Log::info('✅ Enviado a Slack exitosamente');
        } else {
            Log::error('❌ Error enviando a Slack', ['error' => $response['error']]);
        }
    }

    private function enviarAGoogleSheets($integration, $resultado)
    {
        $config = json_decode($integration->config ?? '{}', true);
        $spreadsheetId = $config['spreadsheet_id'] ?? null;
        $sheetName = $config['sheet_name'] ?? 'Hoja 1';

        if (!$spreadsheetId) {
            Log::warning('⚠️ Google Sheets: No se ha configurado spreadsheet_id');
            return;
        }

        $sheets = new GoogleSheetsService($integration->token);
        
        // Configurar cabeceras si es necesario
        $sheets->configurarCabeceras($spreadsheetId, $sheetName);
        
        // Añadir fila con datos
        $response = $sheets->agregarResumenReunion(
            $spreadsheetId,
            $sheetName,
            $this->meeting->titulo ?? 'Reunión sin título',
            $resultado['resumen'],
            $resultado['tareas']
        );

        if ($response['success']) {
            Log::info('✅ Enviado a Google Sheets exitosamente');
        } else {
            Log::error('❌ Error enviando a Google Sheets', ['error' => $response['error']]);
        }
    }

    private function enviarNotificaciones($resultado)
    {
        Log::info('📧 Enviando notificaciones');

        // Webhook a N8N (como ya tenías)
        $this->enviarWebhookN8n($resultado);
        
        // Aquí podrías añadir otras notificaciones como email directo
    }

    private function transcripcionValida($texto)
{
    $limpio = trim(mb_strtolower($texto));

    // Quitar signos, espacios, puntos suspensivos, corchetes
    $limpio = preg_replace('/[^a-záéíóúñ0-9]+/u', '', $limpio);

    // Si tras limpiar no queda nada o es demasiado corto, no es válido
    return strlen($limpio) > 20;
}


    private function enviarWebhookN8n($resultado)
    {
        $payload = [
            'reunion_id' => $this->meeting->id,
            'titulo' => $this->meeting->titulo,
            'resumen' => $resultado['resumen'],
            'tareas' => $resultado['tareas'],
            'email_usuario' => $this->meeting->user->email,
        ];

        // Añadir info de Google Sheets si está configurado
        if ($this->meeting->guardar_en_google_sheets) {
            $integration = $this->meeting->user->integrations()
                ->where('tipo', 'google_sheets')
                ->first();
                
            if ($integration) {
                $config = json_decode($integration->config ?? '{}', true);
                $payload['google_sheets'] = [
                    'access_token' => $integration->token,
                    'spreadsheet_id' => $config['spreadsheet_id'] ?? null,
                    'sheet_name' => $config['sheet_name'] ?? 'Hoja 1',
                ];
            }
        }

        try {
            $response = Http::post(env('N8N_WEBHOOK_URL'), $payload);
            
            Log::info('✅ Webhook N8N enviado', [
                'status' => $response->status(),
                'body_preview' => Str::limit($response->body(), 200)
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Error enviando webhook N8N', [
                'error' => $e->getMessage()
            ]);
        }
    }
}