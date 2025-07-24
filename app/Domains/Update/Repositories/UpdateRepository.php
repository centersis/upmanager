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
} 