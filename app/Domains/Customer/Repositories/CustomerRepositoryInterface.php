<?php

namespace App\Domains\Customer\Repositories;

use App\Shared\Contracts\RepositoryInterface;

interface CustomerRepositoryInterface extends RepositoryInterface
{
    public function findByName(string $name);
    
    public function findActiveCustomers();
} 