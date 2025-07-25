<?php

namespace App\Domains\Update\Providers;

use App\Domains\Update\Repositories\UpdateRepository;
use App\Domains\Update\Repositories\UpdateRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class UpdateServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UpdateRepositoryInterface::class, UpdateRepository::class);
    }

    public function boot(): void
    {
        // Register Update domain views
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'update');
    }
} 