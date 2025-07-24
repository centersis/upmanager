<?php

namespace App\Domains\Project\Services;

use App\Domains\Project\Repositories\ProjectRepositoryInterface;
use Illuminate\Support\Str;

class ProjectService
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository
    ) {}

    public function getAllProjects()
    {
        return $this->projectRepository->all()->load(['customers', 'updates' => function($query) {
            $query->latest()->limit(1);
        }]);
    }

    public function getProjectById(int $id)
    {
        return $this->projectRepository->find($id)->load(['customers', 'updates']);
    }

    public function createProject(array $data)
    {
        // Regras de negócio
        if (!isset($data['status'])) {
            $data['status'] = 'active';
        }

        // Gerar hash se não fornecido
        if (!isset($data['hash'])) {
            $data['hash'] = Str::uuid()->toString();
        }

        return $this->projectRepository->create($data);
    }

    public function updateProject(int $id, array $data)
    {
        return $this->projectRepository->update($id, $data);
    }

    public function deleteProject(int $id): bool
    {
        return $this->projectRepository->delete($id);
    }

    public function getProjectByHash(string $hash)
    {
        return $this->projectRepository->findByHash($hash);
    }

    public function getProjectsByCustomer(int $customerId)
    {
        return $this->projectRepository->findByCustomer($customerId);
    }
} 