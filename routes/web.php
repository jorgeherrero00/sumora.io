<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MeetingController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

require __DIR__.'/auth.php';
