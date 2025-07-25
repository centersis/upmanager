<?php

namespace App\Domains\Public\Providers;

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
    }
} 