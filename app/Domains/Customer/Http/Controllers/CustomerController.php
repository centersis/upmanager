<?php

namespace App\Domains\Customer\Http\Controllers;

use App\Domains\Customer\Entities\Customer;
use App\Domains\Customer\Http\Requests\StoreCustomerRequest;
use App\Domains\Customer\Http\Requests\UpdateCustomerRequest;
use App\Domains\Customer\Services\CustomerService;
use App\Shared\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function __construct(
        private CustomerService $customerService
    ) {}

    public function index()
    {
        $customers = $this->customerService->getAllCustomers();
        return response()->json($customers);
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = $this->customerService->createCustomer($request->validated());
        return response()->json($customer, 201);
    }

    public function show(Customer $customer)
    {
        return response()->json($customer);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $updatedCustomer = $this->customerService->updateCustomer($customer->id, $request->validated());
        return response()->json($updatedCustomer);
    }

    public function destroy(Customer $customer)
    {
        $this->customerService->deleteCustomer($customer->id);
        return response()->noContent();
    }
} 