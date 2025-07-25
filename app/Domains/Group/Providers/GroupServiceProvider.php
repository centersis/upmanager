<?php

namespace App\Domains\Group\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domains\Group\Repositories\GroupRepository;
use App\Domains\Group\Repositories\GroupRepositoryInterface;

class GroupServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repository interface to implementation
        $this->app->bind(GroupRepositoryInterface::class, GroupRepository::class);
    }

    public function boot(): void
    {
        // Load views
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'group');
    }
} 