<?php

namespace App\Domains\Dashboard\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register Dashboard domain views
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'dashboard');
        
        // Register Dashboard domain routes
        Route::middleware('web')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        });
    }
} 