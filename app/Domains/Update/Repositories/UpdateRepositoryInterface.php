<?php

namespace App\Domains\Update\Repositories;

use App\Shared\Contracts\RepositoryInterface;

interface UpdateRepositoryInterface extends RepositoryInterface
{
    public function findByProject(int $projectId);
    
    public function findByHash(string $hash);
    
    public function incrementViews(int $id);
    
    public function findBySharedHash(string $sharedHash);
    
    public function updateBySharedHash(string $sharedHash, array $data);
    
    public function allWithFilters(array $filters);
} 