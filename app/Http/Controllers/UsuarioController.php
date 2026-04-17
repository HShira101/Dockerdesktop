<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    // {---- Lista todos los usuarios ----}
    public function index()
    {
        $usuarios = User::all();
        return view('usuarios', compact('usuarios'));
    }
    // {---- Fin Lista usuarios ----}

    // {---- Crea un nuevo usuario ----}
    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'alias'    => 'nullable|string|max:255',
            'correo'   => 'required|email|unique:users,correo',
            'password' => 'required|string|min:6',
        ]);

        $usuario = User::create([
            'nombre'   => $request->nombre,
            'alias'    => $request->alias,
            'correo'   => $request->correo,
            'password' => $request->password,
        ]);

        return redirect('/usuarios')->with('notificacion', [
            'accion' => 'encendido',
            'nombre' => "Usuario {$usuario->nombre} creado",
        ]);
    }
    // {---- Fin Crea usuario ----}

    // {---- Actualiza un usuario existente ----}
    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'alias'    => 'nullable|string|max:255',
            'correo'   => 'required|email|unique:users,correo,' . $usuario->id,
            'password' => 'nullable|string|min:6',
        ]);

        $datos = $request->only(['nombre', 'alias', 'correo']);

        // {-- ← solo actualiza contraseña si se envió una nueva --}
        if ($request->filled('password')) {
            $datos['password'] = $request->password;
        }

        $usuario->update($datos);

        return redirect('/usuarios')->with('notificacion', [
            'accion' => 'encendido',
            'nombre' => "Usuario {$usuario->nombre} actualizado",
        ]);
    }
    // {---- Fin Actualiza usuario ----}

    // {---- Elimina un usuario (no puede eliminarse a sí mismo) ----}
    public function destroy(User $usuario)
    {
        if ($usuario->id === Auth::id()) {
            return redirect('/usuarios')->with('notificacion', [
                'accion' => 'error',
                'nombre' => 'No puedes eliminarte a ti mismo',
            ]);
        }

        $nombre = $usuario->nombre;
        $usuario->delete();

        return redirect('/usuarios')->with('notificacion', [
            'accion' => 'apagado',
            'nombre' => "Usuario {$nombre} eliminado",
        ]);
    }
    // {---- Fin Elimina usuario ----}
}
