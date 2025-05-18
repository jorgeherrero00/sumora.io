@extends('layouts.app')

@section('content')
<h1>Mis reuniones</h1>
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: '{{ session('success') }}',
        confirmButtonColor: '#3085d6',
        timer: 3000,
    });
</script>
@endif

@if ($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        html: `{!! implode('<br>', $errors->all()) !!}`,
        confirmButtonColor: '#d33',
    });
</script>
@endif

<form action="{{ route('reuniones.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="text" name="titulo" placeholder="Título (opcional)">
    <input type="file" name="archivo" required accept=".mp4, .mov, .webm, .mp3, .wav, .ogg">
    <input type="checkbox" name="enviar_google_sheets" value="1"> Guardar también en mi Google Sheets
    <button type="submit">Subir</button> 
</form>

<ul>
@foreach($reuniones as $reunion)
<li class="mb-6">
    {{ $reunion->titulo ?? 'Sin título' }}<br>
    Tipo: {{ ucfirst($reunion->formato_origen) }}<br>
    Archivo: {{ $reunion->archivo }}
    Resumen de la reunión: {{ $reunion->resumen, 100 ?? 'Sin resumen' }}<br>
</li>
@endforeach
</ul>
@endsection