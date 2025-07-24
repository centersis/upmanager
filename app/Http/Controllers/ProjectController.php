<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectController extends Controller
{
    public function index()
    {
        return response()->json(Project::with('customers')->get());
    }

    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();

        $project = Project::create($data);

        if (isset($data['customer_ids'])) {
            $project->customers()->sync($data['customer_ids']);
        }

        return response()->json($project->load('customers'), 201);
    }

    public function show(Project $project)
    {
        return response()->json($project->load('customers', 'updates'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $data = $request->validated();

        $project->update($data);

        if (isset($data['customer_ids'])) {
            $project->customers()->sync($data['customer_ids']);
        }

        return response()->json($project->load('customers'));
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return response()->noContent();
    }
} 