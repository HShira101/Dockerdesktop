@extends('layouts.app')

@section('content')
    @if(session('notificacion'))
        @php $n = session('notificacion'); @endphp
        <div id="notificacion" class="
            @if($n['accion'] === 'encendido') prendido-contenedor
            @elseif($n['accion'] === 'apagado') apagado-contenedor
            @else error-contenedor @endif">
            <div class="notif-mensaje notif-{{ $n['accion'] }}">
                @if($n['accion'] === 'encendido') ✅ Se ha encendido: {{ $n['nombre'] }}
                @elseif($n['accion'] === 'apagado') 🛑 Se ha apagado: {{ $n['nombre'] }}
                @else ⚠️ Error al operar: {{ $n['nombre'] }}
                @endif
            </div>
        </div>
        <br>
        <script>
            setTimeout(() => {
                const el = document.getElementById('notificacion');
                document.body.appendChild(el);
                el.style.transition = 'opacity 0.4s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 400);
            }, 2000);
        </script>
    @endif
    <h1>Contenedores</h1>
    <div class="caja-de-tarjetas">
        @foreach ($lista as $contenedor)
            <x-tarjeta-contenedor :nombre="$contenedor['nombre']" :estado="$contenedor['estado']">
                <p>ID: {{ $contenedor['id'] }}</p>
                <p>Puerto: {{ $contenedor['puerto'] }}</p>
                <p>Network: {{ $contenedor['network'] }}</p>
                <p>Creado el: {{ $contenedor['creado'] }}</p>
            </x-tarjeta-contenedor>
        @endforeach
    </div>
@endsection
