@extends('layouts.app')

@section('content')
    <div class="py-8 px-6 max-w-7xl mx-auto">
        <!-- Header personalizado -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-orange-400 to-red-500 bg-clip-text text-transparent">
                        ¡Hola, {{ Auth::user()->name }}! 👋
                    </h1>
                    <p class="text-gray-400 mt-1">Aquí tienes un resumen de tu actividad reciente</p>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-400">Hoy es</p>
<p class="font-medium text-white">{{ now()->locale('es')->translatedFormat('d M Y') }}</p>                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Reuniones -->
            <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6 hover:border-orange-500/30 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Total Reuniones</p>
                        <p class="text-2xl font-bold text-white">{{ auth()->user()->meetings()->count() }}</p>
                    </div>
                    <div class="bg-orange-500/20 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-green-400 text-sm">
                        +{{ auth()->user()->meetings()->where('created_at', '>=', now()->subDays(7))->count() }} esta semana
                    </span>
                </div>
            </div>

            <!-- Tareas Pendientes -->
<!--             <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6 hover:border-orange-500/30 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Tareas Pendientes</p>
                        <p class="text-2xl font-bold text-white">
                            {{ auth()->user()->meetings()->with('tasks')->get()->pluck('tasks')->flatten()->where('completada', false)->count() }}
                        </p>
                    </div>
                    <div class="bg-blue-500/20 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-orange-400 text-sm">
                        {{ auth()->user()->meetings()->with('tasks')->get()->pluck('tasks')->flatten()->where('completada', true)->count() }} completadas
                    </span>
                </div>
            </div> -->

            <!-- Tiempo Transcrito -->
            <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6 hover:border-orange-500/30 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Horas Analizadas</p>
                        <p class="text-2xl font-bold text-white">
                            {{ number_format(auth()->user()->meetings()->count() * 0.75, 1) }}h
                        </p>
                    </div>
                    <div class="bg-purple-500/20 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-purple-400 text-sm">Tiempo promedio: 45min</span>
                </div>
            </div>

            <!-- Insights Generados -->
            <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6 hover:border-orange-500/30 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Insights Generados</p>
                        <p class="text-2xl font-bold text-white">{{ auth()->user()->meetings()->whereNotNull('resumen')->count() }}</p>
                    </div>
                    <div class="bg-green-500/20 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-green-400 text-sm">98% precisión promedio</span>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Nueva Reunión -->
            <div class="lg:col-span-2">
                <div class="bg-gradient-to-br from-orange-500/10 to-red-500/10 border border-orange-500/20 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-white">Subir Nueva Reunión</h3>
                        <div class="bg-orange-500/20 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-300 mb-4">Arrastra tu archivo de audio o video aquí, o haz clic para seleccionar</p>
                    <div class="flex gap-3">
                        <a href="{{ route('reuniones.index') }}" class="flex-1 bg-gradient-to-r from-orange-500 to-red-500 text-white py-3 px-4 rounded-lg font-medium text-center transition-all hover:-translate-y-0.5 hover:shadow-lg">
                            Ir a Reuniones
                        </a>
                        <button class="bg-gray-700/50 hover:bg-gray-700 text-gray-300 py-3 px-4 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tu Token API -->
            <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-white">Tu Token API</h3>
                    <div class="bg-blue-500/20 p-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="15 7a2 2 0 012 2m0 0a2 2 0 012 2m-2-2a2 2 0 00-2 2m2-2a2 2 0 002 2M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-gray-300 text-sm mb-4">Para usar con extensiones o aplicaciones</p>
                <div class="bg-gray-900/50 p-3 rounded-lg border border-gray-700 mb-4">
                    <code class="text-orange-400 text-sm break-all">
                        {{ auth()->user()->api_token ?? 'Token no generado' }}
                    </code>
                </div>
                <button onclick="copyToClipboard('{{ auth()->user()->api_token }}')" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm transition-colors">
                    Copiar Token
                </button>
            </div>
        </div>

        <!-- Reuniones Recientes y Tareas -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
            <!-- Reuniones Recientes -->
            <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-white">Reuniones Recientes</h3>
                    <a href="{{ route('reuniones.index') }}" class="text-orange-400 hover:text-orange-300 text-sm flex items-center">
                        Ver todas
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse(auth()->user()->meetings()->latest()->limit(4)->get() as $reunion)
                    <div class="bg-gray-700/30 rounded-lg p-4 hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="font-medium text-white mb-1">{{ $reunion->titulo ?? 'Sin título' }}</h4>
                                <div class="flex items-center text-sm text-gray-400 space-x-4">
                                    <span>{{ $reunion->created_at->format('j M') }}</span>
                                    <span class="flex items-center">
                                        <span class="inline-block w-2 h-2 bg-{{ $reunion->resumen ? 'green' : 'orange' }}-400 rounded-full mr-1"></span>
                                        {{ $reunion->resumen ? 'Procesado' : 'Procesando...' }}
                                    </span>
                                </div>
                                @if($reunion->resumen)
                                <p class="text-gray-300 text-sm mt-2 line-clamp-2">{{ Str::limit($reunion->resumen, 100) }}</p>
                                @endif
                            </div>
                            <div class="ml-4 flex space-x-2">
                                <button class="p-2 text-gray-400 hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <p class="text-gray-400">No tienes reuniones todavía</p>
                        <p class="text-sm text-gray-500 mt-1">Sube tu primera reunión para empezar</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Tareas Pendientes -->
