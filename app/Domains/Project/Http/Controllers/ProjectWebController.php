<?php

namespace App\Domains\Project\Http\Controllers;

use App\Domains\Project\Services\ProjectService;
use App\Http\Controllers\Controller;

class ProjectWebController extends Controller
{
    public function __construct(
        private ProjectService $projectService
    ) {}

    public function index()
    {
        $projects = $this->projectService->getAllProjects();
        
        return view('projects.index', compact('projects'));
    }

    public function show($id)
    {
        $project = $this->projectService->getProjectById($id);
        
        if (!$project) {
            abort(404, 'Projeto não encontrado');
        }
        
        return view('projects.show', compact('project'));
    }
} 