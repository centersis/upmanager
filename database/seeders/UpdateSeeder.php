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
            // E-commerce Platform (Project ID: 1) - Cliente Acme Corp (ID: 1)
            [
                'project_id' => 1,
                'customer_id' => 1, // Acme Corp
                'title' => 'Nova funcionalidade de carrinho de compras',
                'caption' => 'Implementação do carrinho persistente',
                'description' => 'Adicionada funcionalidade de carrinho de compras que persiste entre sessões. Incluí validação de estoque em tempo real e cálculo automático de frete.',
                'views' => 15,
                'hash' => Str::uuid()->toString(),
                'status' => 'published',
                'is_global' => false, // Específica para este projeto
            ],
            [
                'project_id' => 1,
                'customer_id' => 2, // TechStart Solutions
                'title' => 'Correção de bugs no sistema de pagamento',
                'caption' => 'Fixes críticos no checkout',
                'description' => 'Corrigidos problemas relacionados ao processamento de pagamentos via cartão de crédito. Melhorada a integração com gateway de pagamento.',
                'views' => 8,
                'hash' => Str::uuid()->toString(),
                'status' => 'published',
                'is_global' => false,
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
                'is_global' => false,
            ],
            
            // Atualizações Globais (aplicam-se a todos os projetos do grupo)
            [
                'project_id' => 1, // E-commerce base
                'customer_id' => null, // NULL = Global para todos os clientes do grupo
                'title' => 'Atualização de Segurança - SSL/TLS',
                'caption' => 'Melhoria global de segurança',
                'description' => 'Implementada nova versão do protocolo SSL/TLS em todos os projetos. Esta atualização melhora significativamente a segurança das comunicações.',
                'views' => 45,
                'hash' => Str::uuid()->toString(),
                'status' => 'published',
                'is_global' => true, // GLOBAL - aplica a todos do grupo E-commerce
            ],
            [
                'project_id' => 2, // CRM base
                'customer_id' => null, // NULL = Global para todos os clientes do grupo
                'title' => 'Nova política de LGPD',
                'caption' => 'Conformidade legal atualizada',
                'description' => 'Implementadas novas funcionalidades para compliance com LGPD. Inclui controles de consentimento e relatórios de privacidade.',
                'views' => 32,
                'hash' => Str::uuid()->toString(),
                'status' => 'published',
                'is_global' => true, // GLOBAL - aplica a todos os CRMs
            ],
        ];

        foreach ($updates as $updateData) {
            // Definir is_global como false por padrão se não especificado
            if (!isset($updateData['is_global'])) {
                $updateData['is_global'] = false;
            }
            
            // Se não tem customer_id definido e não é global, usar o primeiro cliente do projeto
            if (!isset($updateData['customer_id']) && !$updateData['is_global']) {
                $project = \App\Domains\Project\Entities\Project::find($updateData['project_id']);
                if ($project && $project->customers->isNotEmpty()) {
                    $updateData['customer_id'] = $project->customers->first()->id;
                }
            }
            
            Update::create($updateData);
        }
    }
} 