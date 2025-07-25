<?php

namespace App\Domains\Group\Services;

use App\Domains\Group\Repositories\GroupRepositoryInterface;

class GroupService
{
    public function __construct(
        private GroupRepositoryInterface $groupRepository
    ) {}

    public function getAllGroups()
    {
        return $this->groupRepository->all();
    }

    public function getGroupById(int $id)
    {
        return $this->groupRepository->find($id);
    }

    public function createGroup(array $data)
    {
        // Regras de negócio podem ser adicionadas aqui
        if (!isset($data['is_active'])) {
            $data['is_active'] = true;
        }

        // Validar cor hexadecimal se fornecida
        if (isset($data['color']) && !preg_match('/^#[a-fA-F0-9]{6}$/', $data['color'])) {
            $data['color'] = '#3B82F6'; // Default blue
        }

        return $this->groupRepository->create($data);
    }

    public function updateGroup(int $id, array $data)
    {
        // Validar cor hexadecimal se fornecida
        if (isset($data['color']) && !preg_match('/^#[a-fA-F0-9]{6}$/', $data['color'])) {
            $data['color'] = '#3B82F6'; // Default blue
        }

        return $this->groupRepository->update($id, $data);
    }

    public function deleteGroup(int $id): bool
    {
        return $this->groupRepository->delete($id);
    }

    public function getActiveGroups()
    {
        return $this->groupRepository->findActiveGroups();
    }
} 