<!--             <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-white">Tareas Pendientes</h3>
                    <span class="bg-orange-500/20 text-orange-300 px-2 py-1 rounded-full text-xs">
                        {{ auth()->user()->meetings()->with('tasks')->get()->pluck('tasks')->flatten()->where('completada', false)->count() }} pendientes
                    </span>
                </div>

                <div class="space-y-3">
                    @php
                        $tareas = auth()->user()->meetings()->with('tasks')->get()->pluck('tasks')->flatten()->where('completada', false)->take(5);
                    @endphp
                    
                    @forelse($tareas as $tarea)
                    <div class="flex items-start space-x-3 p-3 bg-gray-700/30 rounded-lg hover:bg-gray-700/50 transition-colors">
                        <input type="checkbox" class="mt-1 w-4 h-4 text-orange-500 border-gray-600 rounded focus:ring-orange-500 focus:ring-2">
                        <div class="flex-1">
                            <p class="text-white text-sm">{{ $tarea->descripcion }}</p>
                            <p class="text-gray-400 text-xs mt-1">
                                De: {{ $tarea->meeting->titulo ?? 'Reunión sin título' }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-400">¡No tienes tareas pendientes!</p>
                        <p class="text-sm text-gray-500 mt-1">Todas las tareas están completadas</p>
                    </div>
                    @endforelse
                </div>

                @if($tareas->count() > 0)
                <div class="mt-6 pt-4 border-t border-gray-700">
                    <button class="w-full text-center text-orange-400 hover:text-orange-300 text-sm font-medium">
                        Ver todas las tareas
                    </button>
                </div>
                @endif
            </div> -->
        </div>

        <!-- Integraciones Rápidas -->
        <!-- <div class="mt-8">
            <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-white">Conecta tus herramientas</h3>
                    <a href="{{ route('integrations.index') }}" class="text-orange-400 hover:text-orange-300 text-sm">Gestionar</a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <button class="p-4 bg-gray-700/30 hover:bg-gray-700/50 rounded-lg transition-colors text-center group">
                        <div class="bg-orange-500/20 p-3 rounded-lg inline-block mb-2 group-hover:bg-orange-500/30 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.459 4.208c.746.606 1.026.56 2.428.466l13.215-.793c.28 0 .047-.28-.046-.326L17.86 1.968c-.42-.326-.981-.7-2.055-.607L3.01 2.295c-.466.046-.56.28-.374.466l1.823 1.447zm1.775 2.055c.187.466.187.886.187 1.727v13.775c0 .746.373 1.027 1.213 1.027.933 0 1.353-.28 1.727-1.12l7.318-11.85c.14-.186.047-.373-.094-.373l-3.896.7c-.28.047-.327.234-.187.42l4.734 7.318c.047.047.047.094.047.187v-1.727l-4.734-7.318c-.094-.14-.14-.326-.047-.514l.886-1.96c.187-.466.047-.98-.42-1.12L4.696 3.628c-.42-.14-.933 0-1.12.42l-.374.793c-.14.326-.28.7-.28 1.213L3.01 6.24c0 .466.28.653.653.653h2.147c.326-.047.42-.28.42-.606zm6.638 2.568c.187-.14.467-.047.653.093l1.12.84c.187.187.187.33.187.514v.046l-1.214 1.68-1.493-1.40c-.094-.187-.094-.33-.047-.514l.794-1.26zm-3.57 11.664c-.14.187-.047.373.094.467l1.214.84c.187.14.373.094.513-.047l2.708-3.734V8.954l-1.214 1.826-3.316 4.56z"/>
                            </svg>
                        </div>
                        <p class="text-white text-sm font-medium">Notion</p>
                        <p class="text-gray-400 text-xs">Conectar</p>
                    </button>

                    <button class="p-4 bg-gray-700/30 hover:bg-gray-700/50 rounded-lg transition-colors text-center group">
                        <div class="bg-green-500/20 p-3 rounded-lg inline-block mb-2 group-hover:bg-green-500/30 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.993 8.448c-.006-.12-.012-.24-.018-.358H8v5.01h7.34c-.305 1.59-1.533 2.962-3.296 3.638v3.033h5.35c3.12-2.867 4.917-7.08 4.917-12.03 0-1.131-.099-2.242-.29-3.323z" fill="#4285F4"/>
                                <path d="M8 21c4.468 0 8.223-1.456 10.957-3.933l-5.35-3.027c-1.48.995-3.38 1.585-5.607 1.585-4.295 0-7.934-2.898-9.235-6.8H-6.58v3.127C-3.825 17.735 1.816 21 8 21z" fill="#34A853"/>
                            </svg>
                        </div>
                        <p class="text-white text-sm font-medium">Google Sheets</p>
                        <p class="text-gray-400 text-xs">Conectar</p>
                    </button>

                    <button class="p-4 bg-gray-700/30 hover:bg-gray-700/50 rounded-lg transition-colors text-center group">
                        <div class="bg-purple-500/20 p-3 rounded-lg inline-block mb-2 group-hover:bg-purple-500/30 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M5.042 15.165a2.528 2.528 0 0 1-2.52 2.523A2.528 2.528 0 0 1 0 15.165a2.527 2.527 0 0 1 2.522-2.52h2.52v2.52zM6.313 15.165a2.527 2.527 0 0 1 2.521-2.52 2.527 2.527 0 0 1 2.521 2.52v6.313A2.528 2.528 0 0 1 8.834 24a2.528 2.528 0 0 1-2.521-2.522v-6.313z"/>
                            </svg>
                        </div>
                        <p class="text-white text-sm font-medium">Slack</p>
                        <p class="text-gray-400 text-xs">Conectar</p>
                    </button>

                    <button class="p-4 bg-gray-700/30 hover:bg-gray-700/50 rounded-lg transition-colors text-center group">
                        <div class="bg-blue-500/20 p-3 rounded-lg inline-block mb-2 group-hover:bg-blue-500/30 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <p class="text-white text-sm font-medium">Más</p>
                        <p class="text-gray-400 text-xs">Explorar</p>
                    </button>
                </div>
            </div>
        </div> -->
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Podrías mostrar una notificación aquí
                alert('Token copiado al portapapeles!');
            });
        }
    </script>
@endsection