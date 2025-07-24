<?php

namespace Database\Seeders;

use App\Domains\Project\Entities\Project;
use App\Domains\Update\Entities\Update;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UpdateSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::all();

        $updates = [
            // E-commerce Platform (Project ID: 1)
            [
                'project_id' => 1,
                'title' => 'Nova funcionalidade de carrinho de compras',
                'caption' => 'Implementação do carrinho persistente',
                'description' => 'Adicionada funcionalidade de carrinho de compras que persiste entre sessões. Incluí validação de estoque em tempo real e cálculo automático de frete.',
                'views' => 15,
                'hash' => Str::uuid()->toString(),
                'status' => 'published',
            ],
            [
                'project_id' => 1,
                'title' => 'Correção de bugs no sistema de pagamento',
                'caption' => 'Fixes críticos no checkout',
                'description' => 'Corrigidos problemas relacionados ao processamento de pagamentos via cartão de crédito. Melhorada a integração com gateway de pagamento.',
                'views' => 8,
                'hash' => Str::uuid()->toString(),
                'status' => 'published',
            ],
            
            // CRM System (Project ID: 2)
            [
                'project_id' => 2,
                'title' => 'Dashboard de vendas atualizado',
                'caption' => 'Novos gráficos e métricas',
                'description' => 'Implementado novo dashboard com gráficos interativos, métricas de conversão e relatórios de performance de vendas.',
                'views' => 22,
                'hash' => Str::uuid()->toString(),
                'status' => 'published',
            ],
            [
                'project_id' => 2,
                'title' => 'Integração com WhatsApp Business',
                'caption' => 'Comunicação direta com clientes',
                'description' => 'Adicionada integração com WhatsApp Business API para comunicação direta com leads e clientes através da plataforma.',
                'views' => 31,
                'hash' => Str::uuid()->toString(),
                'status' => 'published',
            ],

            // Mobile App (Project ID: 3)
            [
                'project_id' => 3,
                'title' => 'Otimização de performance',
                'caption' => 'App 40% mais rápido',
                'description' => 'Implementadas otimizações que resultaram em 40% de melhoria na velocidade de carregamento. Reduzido uso de memória em 25%.',
                'views' => 12,
                'hash' => Str::uuid()->toString(),
                'status' => 'published',
            ],
            [
                'project_id' => 3,
                'title' => 'Nova funcionalidade de notificações push',
                'caption' => 'Engajamento melhorado',
                'description' => 'Sistema de notificações push personalizadas baseado no comportamento do usuário. Inclui segmentação avançada.',
                'views' => 7,
                'hash' => Str::uuid()->toString(),
                'status' => 'draft',
            ],

            // Analytics Dashboard (Project ID: 4)
            [
                'project_id' => 4,
                'title' => 'Relatórios em tempo real',
                'caption' => 'Dados atualizados instantaneamente',
                'description' => 'Implementado sistema de relatórios em tempo real com WebSockets. Dados são atualizados automaticamente sem necessidade de refresh.',
                'views' => 18,
                'hash' => Str::uuid()->toString(),
                'status' => 'published',
            ],

            // API Gateway (Project ID: 6)
            [
                'project_id' => 6,
                'title' => 'Implementação de rate limiting',
                'caption' => 'Proteção contra abuso da API',
                'description' => 'Adicionado sistema de rate limiting por IP e por usuário. Configuração flexível de limites baseados no plano do cliente.',
                'views' => 9,
                'hash' => Str::uuid()->toString(),
                'status' => 'published',
            ],
            [
                'project_id' => 6,
                'title' => 'Documentação interativa da API',
                'caption' => 'Swagger UI implementado',
                'description' => 'Criada documentação interativa usando Swagger UI. Desenvolvedores podem testar endpoints diretamente na documentação.',
                'views' => 25,
                'hash' => Str::uuid()->toString(),
                'status' => 'published',
            ],
        ];

        foreach ($updates as $updateData) {
            Update::create($updateData);
        }
    }
} 