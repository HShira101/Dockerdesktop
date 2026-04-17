<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // {---- Muestra el formulario de login ----}
    public function showLogin()
    {
        return view('auth.login');
    }
    // {---- Fin Muestra el formulario de login ----}

    // {---- Valida credenciales, genera token de sesión único y redirige ----}
    public function login(Request $request)
    {
        $request->validate([
            'identificador' => 'required',
            'password'      => 'required',
        ]);

        $id = $request->identificador;

        // {-- ← busca el usuario por correo, nombre o alias --}
        $usuario = User::where('correo', $id)
            ->orWhere('nombre', $id)
            ->orWhere('alias', $id)
            ->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()
                ->withInput(['identificador' => $id])
                ->withErrors(['identificador' => 'Credenciales incorrectas.']);
        }

        Auth::login($usuario);
        $request->session()->regenerate();

        // {-- ← genera token único para esta sesión y lo almacena en usuario y sesión --}
        $token = Str::uuid()->toString();
        Auth::user()->update(['session_token' => $token]);
        session(['auth_token' => $token, 'last_activity' => time()]);

        return redirect('/');
    }
    // {---- Fin Valida credenciales ----}

    // {---- Cierra sesión, borra token y limpia caché de sesión ----}
    public function logout(Request $request)
    {
        Auth::user()?->update(['session_token' => null]); // {-- ← invalida otras sesiones activas --}

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
    // {---- Fin Cierra sesión ----}
}
