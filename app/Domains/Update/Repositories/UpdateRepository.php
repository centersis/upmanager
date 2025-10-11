<?php

namespace App\Domains\Update\Repositories;

use App\Domains\Update\Entities\Update;

class UpdateRepository implements UpdateRepositoryInterface
{
    public function all()
    {
        return Update::with('project')->get();
    }

    public function find(int $id)
    {
        return Update::with('project')->find($id);
    }

    public function create(array $data)
    {
        return Update::create($data)->load('project');
    }

    public function update(int $id, array $data)
    {
        $update = Update::find($id);
        if ($update) {
            $update->update($data);
            return $update->load('project');
        }
        return null;
    }

    public function delete(int $id): bool
    {
        $update = Update::find($id);
        if ($update) {
            return $update->delete();
        }
        return false;
    }

    public function findByProject(int $projectId)
    {
        return Update::where('project_id', $projectId)->with('project')->get();
    }

    public function findByHash(string $hash)
    {
        return Update::where('hash', $hash)->with('project')->get();
    }

    public function incrementViews(int $id)
    {
        $update = Update::find($id);
        if ($update) {
            $update->increment('views');
            return $update;
        }
        return null;
    }

    public function findBySharedHash(string $sharedHash)
    {
        return Update::where('shared_hash', $sharedHash)->with(['project', 'customer'])->get();
    }

    public function updateBySharedHash(string $sharedHash, array $data)
    {
        return Update::where('shared_hash', $sharedHash)->update($data);
    }

    public function allWithFilters(array $filters)
    {
        $query = Update::with(['project', 'customer']);

        // Filtro por status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filtro por projeto
        if (!empty($filters['project_id'])) {
            $query->where('project_id', $filters['project_id']);
        }

        // Filtro por cliente
        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        // Busca por texto em título, caption e description
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('caption', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
} 