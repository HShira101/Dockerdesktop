@props([
    'nombre' => 'Contenedor',
    'estado' => 'apagado', // por defecto estará apagado si no indicamos nada
])

<div class="tarjeta">
    <div class="encabezado-tarjeta">
        <h1>{{ $nombre }}</h1>
        @if ($estado == 'running')
            <p class="w-3 h-3 rounded-full bg-green-500"></p>
        @else
            <p class="w-3 h-3 rounded-full bg-red-500"></p>
        @endif
    </div>

    <div class="cuerpo-tarjeta">
        {{ $slot }}
    </div>
</div>
