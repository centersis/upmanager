<?php

namespace App\Domains\Update\Providers;

use App\Domains\Update\Repositories\UpdateRepository;
use App\Domains\Update\Repositories\UpdateRepositoryInterface;
use Illuminate\Support\Facades\Route;
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
        
        // Register Update domain migrations
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        
        // Register Update domain routes
        Route::middleware('web')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        });
        
        Route::middleware('api')->prefix('api')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        });
    }
} 