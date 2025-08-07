<?php

namespace App\Domains\Auth\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register Auth domain views
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'auth');
        
        // Register Auth domain routes
        Route::middleware('web')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        });

        // Register Auth API routes
        Route::prefix('api')->middleware('api')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        });
    }
} 