@extends('layouts.app')

@section('content')
<div class="py-12 px-6 max-w-6xl mx-auto">
    <!-- Header section -->
    <div class="mb-10 text-center">
        <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-orange-400 to-red-500 bg-clip-text text-transparent mb-4">Mis reuniones</h1>
        <p class="text-gray-400 max-w-2xl mx-auto">Gestiona tus conversaciones y obtén resúmenes inteligentes al instante</p>
    </div>

    <!-- Alerts -->
    @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: '{{ session('success') }}',
            confirmButtonColor: '#f97316',
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
            confirmButtonColor: '#f43f5e',
        });
    </script>
    @endif

    <!-- Upload form -->
    <div class="mb-12 bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6 md:p-8 shadow-lg hover:border-orange-500/30 transition-all duration-300">
        <h2 class="text-xl font-semibold mb-6 flex items-center text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
            Subir nueva reunión
        </h2>
        
        <form id="uploadForm" action="{{ route('reuniones.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="titulo" class="block text-sm text-gray-400 mb-2">Título (opcional)</label>
                    <input type="text" name="titulo" id="titulo" placeholder="Ej: Reunión equipo marketing" 
                           class="w-full bg-gray-700/50 border border-gray-600 rounded-lg py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 transition-all">
                </div>
                
                <div>
                    <label for="archivo" class="block text-sm text-gray-400 mb-2">Archivo de audio/video</label>
                    <div class="relative">
                        <input type="file" name="archivo" id="archivo" required accept=".mp4,.mov,.webm,.mp3,.wav,.ogg" 
                               class="w-full bg-gray-700/50 border border-gray-600 rounded-lg py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:bg-orange-500 file:text-white hover:file:bg-orange-600">
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Formatos soportados: MP4, MOV, WEBM, MP3, WAV, OGG (máx. 100MB)</p>
                </div>
            </div>
            
            <!-- <div class="flex items-center">
                <input type="checkbox" name="enviar_google_sheets" id="enviar_google_sheets" value="1" 
                       class="w-4 h-4 text-orange-500 border-gray-600 rounded focus:ring-orange-500">
                <label for="enviar_google_sheets" class="ml-2 text-sm text-gray-300">
                    Guardar también en mi Google Sheets
                </label>
            </div> -->
            
            <!-- Barra de progreso (inicialmente oculta) -->
            <div id="progressContainer" class="hidden space-y-4">
                <div class="bg-gray-700/30 rounded-lg p-4 border border-gray-600/50">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-300 font-medium">Subiendo archivo...</span>
                        <span id="progressText" class="text-orange-400 font-bold">0%</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-3 mb-3 overflow-hidden">
                        <div id="progressBar" class="bg-gradient-to-r from-orange-500 to-red-500 h-3 rounded-full transition-all duration-300 shadow-lg" style="width: 0%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-400">
                        <span id="sizeText">0 MB de 0 MB</span>
                        <span id="speedText">0 MB/s</span>
                    </div>
                    <p id="statusText" class="text-sm text-gray-300 mt-2 flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Preparando subida...
                    </p>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" id="submitBtn" class="group relative overflow-hidden px-6 py-3 rounded-lg bg-gradient-to-r from-orange-500 to-red-500 text-white font-medium shadow-md transition-all duration-300 hover:shadow-lg hover:shadow-orange-500/30 hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                    <span id="buttonText" class="relative z-10 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        Subir reunión
                    </span>
                    <span class="absolute inset-0 bg-gradient-to-r from-orange-600 to-red-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                </button>
            </div>
        </form>
    </div>

    <!-- Reunions list -->
    <div>
        <h2 class="text-2xl font-semibold mb-6 flex items-center text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            Historial de reuniones
        </h2>

        @if(count($reuniones) > 0)
            <div class="grid gap-6">
                @foreach($reuniones as $reunion)
                <div class="bg-gray-800/40 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6 hover:border-orange-500/30 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                        <div>
                            <h3 class="text-xl font-medium text-white">{{ $reunion->titulo ?? 'Sin título' }}</h3>
                            <div class="mt-2 flex flex-wrap gap-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-500/20 text-orange-300">
                                    {{ ucfirst($reunion->formato_origen) }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-700 text-gray-300">
                                    {{ \Carbon\Carbon::parse($reunion->created_at)->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('reuniones.show', $reunion) }}" class="p-2 rounded-lg bg-gray-700/50 hover:bg-gray-700 text-gray-300 hover:text-white transition-colors" title="Ver detalles">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-700/50">
                        <h4 class="text-sm font-medium text-gray-400 mb-2">Resumen de la reunión:</h4>
                        <p class="text-gray-300 line-clamp-3">{{ Str::limit($reunion->resumen ?? 'Sin resumen disponible', 200) }}</p>
                        @if($reunion->tasks && $reunion->tasks->count() > 0)
                            <div class="mt-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-500/20 text-blue-300">
                                    {{ $reunion->tasks->count() }} {{ $reunion->tasks->count() == 1 ? 'tarea' : 'tareas' }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-gray-800/20 rounded-xl border border-gray-700/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <p class="text-gray-400">No tienes reuniones todavía</p>
                <p class="text-sm text-gray-500 mt-2">Sube tu primera reunión para obtener un resumen</p>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('uploadForm');
    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const sizeText = document.getElementById('sizeText');
    const speedText = document.getElementById('speedText');
    const statusText = document.getElementById('statusText');
    const submitBtn = document.getElementById('submitBtn');
    const buttonText = document.getElementById('buttonText');
    
    let startTime;
    let lastLoaded = 0;
    let lastTime;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const fileInput = document.getElementById('archivo');
        
        if (!fileInput.files[0]) {
            Swal.fire({
                icon: 'warning',
                title: 'Archivo requerido',
                text: 'Por favor selecciona un archivo de audio o video',
                confirmButtonColor: '#f97316'
            });
            return;
        }

        // Mostrar barra de progreso
        progressContainer.classList.remove('hidden');
        submitBtn.disabled = true;
        buttonText.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Subiendo...
        `;
        
        startTime = Date.now();
        lastTime = startTime;
        
        // Crear XMLHttpRequest para progreso
        const xhr = new XMLHttpRequest();
        
        // Evento de progreso de subida
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const currentTime = Date.now();
                const percentComplete = Math.round((e.loaded / e.total) * 100);
                
                // Actualizar barra de progreso
                progressBar.style.width = percentComplete + '%';
                progressText.textContent = percentComplete + '%';
                
                // Calcular tamaños
                const loaded = (e.loaded / 1024 / 1024).toFixed(1);
                const total = (e.total / 1024 / 1024).toFixed(1);
                sizeText.textContent = `${loaded} MB de ${total} MB`;
                
                // Calcular velocidad
                if (currentTime > lastTime + 500) { // Actualizar cada 500ms
                    const timeDiff = (currentTime - lastTime) / 1000;
                    const loadedDiff = e.loaded - lastLoaded;
                    const speed = (loadedDiff / timeDiff / 1024 / 1024).toFixed(1);
                    speedText.textContent = `${speed} MB/s`;
                    
                    lastLoaded = e.loaded;
                    lastTime = currentTime;
                }
                
                // Actualizar estado
                if (percentComplete < 100) {
                    statusText.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Subiendo archivo...
                    `;
                } else {
                    statusText.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Procesando archivo... Esto puede tomar varios minutos.
                    `;
                    progressBar.classList.add('animate-pulse');
                }
            }
        });
        
        // Cuando se complete la subida
        xhr.addEventListener('load', function() {
            if (xhr.status === 200) {
                statusText.innerHTML = `
                    <svg class="mr-2 h-4 w-4 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    ✅ Archivo subido correctamente. La IA está procesando tu reunión...
                `;
                
                // Redireccionar después de 3 segundos
                setTimeout(() => {
                    window.location.href = '/reuniones';
                }, 3000);
            } else {
                handleError('Error al subir el archivo. Código: ' + xhr.status);
            }
        });
        
        // Manejar errores
        xhr.addEventListener('error', function() {
            handleError('Error de conexión. Verifica tu internet.');
        });
        
        xhr.addEventListener('timeout', function() {
            handleError('Tiempo agotado. El archivo es muy grande.');
        });
        
        // Configurar y enviar la petición
        xhr.open('POST', '{{ route("reuniones.store") }}');
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('input[name="_token"]').value);
        xhr.timeout = 300000; // 5 minutos
        xhr.send(formData);
    });
    
    function handleError(message) {
        statusText.innerHTML = `
            <svg class="mr-2 h-4 w-4 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            ❌ ${message}
        `;
        submitBtn.disabled = false;
        buttonText.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            Reintentar
        `;
        
        Swal.fire({
            icon: 'error',
            title: 'Error al subir',
            text: message,
            confirmButtonColor: '#f43f5e'
        });
    }
});
</script>

@endsection