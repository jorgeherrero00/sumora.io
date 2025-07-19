<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MeetingController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Auth\GoogleController;

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
Route::get('/oauth/google', [GoogleController::class, 'redirect'])->name('oauth.google.redirect');
Route::get('/oauth/google/callback', [GoogleController::class, 'callback'])->name('oauth.google.callback');
Route::post('/oauth/google/store', [GoogleController::class, 'store'])->name('oauth.google.store');


Route::get('/oauth/google/sheets', [GoogleController::class, 'listSheets'])->name('oauth.google.sheets');
Route::post('/oauth/google/sheets/save', [GoogleController::class, 'saveSheet'])->name('oauth.google.sheets.save');

Route::get('/youtube-to-mp3', function () {
    $youtubeUrl = request('url') ?? 'https://www.youtube.com/watch?v=XXXXXXXXXXX';

    $tempPath = storage_path('app/public/youtube');
    if (!file_exists($tempPath)) {
        mkdir($tempPath, 0775, true);
    }

    // Escapar comillas por seguridad
    $youtubeUrl = escapeshellarg($youtubeUrl);
    $output = [];
    $code = 0;

$ytExecutable = 'C:\Tools\yt-dlp\yt-dlp.exe'; // 👈 ruta completa a yt-dlp.exe
$ytCommand = "\"$ytExecutable\" -x --audio-format mp3 -o \"$tempPath/%(title)s.%(ext)s\" $youtubeUrl";

    exec($ytCommand . " 2>&1", $output, $code); // redirigir STDERR a STDOUT

    if ($code !== 0) {
        return response()->make(
            "<h3>❌ Error al convertir desde YouTube</h3><pre>" . implode("\n", $output) . "</pre>",
            500
        );
    }

    $files = glob($tempPath . '/*.mp3');
    if (empty($files)) {
        return '❌ No se encontró ningún archivo .mp3 en ' . $tempPath;
    }

    $latest = end($files);
    $basename = basename($latest);

    return "✅ Archivo convertido: <a href='/storage/youtube/$basename'>$basename</a>";
});



Route::get('/test-reunion', function () {

    set_time_limit(300); // 5 minutos

    $file = storage_path('app\public\reuniones\reunion42.mp3'); // Ajusta si tu archivo tiene otro nombre


    if (!file_exists($file)) {
        return '❌ Archivo no encontrado';
    }

    // 1. Transcripción con Whisper
    $transcripcion = Http::timeout(300) // 5 minutos por si tarda
    ->attach('file', fopen($file, 'r'), basename($file))
    ->withToken(env('OPENAI_API_KEY'))
    ->post('https://api.openai.com/v1/audio/transcriptions', [
        'model' => 'whisper-1',
        'language' => 'es',
    ]);


    if (!$transcripcion->ok()) {
        return response()->make(
            "<h3>❌ Error en transcripción</h3><pre>" . $transcripcion->body() . "</pre>",
            500
        );
    }

    $texto = $transcripcion->json('text');

    // 2. Análisis con GPT
    $prompt = <<<PROMPT
Eres un asistente de productividad. A partir de esta transcripción de una reunión:

-----
$texto
-----

Extrae:
1. Un resumen ejecutivo claro y estructurado.
2. Lista de tareas con responsables si se mencionan.
3. Decisiones importantes tomadas (si las hay).
4. Insight conductual (por ejemplo, quién habló más, tono general, dinámicas del equipo).

Formato: Markdown.
PROMPT;

    $analisis = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/chat/completions', [
        'model' => 'gpt-4o-mini-2024-07-18',
        'messages' => [
            ['role' => 'system', 'content' => 'Eres un asistente experto en análisis de reuniones.'],
            ['role' => 'user', 'content' => $prompt],
        ],
        'temperature' => 0.5,
    ]);

    if (!$analisis->ok()) {
        return response()->make(
            "<h3>❌ Error en GPT</h3><pre>" . $analisis->body() . "</pre>",
            500
        );
    }

    $resultado = $analisis->json('choices.0.message.content');

    return "<h2>🧠 Resultado del análisis IA:</h2><hr><pre style='white-space: pre-wrap; font-family: monospace;'>$resultado</pre>";
});
require __DIR__.'/auth.php';
