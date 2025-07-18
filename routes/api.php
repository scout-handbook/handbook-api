<?php

declare(strict_types=1);

use App\Http\Controllers\LegacyController;
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

Route::any('{path}', [LegacyController::class, 'call'])->where('path', '.*');
