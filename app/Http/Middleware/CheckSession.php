<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSession
{
    private const INACTIVIDAD_MAX = 600; // {-- ← 10 minutos en segundos --}

    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        // {-- ← cierra sesión si lleva más de 10 min sin actividad --}
        $ultimaActividad = session('last_activity');
        if ($ultimaActividad && (time() - $ultimaActividad) > self::INACTIVIDAD_MAX) {
            return $this->cerrarSesion($request, 'Sesión cerrada por inactividad.');
        }

        // {-- ← cierra sesión si otro dispositivo inició sesión después --}
        if (Auth::user()->session_token !== session('auth_token')) {
            return $this->cerrarSesion($request, 'Tu sesión fue iniciada en otro dispositivo.');
        }

        session(['last_activity' => time()]); // {-- ← actualiza el tiempo de última actividad --}

        return $next($request);
    }

    private function cerrarSesion(Request $request, string $mensaje)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->withErrors(['correo' => $mensaje]);
    }
}
