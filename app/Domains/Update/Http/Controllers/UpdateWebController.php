<?php

namespace App\Domains\Update\Http\Controllers;

use App\Domains\Update\Services\UpdateService;
use App\Domains\Project\Services\ProjectService;
use App\Domains\Customer\Services\CustomerService;
use App\Domains\Update\Http\Requests\StoreUpdateRequest;
use App\Domains\Update\Http\Requests\UpdateUpdateRequest;
use App\Shared\Http\Controllers\Controller;

class UpdateWebController extends Controller
{
    public function __construct(
        private UpdateService $updateService,
        private ProjectService $projectService,
        private CustomerService $customerService
    ) {}

    public function index()
    {
        // Capturar filtros do request
        $filters = request()->only(['status', 'project_id', 'customer_id', 'search']);
        
        // Remover valores vazios do array de filtros
        $filters = array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });
        
        // Buscar updates com filtros aplicados
        $updates = $this->updateService->getAllUpdates($filters);
        
        // Buscar projetos e clientes para os selects
        $projects = $this->projectService->getAllProjects();
        $customers = $this->customerService->getAllCustomers();
        
        return view('update::index', compact('updates', 'projects', 'customers', 'filters'));
    }

    public function create()
    {
        $projects = $this->projectService->getAllProjects();
        $customers = $this->customerService->getAllCustomers();
        
        return view('update::create', compact('projects', 'customers'));
    }

    public function store(StoreUpdateRequest $request)
    {
        try {
            $data = $request->validated();
            $customerIds = $data['customer_ids'];
            $createdUpdates = [];
            
            // Gerar shared_hash único para vincular todas as atualizações relacionadas
            $sharedHash = uniqid('shared_', true);
            
            // Criar uma atualização para cada cliente selecionado
            foreach ($customerIds as $customerId) {
                $updateData = $data;
                $updateData['customer_id'] = $customerId;
                $updateData['shared_hash'] = $sharedHash;
                
                // Gerar hash único para cada atualização
                $updateData['hash'] = uniqid('upd_', true);
                
                unset($updateData['customer_ids']);
                
                $update = $this->updateService->createUpdate($updateData);
                $createdUpdates[] = $update;
            }
            
            $count = count($createdUpdates);
            
            if ($count === 1) {
                return redirect()
                    ->route('updates.show', $createdUpdates[0]->id)
                    ->with('success', 'Atualização criada com sucesso!');
            } else {
                return redirect()
                    ->route('updates.index')
                    ->with('success', "Foram criadas {$count} atualizações vinculadas com sucesso!");
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar atualização: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $update = $this->updateService->getUpdateById($id);
        
        if (!$update) {
            abort(404, 'Atualização não encontrada');
        }

        // Incrementar visualizações
        $this->updateService->incrementUpdateViews($id);
        
        // Carregar atualizações vinculadas se existir shared_hash
        $linkedUpdates = null;
        if ($update->shared_hash) {
            $linkedUpdates = $this->updateService->getUpdatesBySharedHash($update->shared_hash);
        }
        
        return view('update::show', compact('update', 'linkedUpdates'));
    }

    public function edit($id)
    {
        $update = $this->updateService->getUpdateById($id);
        $projects = $this->projectService->getAllProjects();
        $customers = $this->customerService->getAllCustomers();
        
        if (!$update) {
            abort(404, 'Atualização não encontrada');
        }
        
        return view('update::edit', compact('update', 'projects', 'customers'));
    }

    public function update(UpdateUpdateRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $update = $this->updateService->getUpdateById($id);
            
            if (!$update) {
                abort(404, 'Atualização não encontrada');
            }
            
            // Se a atualização tem shared_hash, atualizar todas as atualizações vinculadas
            if ($update->shared_hash) {
                $updatedCount = $this->updateService->updateUpdatesBySharedHash($update->shared_hash, $data);
                
                return redirect()
                    ->route('updates.show', $update->id)
                    ->with('success', "Atualização e {$updatedCount} atualizações vinculadas foram atualizadas com sucesso!");
            } else {
                // Atualização individual (comportamento antigo)
                $updatedUpdate = $this->updateService->updateUpdate($id, $data);
                
                return redirect()
                    ->route('updates.show', $updatedUpdate->id)
                    ->with('success', 'Atualização atualizada com sucesso!');
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar atualização: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $deleted = $this->updateService->deleteUpdate($id);
            
            if (!$deleted) {
                return redirect()
                    ->back()
                    ->with('error', 'Atualização não encontrada');
            }
            
            return redirect()
                ->route('updates.index')
                ->with('success', 'Atualização excluída com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir atualização: ' . $e->getMessage());
        }
    }
} 