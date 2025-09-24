<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Models\Meeting;
use App\Jobs\ProcesarReunionSubida;

use App\Http\Controllers\Controller;

class ExtensionController extends Controller
{
   public function uploadFromExtension(Request $request)
{
    $request->validate([
        'titulo'  => 'nullable|string|max:255',
        'audio'   => 'required|file|mimes:webm,mp3,wav,m4a,mp4,mov,avi|max:102400',
    ]);

    // 👇 el usuario ya lo tienes desde el middleware
    $user = $request->attributes->get('api_user');

    if (!$user) {
        return response()->json(['error' => 'No autorizado'], 403);
    }

    $archivo = $request->file('audio');
    $pathTemporal = $archivo->getPathname();

    // Analizar duración con getID3
    $getID3 = new \getID3;
    $info = $getID3->analyze($pathTemporal);

    $duracionSegundos = $info['playtime_seconds'] ?? 0;
    if ($duracionSegundos > 3600) {
        return response()->json(['error' => 'El archivo supera los 60 minutos'], 400);
    }

    // Detectar tipo
    $formato = $info['fileformat'] ?? 'desconocido';
    $tipo    = isset($info['video']) ? 'video' : 'audio';

    // Guardar archivo
    $ruta = $archivo->store('reuniones', 'public');

    // Crear reunión ligada al user autenticado por token
    $meeting = Meeting::create([
        'user_id'    => $user->id,
        'titulo'     => $request->input('titulo', 'Reunión desde extensión'),
        'archivo'    => $ruta,
        'formato_origen' => $tipo,
        'guardar_en_google_sheets' => false, // extensiones no suelen mandar esto
    ]);

    // 👇 lanzo el job para procesar
    ProcesarReunionSubida::dispatch($meeting);

    return response()->json([
        'success'    => true,
        'message'    => 'Reunión subida correctamente',
        'meeting_id' => $meeting->id,
    ]);
}


}
