<?php

namespace App\Domains\Group\Repositories;

use App\Domains\Group\Entities\Group;

class GroupRepository implements GroupRepositoryInterface
{
    public function all()
    {
        return Group::orderBy('name')->get();
    }

    public function find(int $id)
    {
        return Group::find($id);
    }

    public function create(array $data)
    {
        return Group::create($data);
    }

    public function update(int $id, array $data)
    {
        $group = Group::find($id);
        if ($group) {
            $group->update($data);
            return $group;
        }
        return null;
    }

    public function delete(int $id): bool
    {
        $group = Group::find($id);
        if ($group) {
            // Verificar se há projetos associados
            if ($group->projects()->count() > 0) {
                throw new \Exception('Não é possível excluir o grupo pois existem projetos associados a ele.');
            }
            return $group->delete();
        }
        return false;
    }

    public function findActiveGroups()
    {
        return Group::active()->orderBy('name')->get();
    }
} 