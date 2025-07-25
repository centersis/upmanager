<?php

namespace App\Domains\Group\Http\Controllers;

use App\Domains\Group\Services\GroupService;
use App\Domains\Group\Http\Requests\StoreGroupRequest;
use App\Domains\Group\Http\Requests\UpdateGroupRequest;
use App\Http\Controllers\Controller;

class GroupWebController extends Controller
{
    public function __construct(
        private GroupService $groupService
    ) {}

    public function index()
    {
        $groups = $this->groupService->getAllGroups();
        
        return view('group::index', compact('groups'));
    }

    public function create()
    {
        return view('group::create');
    }

    public function store(StoreGroupRequest $request)
    {
        try {
            $group = $this->groupService->createGroup($request->validated());
            
            return redirect()
                ->route('groups.show', $group->id)
                ->with('success', 'Grupo criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar grupo: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $group = $this->groupService->getGroupById($id);
        
        if (!$group) {
            abort(404, 'Grupo não encontrado');
        }

        // Carregar projetos do grupo
        $group->load('projects.customers');
        
        return view('group::show', compact('group'));
    }

    public function edit($id)
    {
        $group = $this->groupService->getGroupById($id);
        
        if (!$group) {
            abort(404, 'Grupo não encontrado');
        }
        
        return view('group::edit', compact('group'));
    }

    public function update(UpdateGroupRequest $request, $id)
    {
        try {
            $group = $this->groupService->updateGroup($id, $request->validated());
            
            if (!$group) {
                abort(404, 'Grupo não encontrado');
            }
            
            return redirect()
                ->route('groups.show', $group->id)
                ->with('success', 'Grupo atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar grupo: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $deleted = $this->groupService->deleteGroup($id);
            
            if (!$deleted) {
                return redirect()
                    ->back()
                    ->with('error', 'Grupo não encontrado');
            }
            
            return redirect()
                ->route('groups.index')
                ->with('success', 'Grupo excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir grupo: ' . $e->getMessage());
        }
    }
} 