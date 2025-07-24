<?php

namespace App\Domains\Customer\Services;

use App\Domains\Customer\Repositories\CustomerRepositoryInterface;

class CustomerService
{
    public function __construct(
        private CustomerRepositoryInterface $customerRepository
    ) {}

    public function getAllCustomers()
    {
        return $this->customerRepository->all();
    }

    public function getCustomerById(int $id)
    {
        return $this->customerRepository->find($id);
    }

    public function createCustomer(array $data)
    {
        // Regras de negócio podem ser adicionadas aqui
        if (!isset($data['status'])) {
            $data['status'] = 'active';
        }

        return $this->customerRepository->create($data);
    }

    public function updateCustomer(int $id, array $data)
    {
        return $this->customerRepository->update($id, $data);
    }

    public function deleteCustomer(int $id): bool
    {
        return $this->customerRepository->delete($id);
    }

    public function searchCustomersByName(string $name)
    {
        return $this->customerRepository->findByName($name);
    }

    public function getActiveCustomers()
    {
        return $this->customerRepository->findActiveCustomers();
    }
} 