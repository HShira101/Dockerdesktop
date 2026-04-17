@props([
    'nombre' => 'Contenedor',
    'estado' => 'apagado',
    'id' => '',
])

<div class="tarjeta">
    <div class="encabezado-tarjeta">
        <h1 class="w-1/2 truncate">{{ $nombre }}</h1>
        <div class="celda-tarjeta">
            @if ($nombre === 'Dockerlocal')
                <button disabled class="boton-tarjeta boton-protegido w-full">Protegido</button>
            @elseif ($estado == 'running')
                <form method="POST" action="/contenedor/parar">
                    @csrf
                    <input type="hidden" name="id" value="{{ $id }}">
                    <input type="hidden" name="nombre" value="{{ $nombre }}">
                    <button class="boton-tarjeta boton-detener w-full">Detener</button>
                </form>
            @else
                <form method="POST" action="/contenedor/iniciar">
                    @csrf
                    <input type="hidden" name="id" value="{{ $id }}">
                    <input type="hidden" name="nombre" value="{{ $nombre }}">
                    <button class="boton-tarjeta boton-iniciar w-full">Iniciar</button>
                </form>
            @endif
        </div>
        <div class="celda-tarjeta">
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
