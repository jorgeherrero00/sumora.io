<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Models\Meeting;

use App\Http\Controllers\Controller;

class ExtensionController extends Controller
{
   public function uploadFromExtension(Request $request)
{
    $user = $request->attributes->get('api_user'); // 👈 Usuario autenticado por token

    if (!$user) {
        return response()->json(['error' => 'No autorizado'], 403);
    }

    // Aquí ya usas $user->id directamente
    $meeting = Meeting::create([
        'user_id' => $user->id,
        'titulo'  => $request->input('titulo', 'Reunión sin título'),
        'archivo' => $request->file('audio')->store('reuniones', 'public'),
    ]);

    return response()->json([
        'success' => true,
        'meeting_id' => $meeting->id,
    ]);
}

}
