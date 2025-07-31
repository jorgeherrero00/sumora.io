<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use getID3;
use App\Models\Meeting;
use App\Jobs\ProcesarReunionSubida;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\TaskController;
use App\Services\NotionService;
use App\Services\SlackService;
use App\Services\GoogleSheetsService;
use App\Models\Task;
use Illuminate\Support\Facades\Http;

class MeetingController extends Controller
{
public function store(Request $request)
{
    $request->validate([
        'titulo' => 'nullable|string|max:255',
        'archivo' => 'required|file|mimes:mp3,wav,m4a,mp4,mov,avi|max:102400',
    ]);

    $guardarEnSheets = $request->input('enviar_google_sheets') == 1;


    $archivo = $request->file('archivo');
    $pathTemporal = $archivo->getPathname();

    // Analizar el archivo
    $getID3 = new \getID3;
    $info = $getID3->analyze($pathTemporal);

    // Duración (opcional)
    $duracionSegundos = $info['playtime_seconds'] ?? 0;
    if ($duracionSegundos > 3600) {
        return back()->withErrors(['archivo' => 'El archivo supera los 60 minutos de duración.']);
    }

    // Detectar tipo: audio o video
    $formato = $info['fileformat'] ?? 'desconocido';
    $tipo = isset($info['video']) ? 'video' : 'audio';

    // Guardar archivo
    $ruta = $archivo->store('reuniones', 'public');

    // Guardar en la base de datos
    $meeting = auth()->user()->meetings()->create([
        'titulo' => $request->input('titulo'),
        'archivo' => $ruta,
        'formato_origen' => $tipo,
        'guardar_en_google_sheets' => $guardarEnSheets,
    ]);

    ProcesarReunionSubida::dispatch($meeting);

if ($request->expectsJson() || $request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Reunión subida correctamente',
            'meeting_id' => $meeting->id,
            'redirect' => route('reuniones.index')
        ]);
    }

    return redirect()->route('reuniones.index')->with('success', 'Reunión subida correctamente.');
}

    public function index()
{
    $reuniones = auth()->user()->meetings()->latest()->get();
    return view('reuniones.index', compact('reuniones'));
}


public function show(Meeting $meeting)
{

    $meeting->load('tasks');

    return view('reuniones.show', compact('meeting'));
}

