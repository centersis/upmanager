<?php

namespace App\Domains\Project\Providers;

use App\Domains\Project\Repositories\ProjectRepository;
use App\Domains\Project\Repositories\ProjectRepositoryInterface;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ProjectServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
    }

    public function boot(): void
    {
        // Register Project domain views
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'project');
        
        // Register Project domain routes
        Route::middleware('web')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        });
        
        Route::middleware('api')->prefix('api')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        });
    }
} 