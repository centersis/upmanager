<?php

namespace App\Domains\Project\Repositories;

use App\Shared\Contracts\RepositoryInterface;

interface ProjectRepositoryInterface extends RepositoryInterface
{
    public function findByHash(string $hash);
    
    public function findByCustomer(int $customerId);
    
    public function withCustomersAndUpdates();
} 