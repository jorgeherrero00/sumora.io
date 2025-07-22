<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use getID3;
use App\Models\Meeting;
use App\Jobs\ProcesarReunionSubida;

class IntegrationController extends Controller
{

    public function index()
    {
        // Aquí puedes retornar una vista con las integraciones disponibles
        return view('integrations.api-keys');
    }

    public function store(Request $request)
    {
        // Lógica para almacenar una nueva integración
        $request->validate([
            'nombre' => 'required|string|max:255',
            'configuracion' => 'required|array',
        ]);

        // Guardar la integración en la base de datos
        auth()->user()->integrations()->create($request->all());

        return redirect()->route('integrations.index')->with('success', 'Integración creada correctamente.');
    }


}
