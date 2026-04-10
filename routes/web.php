<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DockerController;

Route::get('/', [DockerController::class, 'index']);
Route::post('/contenedor/iniciar', [DockerController::class, 'iniciar']);
Route::post('/contenedor/parar',   [DockerController::class, 'parar']);
