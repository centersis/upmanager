<?php

namespace App\Domains\System\Providers;

use Illuminate\Support\ServiceProvider;

class SystemServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register System domain migrations (cache, jobs, etc.)
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
} 