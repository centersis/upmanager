<?php

namespace App\Domains\Group\Providers;

use Illuminate\Support\Facades\Route;
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
        
        // Register Group domain migrations
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        
        // Register Group domain routes
        Route::middleware('web')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        });
        
        Route::middleware('api')->prefix('api')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        });
    }
} 