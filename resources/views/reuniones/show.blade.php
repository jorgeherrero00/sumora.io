@extends('layouts.app')

@section('content')
<div class="py-12 px-6 max-w-6xl mx-auto">
    <!-- Header con navegación -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('reuniones.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-700/50 hover:bg-gray-700 text-gray-300 hover:text-white rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Volver a reuniones
            </a>
            <div class="h-6 w-px bg-gray-600"></div>
            <h1 class="text-2xl font-bold bg-gradient-to-r from-orange-400 to-red-500 bg-clip-text text-transparent">
                {{ $meeting->titulo ?? 'Reunión sin título' }}
            </h1>
        </div>
        
        <!-- Estado y acciones -->
        <div class="flex items-center gap-3">
            @if($meeting->resumen)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-500/20 text-green-300 border border-green-500/30">
                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Procesada
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-yellow-500/20 text-yellow-300 border border-yellow-500/30">
                    <svg class="animate-spin w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Procesando...
                </span>
            @endif
            
            <button onclick="shareReunion()" class="inline-flex items-center px-4 py-2 bg-blue-600/20 hover:bg-blue-600/30 text-blue-300 rounded-lg transition-colors border border-blue-600/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" />
                </svg>
                Compartir
            </button>
        </div>
    </div>

    <!-- Metadatos de la reunión -->
    <div class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gray-800/40 backdrop-blur-sm border border-gray-700/50 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Fecha</p>
                    <p class="text-lg font-semibold text-white">{{ $meeting->created_at->format('d/m/Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $meeting->created_at->format('H:i') }}</p>
                </div>
                <div class="text-orange-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gray-800/40 backdrop-blur-sm border border-gray-700/50 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Formato</p>
                    <p class="text-lg font-semibold text-white">{{ ucfirst($meeting->formato_origen) }}</p>
                    <p class="text-xs text-gray-500">{{ pathinfo($meeting->archivo, PATHINFO_EXTENSION) }}</p>
                </div>
                <div class="text-blue-500">
                    @if($meeting->formato_origen === 'video')
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    @else
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                        </svg>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-gray-800/40 backdrop-blur-sm border border-gray-700/50 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Tareas</p>
                    <p class="text-lg font-semibold text-white">{{ $meeting->tasks->count() }}</p>
                    <p class="text-xs text-gray-500">
                        {{ $meeting->tasks->where('completada', true)->count() }} completadas
                    </p>
                </div>
                <div class="text-green-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gray-800/40 backdrop-blur-sm border border-gray-700/50 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Estado</p>
                    @if($meeting->guardar_en_google_sheets)
                        <p class="text-lg font-semibold text-white">Sincronizada</p>
                        <p class="text-xs text-gray-500">Google Sheets</p>
                    @else
                        <p class="text-lg font-semibold text-white">Local</p>
                        <p class="text-xs text-gray-500">Solo en Sumora</p>
                    @endif
                </div>
                <div class="{{ $meeting->guardar_en_google_sheets ? 'text-green-500' : 'text-gray-500' }}">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Columna principal: Resumen -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Resumen generado por IA -->
            @if($meeting->resumen)
            <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Análisis de la reunión
                    </h2>
                    <div class="flex gap-2">
                        <button onclick="copyToClipboard('resumen')" class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors" title="Copiar resumen">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                        <button onclick="toggleFullscreen('resumen-content')" class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors" title="Pantalla completa">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div id="resumen-content" class="prose prose-invert max-w-none text-white">
                    {!! Str::markdown($meeting->resumen) !!}
                </div>
            </div>
            @else
            <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6 text-center">
                <div class="flex flex-col items-center py-8">
                    <svg class="animate-spin w-12 h-12 text-orange-500 mb-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-white mb-2">Procesando reunión...</h3>
                    <p class="text-gray-400 text-sm">La IA está analizando tu reunión. Esto puede tomar varios minutos.</p>
                    <button onclick="window.location.reload()" class="mt-4 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors">
                        Actualizar página
                    </button>
                </div>
            </div>
            @endif

            <!-- Transcripción completa -->
            @if($meeting->transcripcion)
            <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-1l-4 4z" />
                        </svg>
                        Transcripción completa
                    </h2>
                    <div class="flex gap-2">
                        <button onclick="copyToClipboard('transcripcion')" class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors" title="Copiar transcripción">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                        <button onclick="toggleTranscript()" class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors" title="Mostrar/Ocultar">
                            <svg id="transcript-toggle-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div id="transcripcion-content" class="max-h-96 overflow-y-auto bg-gray-900/50 rounded-lg p-4 hidden">
                    <p class="text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $meeting->transcripcion }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar: Tareas -->
        <div class="space-y-6">
            <!-- Lista de tareas -->
            <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Tareas extraídas
                        @if($meeting->tasks->count() > 0)
                            <span class="ml-2 px-2 py-1 bg-green-500/20 text-green-300 text-sm rounded-full">
                                {{ $meeting->tasks->count() }}
                            </span>
                        @endif
                    </h2>
                </div>

                @if($meeting->tasks->count() > 0)
                    <div class="space-y-3">
                        @foreach($meeting->tasks as $task)
                        <div class="flex items-start gap-3 p-3 bg-gray-900/50 rounded-lg hover:bg-gray-900/70 transition-colors">
                            <button onclick="toggleTask({{ $task->id }})" class="mt-1 flex-shrink-0">
                                <div class="w-5 h-5 rounded border-2 border-gray-600 flex items-center justify-center task-checkbox {{ $task->completada ? 'bg-green-500 border-green-500' : 'hover:border-green-500' }} transition-colors">
                                    @if($task->completada)
                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </div>
                            </button>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-300 {{ $task->completada ? 'line-through opacity-60' : '' }}">
                                    {{ $task->descripcion }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-700/50">
                        <div class="flex justify-between text-sm text-gray-400">
                            <span>Progreso:</span>
                            <span>{{ $meeting->tasks->where('completada', true)->count() }} / {{ $meeting->tasks->count() }}</span>
                        </div>
                        <div class="mt-2 w-full bg-gray-700 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: {{ $meeting->tasks->count() > 0 ? ($meeting->tasks->where('completada', true)->count() / $meeting->tasks->count()) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-gray-400 text-sm">No se identificaron tareas en esta reunión</p>
                    </div>
                @endif
            </div>

            <!-- Información del archivo -->
            <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Información del archivo
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Nombre:</span>
                        <span class="text-gray-300">{{ basename($meeting->archivo) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Tipo:</span>
                        <span class="text-gray-300">{{ strtoupper(pathinfo($meeting->archivo, PATHINFO_EXTENSION)) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Subido:</span>
                        <span class="text-gray-300">{{ $meeting->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                @if($meeting->archivo)
                <div class="mt-4 pt-4 border-t border-gray-700/50">
                    <a href="{{ Storage::url($meeting->archivo) }}" target="_blank" class="inline-flex items-center w-full justify-center px-4 py-2 bg-blue-600/20 hover:bg-blue-600/30 text-blue-300 rounded-lg transition-colors border border-blue-600/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Descargar archivo original
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
// Copiar contenido al portapapeles
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId + '-content');
    const text = element.innerText || element.textContent;
    
    navigator.clipboard.writeText(text).then(() => {
        // Mostrar notificación
        Swal.fire({
            icon: 'success',
            title: 'Copiado',
            text: 'Contenido copiado al portapapeles',
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    });
}

// Toggle transcripción
function toggleTranscript() {
    const content = document.getElementById('transcripcion-content');
    const icon = document.getElementById('transcript-toggle-icon');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>';
    } else {
        content.classList.add('hidden');
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>';
    }
}

// Toggle tarea completada
function toggleTask(taskId) {
    fetch(`/tasks/${taskId}/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Recargar para actualizar el progreso
        }
    });
}

// Pantalla completa para resumen
function toggleFullscreen(elementId) {
    const element = document.getElementById(elementId);
    
    if (!document.fullscreenElement) {
        element.requestFullscreen().catch(err => {
            console.log(`Error attempting to enable fullscreen: ${err.message}`);
        });
    } else {
        document.exitFullscreen();
    }
}

// Compartir reunión
function shareReunion() {
    const url = window.location.href;
    
    if (navigator.share) {
        navigator.share({
            title: '{{ $meeting->titulo ?? "Reunión" }}',
            text: 'Resumen de reunión generado por Sumora',
            url: url
        });
    } else {
        // Fallback: copiar URL
        navigator.clipboard.writeText(url).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Enlace copiado',
                text: 'El enlace de la reunión se ha copiado al portapapeles',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });
    }
}

// Auto-refresh si la reunión está procesando
@if(!$meeting->resumen)
setTimeout(() => {
    window.location.reload();
}, 30000); // Recargar cada 30 segundos
@endif
</script>

@endsection