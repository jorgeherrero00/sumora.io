<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MeetingController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\IntegrationController;

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

    // Test integrations
    Route::post('/test/notion', [App\Http\Controllers\TestIntegrationController::class, 'testNotion'])->name('test.notion');
    Route::post('/test/slack', [App\Http\Controllers\TestIntegrationController::class, 'testSlack'])->name('test.slack');
    Route::post('/test/google-sheets', [App\Http\Controllers\TestIntegrationController::class, 'testGoogleSheets'])->name('test.google-sheets');
    Route::post('/test/all-integrations', [App\Http\Controllers\TestIntegrationController::class, 'testAll'])->name('test.all-integrations');

    // Google Debug
    Route::get('/debug/google', [App\Http\Controllers\GoogleDebugController::class, 'debug'])->name('debug.google');
    Route::get('/debug/google/spreadsheets', [App\Http\Controllers\GoogleDebugController::class, 'listSpreadsheets'])->name('debug.google.spreadsheets');

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