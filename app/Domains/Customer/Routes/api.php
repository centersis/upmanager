<?php

use App\Domains\Customer\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

// Customer API Routes
Route::apiResource('customers', CustomerController::class)->names([
    'index' => 'api.customers.index',
    'store' => 'api.customers.store',
    'show' => 'api.customers.show',
    'update' => 'api.customers.update',
    'destroy' => 'api.customers.destroy',
]); 