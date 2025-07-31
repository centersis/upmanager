<?php

namespace App\Domains\Customer\Providers;

use App\Domains\Customer\Repositories\CustomerRepository;
use App\Domains\Customer\Repositories\CustomerRepositoryInterface;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CustomerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
    }

    public function boot(): void
    {
        // Register Customer domain views
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'customer');
        
        // Register Customer domain routes
        Route::middleware('web')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        });
        
        Route::middleware('api')->prefix('api')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        });
    }
} 