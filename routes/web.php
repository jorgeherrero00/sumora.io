<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MeetingController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\TestIntegrationController;
use App\Http\Controllers\GoogleDebugController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/reuniones', [MeetingController::class, 'index'])->name('reuniones.index');
    Route::post('/reuniones', [MeetingController::class, 'store'])->name('reuniones.store');
    Route::get('/reuniones/{meeting}', [MeetingController::class, 'show'])->name('reuniones.show');
    
    // Integrations
    Route::get('/integrations', [IntegrationController::class, 'index'])->name('integrations.index');
    Route::post('/integrations', [IntegrationController::class, 'store'])->name('integrations.store');
    Route::delete('/integrations/{integration}', [IntegrationController::class, 'destroy'])->name('integrations.destroy');
    Route::get('/integrations/notion/databases', [IntegrationController::class, 'listNotionDatabases'])->name('integrations.notion.databases');
    Route::post('/integrations/notion/databases/save', [IntegrationController::class, 'saveNotionDatabase'])->name('integrations.notion.database.save');

    // Test integrations
    Route::post('/test/notion', [App\Http\Controllers\TestIntegrationController::class, 'testNotion'])->name('test.notion');
    Route::post('/test/slack', [App\Http\Controllers\TestIntegrationController::class, 'testSlack'])->name('test.slack');
    Route::post('/test/google-sheets', [App\Http\Controllers\TestIntegrationController::class, 'testGoogleSheets'])->name('test.google-sheets');
    Route::post('/test/all-integrations', [App\Http\Controllers\TestIntegrationController::class, 'testAll'])->name('test.all-integrations');
    Route::post('/tasks/{task}/send', [App\Http\Controllers\TaskController::class, 'sendTask'])->name('tasks.send');
    Route::post('/meetings/{meeting}/send-all-tasks', [MeetingController::class, 'sendAllTasks'])->name('meetings.send-all-tasks');
    // Google Debug
    Route::get('/debug/google', [App\Http\Controllers\GoogleDebugController::class, 'debug'])->name('debug.google');
    Route::get('/debug/google/spreadsheets', [App\Http\Controllers\GoogleDebugController::class, 'listSpreadsheets'])->name('debug.google.spreadsheets');
