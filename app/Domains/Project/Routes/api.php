<?php

use App\Domains\Project\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

// Project API Routes
Route::apiResource('projects', ProjectController::class)->names([
    'index' => 'api.projects.index',
    'store' => 'api.projects.store',
    'show' => 'api.projects.show',
    'update' => 'api.projects.update',
    'destroy' => 'api.projects.destroy',
]); 