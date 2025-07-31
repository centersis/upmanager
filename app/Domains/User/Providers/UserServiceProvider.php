<?php

namespace App\Domains\User\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register User domain views
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'user');
        
        // Register User domain migrations
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        
        // Register User domain routes
        Route::middleware('web')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        });
    }
} 