Route::get('/debug/notion', function () {
    $integration = auth()->user()->integrations()
        ->where('tipo', 'notion')
        ->first();

    if (!$integration) {
        return response()->json([
            'error' => 'No se encontró integración con Notion',
            'user_id' => auth()->id(),
            'integrations_count' => auth()->user()->integrations()->count()
        ]);
    }

    $token = $integration->token;
    
    // Información básica
    $info = [
        'integration_id' => $integration->id,
        'token_exists' => !empty($token),
        'token_length' => strlen($token ?? ''),
        'token_preview' => $token ? substr($token, 0, 20) . '...' : 'No token',
        'config' => $integration->config,
        'created_at' => $integration->created_at
    ];

    // Probar conexión básica
    try {
        \Log::info('🧪 Probando conexión básica con Notion', $info);
        
        $response = \Http::timeout(30)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Notion-Version' => '2022-06-28',
            'Content-Type' => 'application/json',
        ])->get('https://api.notion.com/v1/users/me');

        $info['connection_test'] = [
            'success' => $response->successful(),
            'status' => $response->status(),
            'response' => $response->json(),
            'headers' => $response->headers()
        ];

        if (!$response->successful()) {
            $info['error_details'] = $response->body();
        }

    } catch (\Exception $e) {
        $info['connection_test'] = [
            'success' => false,
            'error' => $e->getMessage(),
            'class' => get_class($e)
        ];
        
        \Log::error('❌ Error de conexión con Notion', [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
    }

    return response()->json($info, 200, [], JSON_PRETTY_PRINT);
});
Route::get('/debug/notion-service', function () {
    $integration = auth()->user()->integrations()
        ->where('tipo', 'notion')
        ->first();

    if (!$integration) {
        return response()->json(['error' => 'No integration found']);
    }

    try {
        $notion = new \App\Services\NotionService($integration->token);
        
        echo "<h2>🧪 Debug del NotionService</h2>";
        
        // Paso 1: Listar bases de datos
        echo "<h3>📊 1. Listando bases de datos...</h3>";
        $databases = $notion->listarBaseDatos();
        echo "<pre>" . json_encode($databases, JSON_PRETTY_PRINT) . "</pre>";
        
        if (!$databases['success']) {
            echo "<p style='color:red;'>❌ Error listando databases: " . $databases['error'] . "</p>";
            return;
        }
        
        // Paso 2: Obtener esquema de la DB configurada
        $config = json_decode($integration->config ?? '{}', true);
        $databaseId = $config['database_id'] ?? null;
        
        if (!$databaseId) {
            echo "<p style='color:red;'>❌ No hay database_id configurado</p>";
            return;
        }
        
        echo "<h3>🔍 2. Obteniendo esquema de DB: " . $databaseId . "</h3>";
        $schema = $notion->obtenerEsquemaDatabase($databaseId);
        echo "<pre>" . json_encode($schema, JSON_PRETTY_PRINT) . "</pre>";
        
        if (!$schema['success']) {
            echo "<p style='color:red;'>❌ Error obteniendo esquema: " . $schema['error'] . "</p>";
            return;
        }
        
        // Paso 3: Intentar enviar prueba
        echo "<h3>🚀 3. Enviando prueba...</h3>";
        $resultado = $notion->enviarResumenReunion(
            $databaseId,
            '🧪 Prueba desde debug - ' . now()->format('H:i:s'),
            'Esta es una prueba del debug del NotionService.',
            ['Verificar conexión', 'Probar esquema flexible']
        );
        
        echo "<pre>" . json_encode($resultado, JSON_PRETTY_PRINT) . "</pre>";
        
        if ($resultado['success']) {
            echo "<p style='color:green;'>✅ ¡Prueba enviada exitosamente!</p>";
        } else {
            echo "<p style='color:red;'>❌ Error enviando prueba: " . $resultado['error'] . "</p>";
        }
        
    } catch (\Exception $e) {
        echo "<h3 style='color:red;'>❌ Excepción capturada:</h3>";
        echo "<p><strong>Mensaje:</strong> " . $e->getMessage() . "</p>";
        echo "<p><strong>Archivo:</strong> " . $e->getFile() . ":" . $e->getLine() . "</p>";
        echo "<p><strong>Trace:</strong></p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
});

Route::get('/slack/channels', function () {
    $integration = auth()->user()->integrations()->where('tipo', 'slack')->first();
    
    if (!$integration) {
        return response()->json(['error' => 'No Slack integration found']);
    }
    
    // Listar canales públicos
    $publicChannels = Http::withToken($integration->token)
        ->get('https://slack.com/api/conversations.list', [
            'types' => 'public_channel',
            'limit' => 100
        ]);
    
    // Listar canales privados donde el bot es miembro
    $privateChannels = Http::withToken($integration->token)
        ->get('https://slack.com/api/conversations.list', [
            'types' => 'private_channel',
            'limit' => 100
        ]);
    
    $channels = [
        'public' => $publicChannels->json('channels', []),
        'private' => $privateChannels->json('channels', []),
    ];
    
    return response()->json([
        'channels' => $channels,
        'formatted' => collect($channels['public'])->merge($channels['private'])->map(function($channel) {
            return [
                'id' => $channel['id'],
                'name' => '#' . $channel['name'],
                'is_member' => $channel['is_member'] ?? false,
                'is_private' => $channel['is_private'] ?? false
            ];
        })->values()
    ], 200, [], JSON_PRETTY_PRINT);
})->middleware('auth');

Route::get('/slack/fix-config', function () {
    $integration = auth()->user()->integrations()->where('tipo', 'slack')->first();
    
    if (!$integration) {
        return 'No Slack integration found';
    }
    
    $config = json_decode($integration->config ?? '{}', true);
    $oldChannel = $config['channel'] ?? 'No configurado';
    $config['channel'] = 'C097HCXCC0P'; // Canal donde el bot es miembro
    
    $integration->update(['config' => json_encode($config)]);
    
    return "✅ Configuración actualizada:<br>" .
           "Canal anterior: {$oldChannel}<br>" .
           "Canal nuevo: C097HCXCC0P (#general-jore-herrero)<br><br>" .
           "<a href='/test/slack'>🧪 Probar Slack ahora</a>";
})->middleware('auth');
    // Test routes
    Route::get('/test-n8n', function () {
        $payload = [
            'reunion_id' => 999,
            'titulo' => 'Reunión de prueba directa',
            'resumen' => 'Este es un resumen ficticio generado sin IA.',
            'tareas' => [
                'Confirmar asistencia del cliente',
                'Enviar presentación final',
                'Planificar siguiente sesión'
            ],
            'email_usuario' => 'admin@jorgeherrero.dev',
        ];

        Log::info('🧪 Enviando prueba manual a N8N', ['payload' => $payload]);

        try {
            $response = Http::post(env('N8N_WEBHOOK_URL'), $payload);

            Log::info('📩 Respuesta de N8N', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return 'Enviado a n8n. Ver logs.';
        } catch (\Throwable $e) {
            Log::error('❌ Error al contactar con N8N', [
                'mensaje' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine(),
            ]);
            return 'Error al enviar a n8n. Ver logs.';
        }
    })->name('test.n8n');
});

// OAuth Google Routes
Route::get('/oauth/google', [GoogleController::class, 'redirect'])->name('oauth.google.redirect');
Route::get('/oauth/google/callback', [GoogleController::class, 'callback'])->name('oauth.google.callback');
Route::post('/oauth/google/store', [GoogleController::class, 'store'])->name('oauth.google.store');
Route::get('/oauth/google/sheets', [GoogleController::class, 'listSheets'])->name('oauth.google.sheets');
Route::post('/oauth/google/sheets/save', [GoogleController::class, 'saveSheet'])->name('oauth.google.sheets.save');

require __DIR__.'/auth.php';