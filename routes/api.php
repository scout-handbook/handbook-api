<?php

declare(strict_types=1);

use App\Http\Controllers\CompetenceController;
use App\Http\Controllers\LegacyController;
use Illuminate\Support\Facades\Route;

Route::resource('/API/v1.0/competence', CompetenceController::class);

Route::any('{path}', [LegacyController::class, 'call'])->where('path', '.*');
