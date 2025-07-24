<?php

namespace App\Domains\Customer\Repositories;

use App\Domains\Customer\Entities\Customer;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function all()
    {
        return Customer::all();
    }

    public function find(int $id)
    {
        return Customer::find($id);
    }

    public function create(array $data)
    {
        return Customer::create($data);
    }

    public function update(int $id, array $data)
    {
        $customer = Customer::find($id);
        if ($customer) {
            $customer->update($data);
            return $customer;
        }
        return null;
    }

    public function delete(int $id): bool
    {
        $customer = Customer::find($id);
        if ($customer) {
            return $customer->delete();
        }
        return false;
    }

    public function findByName(string $name)
    {
        return Customer::where('name', 'like', "%{$name}%")->get();
    }

    public function findActiveCustomers()
    {
        return Customer::where('status', 'active')->get();
    }
} 