@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $meeting->titulo ?? 'Reunión sin título' }}</h1>

    <p><strong>Tipo:</strong> {{ ucfirst($meeting->formato_origen) }}</p>
    <p><strong>Archivo:</strong> {{ $meeting->archivo }}</p>
    <p><strong>Resumen:</strong><br>{{ $meeting->resumen }}</p>
    <p><strong>Transcripción:</strong><br>{{ $meeting->transcripcion }}</p>

    <h1>Tareas</h1>
    @if ($meeting->tasks->isEmpty())
        <p>No se han identificado tareas.</p>
    @else
        <ul>
            @foreach ($meeting->tasks as $task)
                <li>{{ $task->descripcion }}</li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
