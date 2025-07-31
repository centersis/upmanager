<?php

namespace App\Domains\Public\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PublicServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register Public domain views
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'public');
        
        // Register Public domain routes (no middleware, public access)
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
    }
} 