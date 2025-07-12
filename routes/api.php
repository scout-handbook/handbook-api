<?php

declare(strict_types=1);

use App\Http\Controllers\CompetenceController;
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

Route::resource('/API/v1.0/competence', CompetenceController::class);

Route::any('{path}', [LegacyController::class, 'call'])->where('path', '.*');
