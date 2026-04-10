@props([
    'nombre' => 'Contenedor',
    'estado' => 'apagado',
    'id' => '',
])

<div class="tarjeta">
    <div class="encabezado-tarjeta">
        <h1 class="w-1/2 truncate">{{ $nombre }}</h1>
        <div class="w-1/4">
            @if ($estado == 'running')
                <form method="POST" action="/contenedor/parar">
                    @csrf
                    <input type="hidden" name="id" value="{{ $id }}">
                    <input type="hidden" name="nombre" value="{{ $nombre }}">
                    <button class="boton-tarjeta">⏹️</button>
                </form>
            @else
                <form method="POST" action="/contenedor/iniciar">
                    @csrf
                    <input type="hidden" name="id" value="{{ $id }}">
                    <input type="hidden" name="nombre" value="{{ $nombre }}">
                    <button class="boton-tarjeta">▶️</button>
                </form>
            @endif
        </div>
        <div class="w-1/4 flex justify-center">
            @if ($estado == 'running')
                <p class="w-3 h-3 rounded-full bg-green-500"></p>
            @else
                <p class="w-3 h-3 rounded-full bg-red-500"></p>
            @endif
        </div>
    </div>

    <div class="cuerpo-tarjeta">
        {{ $slot }}
    </div>
</div>
