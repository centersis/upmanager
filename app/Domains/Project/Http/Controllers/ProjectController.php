<?php

namespace App\Domains\Project\Http\Controllers;

use App\Domains\Project\Entities\Project;
use App\Domains\Project\Http\Requests\StoreProjectRequest;
use App\Domains\Project\Http\Requests\UpdateProjectRequest;
use App\Domains\Project\Services\ProjectService;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    public function __construct(
        private ProjectService $projectService
    ) {}

    public function index()
    {
        $projects = $this->projectService->getAllProjects();
        return response()->json($projects);
    }

    public function store(StoreProjectRequest $request)
    {
        $project = $this->projectService->createProject($request->validated());
        return response()->json($project, 201);
    }

    public function show(Project $project)
    {
        $project = $this->projectService->getProjectById($project->id);
        return response()->json($project);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $updatedProject = $this->projectService->updateProject($project->id, $request->validated());
        return response()->json($updatedProject);
    }

    public function destroy(Project $project)
    {
        $this->projectService->deleteProject($project->id);
        return response()->noContent();
    }
} 