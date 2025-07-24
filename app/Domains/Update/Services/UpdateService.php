<?php

namespace App\Domains\Update\Services;

use App\Domains\Update\Repositories\UpdateRepositoryInterface;
use Illuminate\Support\Str;

class UpdateService
{
    public function __construct(
        private UpdateRepositoryInterface $updateRepository
    ) {}

    public function getAllUpdates()
    {
        return $this->updateRepository->all()->load(['project', 'customer'])->sortByDesc('created_at');
    }

    public function getUpdateById(int $id)
    {
        return $this->updateRepository->find($id)->load(['project', 'customer']);
    }

    public function createUpdate(array $data)
    {
        // Regras de negócio
        if (!isset($data['status'])) {
            $data['status'] = 'pending';
        }

        // Gerar hash se não fornecido
        if (!isset($data['hash'])) {
            $data['hash'] = Str::uuid()->toString();
        }

        return $this->updateRepository->create($data);
    }

    public function updateUpdate(int $id, array $data)
    {
        return $this->updateRepository->update($id, $data);
    }

    public function deleteUpdate(int $id): bool
    {
        return $this->updateRepository->delete($id);
    }

    public function getUpdatesByProject(int $projectId)
    {
        return $this->updateRepository->findByProject($projectId);
    }

    public function getUpdatesByHash(string $hash)
    {
        return $this->updateRepository->findByHash($hash);
    }

    public function incrementUpdateViews(int $id)
    {
        return $this->updateRepository->incrementViews($id);
    }
} 