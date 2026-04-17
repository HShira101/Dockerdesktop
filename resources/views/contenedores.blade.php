@extends('layouts.app')

@section('content')
<h1>Contenedores</h1>
    <div class="caja-de-tarjetas">
        @foreach ($lista as $contenedor)
            <x-tarjeta-contenedor :nombre="$contenedor['nombre']" :estado="$contenedor['estado']" :id="$contenedor['id']">
                <div class="flex items-center gap-1">
                    <p>Dirección local:</p>
                    <p class="dato-puerto">{{ $contenedor['puerto'] }}</p>
                </div>
                <p>Network: {{ $contenedor['network'] }}</p>
                <br>
                <p>ID: {{ $contenedor['id'] }}</p>
                <p>Creado el: {{ $contenedor['creado'] }}</p>
            </x-tarjeta-contenedor>
        @endforeach
    </div>

<script>
    // Limpia el texto de puerto para mostrar solo la parte que escucha.
    // Entrada:  "0.0.0.0:10000->80/tcp, :::10000->80/tcp"
    // Salida:   "0.0.0.0:10000"
    document.querySelectorAll('.dato-puerto').forEach(el => {
        const raw   = el.textContent.trim();                         // valor crudo del puerto
        const valor = raw.split('->')[0].trim();                     // toma solo hasta "->"

        if (raw.includes('->')) {
            el.textContent = '';                                     // limpia el texto crudo
            const link = document.createElement('a');                // crea el enlace con el puerto limpio
            const puerto = valor.replace('0.0.0.0', 'localhost');     // reemplaza 0.0.0.0 por localhost
            link.href        = 'http://' + puerto;
            link.textContent = puerto;
            link.target      = '_blank';
            link.rel         = 'noopener noreferrer';
            el.appendChild(link);
        }
        // si no hay "->", deja el texto tal cual
    });
</script>

@endsection