/**
     * Enviar todas las tareas de una reunión a una plataforma específica
     */
    public function sendAllTasks(Meeting $meeting, Request $request)
    {
        $platform = $request->input('platform');
        $user = auth()->user();
        
        // Verificar que el usuario es dueño de la reunión
        if ($meeting->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        if ($meeting->tasks->count() === 0) {
            return response()->json(['success' => false, 'message' => 'No hay tareas para enviar'], 400);
        }

        try {
            switch ($platform) {
                case 'slack':
                    return $this->sendAllTasksToSlack($meeting, $user);
                case 'notion':
                    return $this->sendAllTasksToNotion($meeting, $user);
                case 'sheets':
                    return $this->sendAllTasksToGoogleSheets($meeting, $user);
                default:
                    return response()->json(['success' => false, 'message' => 'Plataforma no soportada'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Error enviando todas las tareas', [
                'meeting_id' => $meeting->id,
                'platform' => $platform,
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['success' => false, 'message' => 'Error interno del servidor'], 500);
        }
    }

    /**
     * Enviar todas las tareas a Slack
     */
    private function sendAllTasksToSlack(Meeting $meeting, $user)
    {
        $integration = $user->integrations()->where('tipo', 'slack')->first();
        
        if (!$integration) {
            return response()->json(['success' => false, 'message' => 'Integración de Slack no configurada'], 400);
        }

        $meetingTitle = $meeting->titulo ?? 'Reunión sin título';
        $config = json_decode($integration->config ?? '{}', true);
        $channel = $config['channel'] ?? '#general';

        // Crear lista de tareas
        $tasksList = $meeting->tasks->map(function ($task, $index) {
            return ($index + 1) . ". " . $task->descripcion;
        })->join("\n");

        $payload = [
            'channel' => $channel,
            'text' => "📋 Tareas de reunión desde Meetlyze",
            'attachments' => [
                [
                    'color' => '#f97316',
                    'fields' => [
                        [
                            'title' => 'Reunión',
                            'value' => $meetingTitle,
                            'short' => false
                        ],
                        [
                            'title' => "Tareas ({$meeting->tasks->count()})",
                            'value' => $tasksList,
                            'short' => false
                        ]
                    ],
                    'footer' => 'Meetlyze',
                    'ts' => now()->timestamp
                ]
            ]
        ];

        $response = Http::withToken($integration->token)
            ->post('https://slack.com/api/chat.postMessage', $payload);

        if ($response->successful() && $response->json('ok')) {
            return response()->json(['success' => true, 'message' => 'Todas las tareas enviadas a Slack']);
        }

        return response()->json(['success' => false, 'message' => 'Error al enviar a Slack'], 400);
    }

    /**
     * Enviar todas las tareas a Notion
     */
    private function sendAllTasksToNotion(Meeting $meeting, $user)
    {
        $integration = $user->integrations()->where('tipo', 'notion')->first();
        
        if (!$integration) {
            return response()->json(['success' => false, 'message' => 'Integración de Notion no configurada'], 400);
        }

        $config = json_decode($integration->config ?? '{}', true);
        $databaseId = $config['database_id'] ?? null;

        if (!$databaseId) {
            return response()->json(['success' => false, 'message' => 'Base de datos de Notion no configurada'], 400);
        }

        $meetingTitle = $meeting->titulo ?? 'Reunión sin título';
        $successCount = 0;
        $errors = [];

        foreach ($meeting->tasks as $task) {
            $payload = [
                'parent' => ['database_id' => $databaseId],
                'properties' => [
                    'Tarea' => [
                        'title' => [
                            [
                                'text' => [
                                    'content' => $task->descripcion
                                ]
                            ]
                        ]
                    ],
                    'Reunión' => [
                        'rich_text' => [
                            [
                                'text' => [
                                    'content' => $meetingTitle
                                ]
                            ]
                        ]
                    ],
                    'Estado' => [
                        'select' => [
                            'name' => 'Pendiente'
                        ]
                    ],
                    'Fecha' => [
                        'date' => [
                            'start' => now()->toDateString()
                        ]
                    ]
                ]
            ];

            $response = Http::withToken($integration->token)
                ->withHeaders([
                    'Notion-Version' => '2022-06-28',
                    'Content-Type' => 'application/json'
                ])
                ->post('https://api.notion.com/v1/pages', $payload);

            if ($response->successful()) {
                $successCount++;
            } else {
                $errors[] = "Error en tarea: " . $task->descripcion;
            }

            // Pequeña pausa para no saturar la API
            usleep(200000); // 0.2 segundos
        }

        if ($successCount === $meeting->tasks->count()) {
            return response()->json(['success' => true, 'message' => 'Todas las tareas enviadas a Notion']);
        } elseif ($successCount > 0) {
            return response()->json(['success' => true, 'message' => "Se enviaron {$successCount} de {$meeting->tasks->count()} tareas a Notion"]);
        } else {
            return response()->json(['success' => false, 'message' => 'Error al enviar las tareas a Notion'], 400);
        }
    }

    /**
     * Enviar todas las tareas a Google Sheets
     */
    private function sendAllTasksToGoogleSheets(Meeting $meeting, $user)
    {
        $integration = $user->integrations()->where('tipo', 'google_sheets')->first();
        
        if (!$integration) {
            return response()->json(['success' => false, 'message' => 'Integración de Google Sheets no configurada'], 400);
        }

        $config = json_decode($integration->config ?? '{}', true);
        $spreadsheetId = $config['spreadsheet_id'] ?? null;
        $sheetName = $config['sheet_name'] ?? 'Hoja 1';

        if (!$spreadsheetId) {
            return response()->json(['success' => false, 'message' => 'Hoja de cálculo no configurada'], 400);
        }

        $meetingTitle = $meeting->titulo ?? 'Reunión sin título';
        $range = $sheetName . '!A:D';

        // Preparar todas las filas de una vez
        $values = $meeting->tasks->map(function ($task) use ($meetingTitle) {
            return [
                now()->format('d/m/Y H:i'),
                $meetingTitle,
                $task->descripcion,
                'Pendiente'
            ];
        })->toArray();

        $payload = [
            'values' => $values,
            'majorDimension' => 'ROWS'
        ];

        $response = Http::withToken($integration->token)
            ->post("https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}/values/{$range}:append?valueInputOption=USER_ENTERED", $payload);

        if ($response->successful()) {
            return response()->json(['success' => true, 'message' => 'Todas las tareas enviadas a Google Sheets']);
        }

        return response()->json(['success' => false, 'message' => 'Error al enviar a Google Sheets'], 400);
    }


}
