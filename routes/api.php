<?php 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Meeting;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.api_token')->post('/upload', function (Request $request) {
    $user = auth()->user();

    if (!$request->hasFile('audio')) {
        return response()->json(['error' => 'No se ha recibido archivo de audio'], 400);
    }

    $archivo = $request->file('audio');
    $filename = uniqid('reunion_') . '.' . $archivo->getClientOriginalExtension();

    $path = $archivo->storeAs('public/reuniones', $filename);

    $meeting = Meeting::create([
        'user_id' => $user->id,
        'titulo' => $request->input('titulo', 'Reunión sin título'),
        'archivo' => str_replace('public/', '', $path),
    ]);

    return response()->json([
        'success' => true,
        'meeting_id' => $meeting->id,
        'filename' => $filename,
    ]);
});
