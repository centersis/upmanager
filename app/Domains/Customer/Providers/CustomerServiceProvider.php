<?php

namespace App\Domains\Customer\Providers;

use App\Domains\Customer\Repositories\CustomerRepository;
use App\Domains\Customer\Repositories\CustomerRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class CustomerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
    }

    public function boot(): void
    {
        //
    }
} 