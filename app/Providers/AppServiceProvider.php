<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use App\Shared\View\Components\AppLayout;
use App\Shared\View\Components\GuestLayout;

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

        // Register domain views
        $this->loadViewsFrom(app_path('Domains/Auth/Resources/views'), 'auth');
        $this->loadViewsFrom(app_path('Domains/User/Resources/views'), 'user');
        $this->loadViewsFrom(app_path('Domains/Public/Resources/views'), 'public');
        
        // Register view components
        Blade::component('app-layout', AppLayout::class);
        Blade::component('guest-layout', GuestLayout::class);
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
