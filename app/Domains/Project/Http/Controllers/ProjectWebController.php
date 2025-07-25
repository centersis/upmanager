<?php

namespace App\Domains\Project\Http\Controllers;

use App\Domains\Project\Services\ProjectService;
use App\Domains\Customer\Services\CustomerService;
use App\Domains\Group\Entities\Group;
use App\Domains\Project\Http\Requests\StoreProjectRequest;
use App\Domains\Project\Http\Requests\UpdateProjectRequest;
use App\Http\Controllers\Controller;

class ProjectWebController extends Controller
{
    public function __construct(
        private ProjectService $projectService,
        private CustomerService $customerService
    ) {}

    public function index()
    {
        $projects = $this->projectService->getAllProjects();
        
        return view('project::index', compact('projects'));
    }

    public function create()
    {
        $customers = $this->customerService->getAllCustomers()->load('projects');
        $groups = Group::active()->orderBy('name')->get();
        
        return view('project::create', compact('customers', 'groups'));
    }

    public function store(StoreProjectRequest $request)
    {
        try {
            $data = $request->validated();
            $project = $this->projectService->createProject($data);
            
            // Associar clientes se fornecidos
            if (isset($data['customer_ids']) && !empty($data['customer_ids'])) {
                $project->customers()->sync($data['customer_ids']);
            }
            
            return redirect()
                ->route('projects.show', $project->id)
                ->with('success', 'Projeto criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar projeto: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $project = $this->projectService->getProjectById($id);
        
        if (!$project) {
            abort(404, 'Projeto não encontrado');
        }
        
        return view('project::show', compact('project'));
    }

    public function edit($id)
    {
        $project = $this->projectService->getProjectById($id);
        $customers = $this->customerService->getAllCustomers()->load('projects');
        $groups = Group::active()->orderBy('name')->get();
        
        if (!$project) {
            abort(404, 'Projeto não encontrado');
        }
        
        return view('project::edit', compact('project', 'customers', 'groups'));
    }

    public function update(UpdateProjectRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $project = $this->projectService->updateProject($id, $data);
            
            if (!$project) {
                abort(404, 'Projeto não encontrado');
            }
            
            // Atualizar associações de clientes
            if (isset($data['customer_ids'])) {
                $project->customers()->sync($data['customer_ids']);
            }
            
            return redirect()
                ->route('projects.show', $project->id)
                ->with('success', 'Projeto atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar projeto: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $deleted = $this->projectService->deleteProject($id);
            
            if (!$deleted) {
                return redirect()
                    ->back()
                    ->with('error', 'Projeto não encontrado');
            }
            
            return redirect()
                ->route('projects.index')
                ->with('success', 'Projeto excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir projeto: ' . $e->getMessage());
        }
    }
} 