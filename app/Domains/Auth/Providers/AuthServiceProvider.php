<?php

namespace App\Domains\Auth\Providers;

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
    }
} 