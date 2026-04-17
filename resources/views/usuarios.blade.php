@extends('layouts.app')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="titulo-pagina">Usuarios</h1>
    <button onclick="abrirModal('modal-crear')" class="boton-tarjeta boton-iniciar w-auto px-4 py-2">
        + Añadir usuario
    </button>
</div>

<!-- Lista de usuarios -->
<div class="flex flex-col gap-3">
    @foreach ($usuarios as $usuario)
        <div class="tarjeta flex-row items-center justify-between px-6 py-4 gap-4">

            <!-- Indicador activo + nombre -->
            <div class="flex items-center gap-3 flex-1 min-w-0">
                @if ($usuario->session_token)
                    <span class="w-3 h-3 rounded-full bg-green-500 shrink-0" title="Activo"></span>
                @else
                    <span class="w-3 h-3 rounded-full bg-red-400 shrink-0" title="Inactivo"></span>
                @endif
                <div class="min-w-0">
                    <p class="font-semibold text-slate-800 truncate">{{ $usuario->nombre }}
                        @if($usuario->alias)
                            <span class="text-slate-400 font-normal text-sm">({{ $usuario->alias }})</span>
                        @endif
                    </p>
                    <p class="text-slate-400 text-sm truncate">{{ $usuario->correo }}</p>
                </div>
            </div>

            <!-- Acciones -->
            <div class="flex gap-2 shrink-0">
                <button onclick="abrirEditar({{ $usuario->id }}, '{{ addslashes($usuario->nombre) }}', '{{ addslashes($usuario->alias ?? '') }}', '{{ addslashes($usuario->correo) }}')"
                    class="boton-tarjeta boton-iniciar w-auto px-3 py-1.5 text-xs">
                    Editar
                </button>

                @if ($usuario->id !== Auth::id())
                    <button onclick="abrirConfirmarEliminar({{ $usuario->id }}, '{{ addslashes($usuario->nombre) }}')"
                        class="boton-tarjeta boton-detener w-auto px-3 py-1.5 text-xs">
                        Eliminar
                    </button>
                @else
                    <button disabled class="boton-tarjeta boton-protegido w-auto px-3 py-1.5 text-xs">
                        Tú
                    </button>
                @endif
            </div>

        </div>
    @endforeach
</div>

{{-- ─── FORM OCULTO ELIMINAR ─── --}}
<form id="form-eliminar" method="POST" action="" class="hidden">
    @csrf
    @method('DELETE')
</form>

{{-- ─── MODAL CONFIRMAR ELIMINAR ─── --}}
<div id="modal-confirmar-eliminar" class="modal-overlay">
    <div class="modal-caja text-center">

        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 rounded-full bg-yellow-500/20 border border-yellow-400/40 flex items-center justify-center">
                <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
        </div>

        <h2 class="modal-titulo justify-center">¿Estás seguro?</h2>
        <p class="text-slate-400 text-sm mb-6">
            Estás a punto de eliminar a <span id="confirmar-nombre" class="text-white font-semibold"></span>.
            Esta acción no se puede deshacer.
        </p>

        <div class="flex gap-3">
            <button type="button" onclick="document.getElementById('form-eliminar').submit()"
                class="boton-tarjeta boton-detener flex-1 py-2">Sí, eliminar</button>
            <button type="button" onclick="cerrarModal('modal-confirmar-eliminar')"
                class="boton-tarjeta boton-protegido flex-1 py-2">Cancelar</button>
        </div>

    </div>
</div>

{{-- ─── MODAL CREAR ─── --}}
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
                <label class="login-label">Nombre <span class="text-red-400">*</span></label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required class="login-input" placeholder="Nombre completo">
            </div>
            <div class="login-campo">
                <label class="login-label">Alias</label>
                <input type="text" name="alias" value="{{ old('alias') }}" class="login-input" placeholder="Opcional">
            </div>
            <div class="login-campo">
                <label class="login-label">Correo <span class="text-red-400">*</span></label>
                <input type="email" name="correo" value="{{ old('correo') }}" required class="login-input" placeholder="correo@ejemplo.com">
            </div>
            <div class="login-campo">
                <label class="login-label">Contraseña <span class="text-red-400">*</span></label>
                <input type="password" name="password" required class="login-input" placeholder="Mínimo 6 caracteres">
            </div>

            <div class="flex gap-3 mt-2">
                <button type="submit" class="boton-tarjeta boton-iniciar flex-1 py-2">Guardar</button>
                <button type="button" onclick="cerrarModal('modal-crear')"
                    class="boton-tarjeta boton-protegido flex-1 py-2">Cancelar</button>
            </div>
        </form>
    </div>
</div>

{{-- ─── MODAL EDITAR ─── --}}
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
                <label class="login-label">Nombre <span class="text-red-400">*</span></label>
                <input type="text" name="nombre" id="edit-nombre" required class="login-input">
            </div>
            <div class="login-campo">
                <label class="login-label">Alias</label>
                <input type="text" name="alias" id="edit-alias" class="login-input" placeholder="Opcional">
            </div>
            <div class="login-campo">
                <label class="login-label">Correo <span class="text-red-400">*</span></label>
                <input type="email" name="correo" id="edit-correo" required class="login-input">
            </div>
            <div class="login-campo">
                <label class="login-label">Contraseña <span class="text-slate-400 font-normal">(dejar vacío para no cambiar)</span></label>
                <input type="password" name="password" class="login-input" placeholder="••••••••">
            </div>

            <div class="flex gap-3 mt-2">
                <button type="submit" class="boton-tarjeta boton-iniciar flex-1 py-2">Guardar</button>
                <button type="button" onclick="cerrarModal('modal-editar')"
                    class="boton-tarjeta boton-protegido flex-1 py-2">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Abre un modal por su id
    function abrirModal(id) {
        document.getElementById(id).style.display = 'flex';
    }

    // Cierra un modal por su id
    function cerrarModal(id) {
        document.getElementById(id).style.display = '';
    }

    // Cierra el modal al hacer clic en el fondo oscuro
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', e => {
            if (e.target === overlay) cerrarModal(overlay.id);
        });
    });

    // Abre el modal de confirmación de eliminación
    function abrirConfirmarEliminar(id, nombre) {
        document.getElementById('confirmar-nombre').textContent = nombre;
        document.getElementById('form-eliminar').action = '/usuarios/' + id;
        abrirModal('modal-confirmar-eliminar');
    }

    // Rellena y abre el modal de edición con los datos del usuario
    function abrirEditar(id, nombre, alias, correo) {
        document.getElementById('form-editar').action = '/usuarios/' + id;
        document.getElementById('edit-nombre').value  = nombre;
        document.getElementById('edit-alias').value   = alias;
        document.getElementById('edit-correo').value  = correo;
        abrirModal('modal-editar');
    }

    // Re-abre el modal si hubo errores de validación
    @if($errors->any() && old('_modal'))
        abrirModal('modal-{{ old("_modal") }}');
    @endif
</script>

@endsection
