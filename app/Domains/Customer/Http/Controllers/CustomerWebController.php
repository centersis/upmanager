<?php

namespace App\Domains\Customer\Http\Controllers;

use App\Domains\Customer\Services\CustomerService;
use App\Http\Controllers\Controller;

class CustomerWebController extends Controller
{
    public function __construct(
        private CustomerService $customerService
    ) {}

    public function index()
    {
        $customers = $this->customerService->getAllCustomers();
        
        return view('customers.index', compact('customers'));
    }

    public function show($id)
    {
        $customer = $this->customerService->getCustomerById($id);
        
        if (!$customer) {
            abort(404, 'Cliente não encontrado');
        }

        // Carregar projetos do cliente
        $customer->load('projects.updates');
        
        return view('customers.show', compact('customer'));
    }
} 