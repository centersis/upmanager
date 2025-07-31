<?php

namespace App\Domains\Group\Database\Seeders;

use App\Domains\Group\Entities\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        // Criar grupos padrão
        $groups = [
            [
                'name' => 'Desenvolvimento Web',
                'description' => 'Projetos de desenvolvimento de websites e aplicações web',
                'color' => '#3B82F6',
                'is_active' => true,
            ],
            [
                'name' => 'Mobile Apps',
                'description' => 'Desenvolvimento de aplicativos móveis iOS e Android',
                'color' => '#10B981',
                'is_active' => true,
            ],
            [
                'name' => 'Design & UX',
                'description' => 'Projetos de design gráfico e experiência do usuário',
                'color' => '#F59E0B',
                'is_active' => true,
            ],
            [
                'name' => 'Marketing Digital',
                'description' => 'Campanhas de marketing digital e redes sociais',
                'color' => '#EF4444',
                'is_active' => true,
            ],
            [
                'name' => 'Infraestrutura',
                'description' => 'Projetos de infraestrutura de TI e DevOps',
                'color' => '#8B5CF6',
                'is_active' => true,
            ],
            [
                'name' => 'Consultoria',
                'description' => 'Projetos de consultoria e assessoria técnica',
                'color' => '#06B6D4',
                'is_active' => true,
            ],
        ];

        foreach ($groups as $groupData) {
            Group::firstOrCreate(
                ['name' => $groupData['name']],
                $groupData
            );
        }

        // Criar alguns grupos adicionais usando factory
        if (app()->environment('local')) {
            Group::factory()->count(3)->create();
        }
    }
}
