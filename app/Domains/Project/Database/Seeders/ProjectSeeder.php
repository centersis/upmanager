<?php

namespace App\Domains\Project\Database\Seeders;

use App\Domains\Customer\Entities\Customer;
use App\Domains\Project\Entities\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();

        $projects = [
            [
                'name' => 'E-commerce Platform',
                'group_id' => 1, // E-commerce
                'status' => 'active',
                'hash' => Str::uuid()->toString(),
                'customer_ids' => [1, 2], // Acme e TechStart
            ],
            [
                'name' => 'CRM System',
                'group_id' => 2, // CRM
                'status' => 'active',
                'hash' => Str::uuid()->toString(),
                'customer_ids' => [1], // Acme
            ],
            [
                'name' => 'Mobile App',
                'group_id' => 3, // Mobile App
                'status' => 'active',
                'hash' => Str::uuid()->toString(),
                'customer_ids' => [3], // Global Industries
            ],
            [
                'name' => 'Analytics Dashboard',
                'group_id' => 4, // Website (mais próximo de Analytics)
                'status' => 'active',
                'hash' => Str::uuid()->toString(),
                'customer_ids' => [4], // Digital Innovations
            ],
            [
                'name' => 'Legacy System Migration',
                'group_id' => 5, // Sistema Interno
                'status' => 'inactive',
                'hash' => Str::uuid()->toString(),
                'customer_ids' => [5], // SmartBiz
            ],
            [
                'name' => 'API Gateway',
                'group_id' => 6, // API
                'status' => 'active',
                'hash' => Str::uuid()->toString(),
                'customer_ids' => [2, 3], // TechStart e Global
            ],
        ];

        foreach ($projects as $projectData) {
            $customerIds = $projectData['customer_ids'];
            unset($projectData['customer_ids']);

            $project = Project::create($projectData);
            $project->customers()->sync($customerIds);
        }
    }
} 