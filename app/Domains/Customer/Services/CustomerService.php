<?php

namespace App\Domains\Customer\Services;

use App\Domains\Customer\Repositories\CustomerRepositoryInterface;
use App\Domains\Customer\Entities\CustomerContact;
use Illuminate\Support\Facades\DB;

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

        return DB::transaction(function () use ($data) {
            $contacts = $data['contacts'] ?? [];
            unset($data['contacts']);

            $customer = $this->customerRepository->create($data);

            // Criar contatos se existirem
            if (!empty($contacts)) {
                $this->createContacts($customer->id, $contacts);
            }

            return $customer->load('contacts');
        });
    }

    public function updateCustomer(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $contacts = $data['contacts'] ?? [];
            unset($data['contacts']);

            $customer = $this->customerRepository->update($id, $data);

            if (!empty($contacts)) {
                $this->updateContacts($id, $contacts);
            }

            return $customer->load('contacts');
        });
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

    private function createContacts(int $customerId, array $contacts): void
    {
        foreach ($contacts as $contactData) {
            if (!empty($contactData['name'])) {
                CustomerContact::create([
                    'customer_id' => $customerId,
                    'name' => $contactData['name'],
                    'phone' => $contactData['phone'] ?? null,
                    'email' => $contactData['email'] ?? null,
                ]);
            }
        }
    }

    private function updateContacts(int $customerId, array $contacts): void
    {
        $existingContactIds = [];

        foreach ($contacts as $contactData) {
            if (empty($contactData['name'])) {
                continue;
            }

            if (isset($contactData['_destroy']) && $contactData['_destroy']) {
                // Marcar para remoção
                if (isset($contactData['id'])) {
                    CustomerContact::where('customer_id', $customerId)
                        ->where('id', $contactData['id'])
                        ->delete();
                }
                continue;
            }

            if (isset($contactData['id'])) {
                // Atualizar contato existente
                CustomerContact::where('customer_id', $customerId)
                    ->where('id', $contactData['id'])
                    ->update([
                        'name' => $contactData['name'],
                        'phone' => $contactData['phone'] ?? null,
                        'email' => $contactData['email'] ?? null,
                    ]);
                $existingContactIds[] = $contactData['id'];
            } else {
                // Criar novo contato
                $newContact = CustomerContact::create([
                    'customer_id' => $customerId,
                    'name' => $contactData['name'],
                    'phone' => $contactData['phone'] ?? null,
                    'email' => $contactData['email'] ?? null,
                ]);
                $existingContactIds[] = $newContact->id;
            }
        }
    }
} 