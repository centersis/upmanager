<?php

namespace Database\Seeders;

use App\Domains\Group\Entities\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            [
                'name' => 'E-commerce',
                'description' => 'Projetos de lojas virtuais e plataformas de vendas online',
                'color' => '#10B981', // Green
                'is_active' => true,
            ],
            [
                'name' => 'CRM',
                'description' => 'Sistemas de gestão de relacionamento com cliente',
                'color' => '#3B82F6', // Blue
                'is_active' => true,
            ],
            [
                'name' => 'Mobile App',
                'description' => 'Aplicativos móveis para iOS e Android',
                'color' => '#8B5CF6', // Purple
                'is_active' => true,
            ],
            [
                'name' => 'Website',
                'description' => 'Sites institucionais e landing pages',
                'color' => '#F59E0B', // Yellow
                'is_active' => true,
            ],
            [
                'name' => 'Sistema Interno',
                'description' => 'Sistemas internos e ferramentas administrativas',
                'color' => '#EF4444', // Red
                'is_active' => true,
            ],
            [
                'name' => 'API',
                'description' => 'APIs e serviços de integração',
                'color' => '#6B7280', // Gray
                'is_active' => true,
            ],
        ];

        foreach ($groups as $groupData) {
            Group::create($groupData);
        }
    }
}
