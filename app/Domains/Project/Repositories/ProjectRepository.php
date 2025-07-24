<?php

namespace App\Domains\Project\Repositories;

use App\Domains\Project\Entities\Project;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function all()
    {
        return Project::with('customers')->get();
    }

    public function find(int $id)
    {
        return Project::with('customers', 'updates')->find($id);
    }

    public function create(array $data)
    {
        $project = Project::create($data);
        
        if (isset($data['customer_ids'])) {
            $project->customers()->sync($data['customer_ids']);
        }
        
        return $project->load('customers');
    }

    public function update(int $id, array $data)
    {
        $project = Project::find($id);
        if ($project) {
            $project->update($data);
            
            if (isset($data['customer_ids'])) {
                $project->customers()->sync($data['customer_ids']);
            }
            
            return $project->load('customers');
        }
        return null;
    }

    public function delete(int $id): bool
    {
        $project = Project::find($id);
        if ($project) {
            return $project->delete();
        }
        return false;
    }

    public function findByHash(string $hash)
    {
        return Project::where('hash', $hash)->first();
    }

    public function findByCustomer(int $customerId)
    {
        return Project::whereHas('customers', function ($query) use ($customerId) {
            $query->where('customer_id', $customerId);
        })->get();
    }

    public function withCustomersAndUpdates()
    {
        return Project::with('customers', 'updates')->get();
    }
} 