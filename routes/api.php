<?php

declare(strict_types=1);

use App\Http\Controllers\CompetenceController;
use App\Http\Controllers\LegacyController;
use App\Http\Middleware\ValidateRole;
use Illuminate\Support\Facades\Route;

Route::apiResource('/API/v1.0/competence', CompetenceController::class)
    ->middlewareFor(['store', 'update', 'destroy'], ValidateRole::class.':administrator');

Route::any('{path}', [LegacyController::class, 'call'])->where('path', '.*');
