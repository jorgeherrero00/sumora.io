<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use getID3;
use App\Models\Meeting;
use App\Jobs\ProcesarReunionSubida;

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


}
