<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DockerController;

Route::get('/', [DockerController::class, 'index']);
