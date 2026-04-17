<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DockerController;
use App\Http\Controllers\UsuarioController;
use App\Http\Middleware\CheckSession;

// Rutas públicas de autenticación
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas: requieren sesión activa y pasan por CheckSession
Route::middleware(['auth', CheckSession::class])->group(function () {
    Route::get('/',             [DockerController::class, 'index']);
    Route::get('/docker/stats', [DockerController::class, 'stats']);
    Route::post('/contenedor/iniciar', [DockerController::class, 'iniciar']);
    Route::post('/contenedor/parar',   [DockerController::class, 'parar']);

    Route::get('/usuarios',              [UsuarioController::class, 'index']);
    Route::post('/usuarios',             [UsuarioController::class, 'store']);
    Route::post('/usuarios/{usuario}',   [UsuarioController::class, 'update']);
    Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy']);
});
