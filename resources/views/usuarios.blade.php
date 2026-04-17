@extends('layouts.app')

@section('content')

<div class="cabecera-vista">
    <h1 class="titulo-pagina">Usuarios</h1>
    <button onclick="abrirModal('modal-crear')" class="boton-tarjeta boton-iniciar w-auto px-4 py-2">
        + Añadir usuario
    </button>
</div>

{{-- ─── Lista de usuarios ─── --}}
<div class="lista-usuarios">
    @foreach ($usuarios as $usuario)
        <div class="fila-usuario">

            <div class="usuario-info">
                @if ($usuario->session_token)
                    <span class="indicador-activo" title="Activo"></span>
                @else
                    <span class="indicador-inactivo" title="Inactivo"></span>
                @endif
                <div class="min-w-0">
                    <p class="usuario-nombre">{{ $usuario->nombre }}
                        @if($usuario->alias)
                            <span class="usuario-alias">({{ $usuario->alias }})</span>
                        @endif
                    </p>
                    <p class="usuario-correo">{{ $usuario->correo }}</p>
                </div>
            </div>

            <div class="usuario-acciones">
                <button onclick="abrirEditar({{ $usuario->id }}, '{{ addslashes($usuario->nombre) }}', '{{ addslashes($usuario->alias ?? '') }}', '{{ addslashes($usuario->correo) }}')"
                    class="boton-tarjeta boton-iniciar boton-accion">
                    Editar
                </button>

                @if ($usuario->id !== Auth::id())
                    <button onclick="abrirConfirmarEliminar({{ $usuario->id }}, '{{ addslashes($usuario->nombre) }}')"
                        class="boton-tarjeta boton-detener boton-accion">
                        Eliminar
                    </button>
                @else
                    <button disabled class="boton-tarjeta boton-protegido boton-accion">
                        Tú
                    </button>
                @endif
            </div>

        </div>
    @endforeach
</div>

{{-- ─── Form oculto eliminar ─── --}}
<form id="form-eliminar" method="POST" action="" class="hidden">
    @csrf
    @method('DELETE')
</form>

{{-- ─── Modal confirmar eliminar ─── --}}
<div id="modal-confirmar-eliminar" class="modal-overlay">
    <div class="modal-caja modal-centrado">
        <div class="modal-icono-wrapper">
            <div class="icono-advertencia">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
        </div>

        <h2 class="modal-titulo">¿Estás seguro?</h2>
        <p class="modal-mensaje">
            Estás a punto de eliminar a <span id="confirmar-nombre" class="nombre-destacado"></span>.
            Esta acción no se puede deshacer.
        </p>

        <div class="modal-acciones">
            <button type="button" onclick="document.getElementById('form-eliminar').submit()"
                class="boton-tarjeta boton-detener boton-modal">Sí, eliminar</button>
            <button type="button" onclick="cerrarModal('modal-confirmar-eliminar')"
                class="boton-tarjeta boton-protegido boton-modal">Cancelar</button>
        </div>
    </div>
</div>

{{-- ─── Modal crear ─── --}}
<div id="modal-crear" class="modal-overlay">
    <div class="modal-caja">
        <h2 class="modal-titulo">Nuevo usuario</h2>

        @if($errors->any() && old('_modal') === 'crear')
            <div class="login-error mb-4">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="/usuarios" class="login-form">
            @csrf
            <input type="hidden" name="_modal" value="crear">

            <div class="login-campo">
                <label class="login-label">Nombre <span class="requerido">*</span></label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required class="login-input" placeholder="Nombre completo">
            </div>
            <div class="login-campo">
                <label class="login-label">Alias</label>
                <input type="text" name="alias" value="{{ old('alias') }}" class="login-input" placeholder="Opcional">
            </div>
            <div class="login-campo">
                <label class="login-label">Correo <span class="requerido">*</span></label>
                <input type="email" name="correo" value="{{ old('correo') }}" required class="login-input" placeholder="correo@ejemplo.com">
            </div>
            <div class="login-campo">
                <label class="login-label">Contraseña <span class="requerido">*</span></label>
                <input type="password" name="password" required class="login-input" placeholder="Mínimo 6 caracteres">
            </div>

            <div class="modal-acciones">
                <button type="submit" class="boton-tarjeta boton-iniciar boton-modal">Guardar</button>
                <button type="button" onclick="cerrarModal('modal-crear')"
                    class="boton-tarjeta boton-protegido boton-modal">Cancelar</button>
            </div>
        </form>
    </div>
</div>

{{-- ─── Modal editar ─── --}}
<div id="modal-editar" class="modal-overlay">
    <div class="modal-caja">
        <h2 class="modal-titulo">Editar usuario</h2>

        @if($errors->any() && old('_modal') === 'editar')
            <div class="login-error mb-4">{{ $errors->first() }}</div>
        @endif

        <form method="POST" id="form-editar" action="" class="login-form">
            @csrf
            <input type="hidden" name="_modal" value="editar">

            <div class="login-campo">
                <label class="login-label">Nombre <span class="requerido">*</span></label>
                <input type="text" name="nombre" id="edit-nombre" required class="login-input">
            </div>
            <div class="login-campo">
                <label class="login-label">Alias</label>
                <input type="text" name="alias" id="edit-alias" class="login-input" placeholder="Opcional">
            </div>
            <div class="login-campo">
                <label class="login-label">Correo <span class="requerido">*</span></label>
                <input type="email" name="correo" id="edit-correo" required class="login-input">
            </div>
            <div class="login-campo">
                <label class="login-label">Contraseña
                    <span class="login-label-nota">(dejar vacío para no cambiar)</span>
                </label>
                <input type="password" name="password" class="login-input" placeholder="••••••••">
            </div>

            <div class="modal-acciones">
                <button type="submit" class="boton-tarjeta boton-iniciar boton-modal">Guardar</button>
                <button type="button" onclick="cerrarModal('modal-editar')"
                    class="boton-tarjeta boton-protegido boton-modal">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModal(id) {
        document.getElementById(id).style.display = 'flex';
    }

    function cerrarModal(id) {
        document.getElementById(id).style.display = '';
    }

    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', e => {
            if (e.target === overlay) cerrarModal(overlay.id);
        });
    });

    function abrirConfirmarEliminar(id, nombre) {
        document.getElementById('confirmar-nombre').textContent = nombre;
        document.getElementById('form-eliminar').action = '/usuarios/' + id;
        abrirModal('modal-confirmar-eliminar');
    }

    function abrirEditar(id, nombre, alias, correo) {
        document.getElementById('form-editar').action = '/usuarios/' + id;
        document.getElementById('edit-nombre').value  = nombre;
        document.getElementById('edit-alias').value   = alias;
        document.getElementById('edit-correo').value  = correo;
        abrirModal('modal-editar');
    }

    @if($errors->any() && old('_modal'))
        abrirModal('modal-{{ old("_modal") }}');
    @endif
</script>

@endsection
