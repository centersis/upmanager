<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRoutes();
        
        // Configure pagination view
        \Illuminate\Pagination\Paginator::defaultView('pagination::tailwind');
        \Illuminate\Pagination\Paginator::defaultSimpleView('pagination::simple-tailwind');
        
        // Register Shared views (layouts, components, etc.)
        $this->loadViewsFrom(app_path('Shared/Resources/views'), 'shared');

        // Register Auth domain views (para resolver auth::*)
        $this->loadViewsFrom(app_path('Domains/Auth/Resources/views'), 'auth');
    }

    /**
     * Configure the application routes.
     */
    protected function configureRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));
    }
}
