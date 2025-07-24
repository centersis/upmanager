<?php

use Illuminate\Support\Facades\Route;
use App\Domains\Customer\Http\Controllers\CustomerController;
use App\Domains\Project\Http\Controllers\ProjectController;
use App\Domains\Update\Http\Controllers\UpdateController;

Route::apiResource('customers', CustomerController::class);
Route::apiResource('projects', ProjectController::class);
Route::apiResource('updates', UpdateController::class); 