<?php

namespace App\Domains\Customer\Http\Controllers;

use App\Domains\Customer\Services\CustomerService;
use App\Domains\Customer\Http\Requests\StoreCustomerRequest;
use App\Domains\Customer\Http\Requests\UpdateCustomerRequest;
use App\Shared\Http\Controllers\Controller;

class CustomerWebController extends Controller
{
    public function __construct(
        private CustomerService $customerService
    ) {}

    public function index()
    {
        $customers = $this->customerService->getAllCustomers();
        
        return view('customer::index', compact('customers'));
    }

    public function create()
    {
        return view('customer::create');
    }

    public function store(StoreCustomerRequest $request)
    {
        try {
            $customer = $this->customerService->createCustomer($request->validated());
            
            return redirect()
                ->route('customers.show', $customer->id)
                ->with('success', 'Cliente criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar cliente: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $customer = $this->customerService->getCustomerById($id);
        
        if (!$customer) {
            abort(404, 'Cliente não encontrado');
        }

        // Carregar projetos do cliente
        $customer->load('projects.updates');
        
        return view('customer::show', compact('customer'));
    }

    public function edit($id)
    {
        $customer = $this->customerService->getCustomerById($id);
        
        if (!$customer) {
            abort(404, 'Cliente não encontrado');
        }
        
        return view('customer::edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, $id)
    {
        try {
            $customer = $this->customerService->updateCustomer($id, $request->validated());
            
            if (!$customer) {
                abort(404, 'Cliente não encontrado');
            }
            
            return redirect()
                ->route('customers.show', $customer->id)
                ->with('success', 'Cliente atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar cliente: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $deleted = $this->customerService->deleteCustomer($id);
            
            if (!$deleted) {
                return redirect()
                    ->back()
                    ->with('error', 'Cliente não encontrado');
            }
            
            return redirect()
                ->route('customers.index')
                ->with('success', 'Cliente excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir cliente: ' . $e->getMessage());
        }
    }
} 