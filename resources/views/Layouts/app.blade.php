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
                <span class="text-sm text-sky-300 font-medium">{{ Auth::user()->alias ?? Auth::user()->nombre }}</span>
            </div>
        </div>
        <!-- Fin del contendor del Logo -->

        <!-- Bloque de consumo Docker -->
        <div class="stats-widget">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sky-200 text-xs font-semibold uppercase tracking-widest">Consumo</span>
                <button id="btn-refresh-stats" class="boton-refresh-stats" title="Actualizar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </div>
            <div class="stat-fila">
                <span class="stat-label">CPU</span>
                <span id="stat-cpu" class="stat-valor">—</span>
            </div>
            <div class="stat-fila">
                <span class="stat-label">RAM</span>
                <span id="stat-ram" class="stat-valor">—</span>
            </div>
            <p id="stat-tiempo" class="stat-tiempo">—</p>
        </div>
        <!-- Fin bloque de consumo Docker -->
    </aside>

    <script>
        // Guarda cuándo fue la última actualización para calcular el tiempo transcurrido
        let lastUpdate = null;

        // Pide los stats al servidor y actualiza el DOM
        async function fetchStats() {
            try {
                const data = await fetch('/docker/stats').then(r => r.json());
                document.getElementById('stat-cpu').textContent = data.cpu + '%';
                document.getElementById('stat-ram').textContent = data.used_gb + ' GB / ' + data.total_gb + ' GB';
                lastUpdate = Date.now();
                updateTimer();
            } catch (e) {
                document.getElementById('stat-cpu').textContent = 'error';
            }
        }

        // Actualiza el texto "Información actualizada hace: X min" en el navegador sin llamar al servidor
        function updateTimer() {
            if (!lastUpdate) return;
            const mins = Math.floor((Date.now() - lastUpdate) / 60000);
            const txt  = mins === 0 ? 'ahora mismo' : 'hace ' + mins + ' min';
            document.getElementById('stat-tiempo').textContent = 'Actualizado ' + txt;
        }

        document.getElementById('btn-refresh-stats').addEventListener('click', fetchStats);

        fetchStats();                          // carga al iniciar la página
        setInterval(fetchStats,  5 * 60 * 1000); // refresca datos cada 5 min
        setInterval(updateTimer, 60 * 1000);     // actualiza el contador cada 1 min
    </script>

    <!-- Área Principal Derecha -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden">

        <!-- Barra Superior (Topbar) / Título -->
        <header>
            <h1>Monitor Docker</h1>
            <div class="flex items-center gap-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm font-semibold text-white bg-red-600/70 hover:bg-red-600/90 border border-red-800 px-3 py-1.5 rounded-lg shadow-inner transition-colors duration-200 cursor-pointer">
                        Cerrar sesión
                    </button>
                </form>
            </div>
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
