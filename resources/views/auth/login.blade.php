<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Docker Desktop — Login</title>
    @vite(['resources/css/app.css'])
</head>
<body class="login-pagina">

    <div class="login-caja">

        <!-- Logo -->
        <div class="login-logo">
            <div class="login-icono">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <span class="login-titulo">Panel Docker</span>
        </div>

        <!-- Error -->
        @if ($errors->any())
            <div class="login-error">{{ $errors->first() }}</div>
        @endif

        <!-- Formulario -->
        <form method="POST" action="{{ route('login') }}" class="login-form">
            @csrf

            <div class="login-campo">
                <label class="login-label">Nombre, alias o correo</label>
                <input type="text" name="identificador" value="{{ old('identificador') }}"
                    required autofocus
                    class="login-input"
                    placeholder="Shira / Javier / correo@ejemplo.com">
            </div>

            <div class="login-campo">
                <label class="login-label">Contraseña</label>
                <input type="password" name="password" required
                    class="login-input"
                    placeholder="••••••••">
            </div>

            <button type="submit" class="login-boton">
                Iniciar sesión
            </button>
        </form>

    </div>

</body>
</html>
