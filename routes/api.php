<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UpdateController;

Route::apiResource('customers', CustomerController::class);
Route::apiResource('projects', ProjectController::class);
Route::apiResource('updates', UpdateController::class); 