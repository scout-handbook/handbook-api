<?php

use App\Http\Controllers\LegacyController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::any('/API/v1.0/login', [LoginController::class, 'authenticate']);

Route::any('{path}', LegacyController::class)->where('path', '.*');
