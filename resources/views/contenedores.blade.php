@extends('layouts.app')

@section('content')
    <h1>Contenedores</h1>
    <div class="caja-de-tarjetas">
        @foreach ($lista as $contenedor)
            <x-tarjeta-contenedor :nombre="$contenedor['nombre']" :estado="$contenedor['estado']">
                <p>ID: {{ $contenedor['id'] }}</p>
            </x-tarjeta-contenedor>
        @endforeach
    </div>
@endsection
