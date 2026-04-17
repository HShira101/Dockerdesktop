@extends('layouts.app')

@section('content')
    @if(session('notificacion'))
        @php
            $n     = session('notificacion');
            $clase = match($n['accion']) {
                'encendido' => 'prendido-contenedor',
                'apagado'   => 'apagado-contenedor',
                default     => 'error-contenedor',
            };
            $texto = match($n['accion']) {
                'encendido' => '✅ Se ha encendido: ',
                'apagado'   => '🛑 Se ha apagado: ',
                default     => '⚠️ Error al operar: ',
            } . $n['nombre'];
        @endphp
        <div id="notificacion" class="{{ $clase }}">{{ $texto }}</div>
        <script>
            setTimeout(() => {
                const el = document.getElementById('notificacion');
                el.style.transition = 'opacity 0.4s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 400);
            }, 2000);
        </script>
    @endif
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
