<?php

namespace Database\Seeders;

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
                'status' => 'active',
                'hash' => Str::uuid()->toString(),
                'customer_ids' => [1, 2], // Acme e TechStart
            ],
            [
                'name' => 'CRM System',
                'status' => 'active',
                'hash' => Str::uuid()->toString(),
                'customer_ids' => [1], // Acme
            ],
            [
                'name' => 'Mobile App',
                'status' => 'active',
                'hash' => Str::uuid()->toString(),
                'customer_ids' => [3], // Global Industries
            ],
            [
                'name' => 'Analytics Dashboard',
                'status' => 'active',
                'hash' => Str::uuid()->toString(),
                'customer_ids' => [4], // Digital Innovations
            ],
            [
                'name' => 'Legacy System Migration',
                'status' => 'inactive',
                'hash' => Str::uuid()->toString(),
                'customer_ids' => [5], // SmartBiz
            ],
            [
                'name' => 'API Gateway',
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