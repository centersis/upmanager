<?php

namespace App\Domains\Customer\Database\Seeders;

use App\Domains\Customer\Entities\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Acme Corporation',
                'status' => 'active',
            ],
            [
                'name' => 'TechStart Solutions',
                'status' => 'active',
            ],
            [
                'name' => 'Global Industries Ltd',
                'status' => 'active',
            ],
            [
                'name' => 'Digital Innovations Inc',
                'status' => 'active',
            ],
            [
                'name' => 'SmartBiz Enterprises',
                'status' => 'inactive',
            ],
        ];

        foreach ($customers as $customerData) {
            Customer::create($customerData);
        }
    }
} 