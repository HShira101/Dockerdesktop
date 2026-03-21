<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Docker Desktop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-800 font-sans antialiased flex h-screen overflow-hidden">

    <!-- Sidebar / Menú Lateral -->
    <aside>
        <!-- Contenedor del Logo -->
        <div class="logocont">
            <div class="flex items-center gap-3">
                <div
                    class="w-8 h-8 rounded-lg bg-gradient-to-tr from-blue-600 to-indigo-500 shadow-lg shadow-blue-500/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <span class="text-xl font-bold text-white tracking-wide">Panel</span>
            </div>
        </div>
        <!-- Fin del contendor del Logo -->

        <!-- Navegador lateral -->
        <nav>
            <ul>
                <li><button class="boton-nav">Contenedores</button></li>
                <li><button class="boton-nav">Opciones</button></li>
            </ul>
        </nav>
    </aside>

    <!-- Área Principal Derecha -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden">

        <!-- Barra Superior (Topbar) / Título -->
        <header>
            <h1>Monitor Docker</h1>
        </header>

        <!-- Contenedor del Dashboard (Scroll Independiente) -->
        <div class="flex-1 overflow-auto bg-slate-50 p-8">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </div>

    </main>

</body>

</html>
