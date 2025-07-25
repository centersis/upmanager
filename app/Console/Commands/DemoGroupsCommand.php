<?php

namespace App\Console\Commands;

use App\Domains\Group\Entities\Group;
use App\Domains\Group\Services\GroupService;
use Illuminate\Console\Command;

class DemoGroupsCommand extends Command
{
    protected $signature = 'demo:groups {action=list : Action to perform (list, create, delete)}';

    protected $description = 'Demo command for groups functionality';

    public function __construct(
        private GroupService $groupService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $action = $this->argument('action');

        return match ($action) {
            'list' => $this->listGroups(),
            'create' => $this->createDemoGroup(),
            'delete' => $this->deleteInactiveGroups(),
            default => $this->showHelp(),
        };
    }

    private function listGroups(): int
    {
        $groups = $this->groupService->getAllGroups();

        if ($groups->isEmpty()) {
            $this->info('Nenhum grupo encontrado.');
            return 0;
        }

        $this->info('Grupos cadastrados:');
        $this->table(
            ['ID', 'Nome', 'Descrição', 'Cor', 'Status', 'Projetos'],
            $groups->map(function (Group $group) {
                return [
                    $group->id,
                    $group->name,
                    $group->description ?? '-',
                    $group->color,
                    $group->is_active ? 'Ativo' : 'Inativo',
                    $group->projects->count(),
                ];
            })->toArray()
        );

        return 0;
    }

    private function createDemoGroup(): int
    {
        $name = $this->ask('Nome do grupo:');
        $description = $this->ask('Descrição (opcional):', '');
        $color = $this->ask('Cor hexadecimal (ex: #3B82F6):', '#3B82F6');

        try {
            $group = $this->groupService->createGroup([
                'name' => $name,
                'description' => $description ?: null,
                'color' => $color,
                'is_active' => true,
            ]);

            $this->info("Grupo '{$group->name}' criado com sucesso! (ID: {$group->id})");
            return 0;
        } catch (\Exception $e) {
            $this->error("Erro ao criar grupo: {$e->getMessage()}");
            return 1;
        }
    }

    private function deleteInactiveGroups(): int
    {
        $inactiveGroups = Group::where('is_active', false)
            ->whereDoesntHave('projects')
            ->get();

        if ($inactiveGroups->isEmpty()) {
            $this->info('Nenhum grupo inativo sem projetos encontrado.');
            return 0;
        }

        $this->info('Grupos inativos sem projetos:');
        $inactiveGroups->each(function (Group $group) {
            $this->line("- {$group->name} (ID: {$group->id})");
        });

        if (!$this->confirm('Deseja excluir todos estes grupos?')) {
            $this->info('Operação cancelada.');
            return 0;
        }

        $count = 0;
        foreach ($inactiveGroups as $group) {
            try {
                $this->groupService->deleteGroup($group->id);
                $count++;
                $this->info("Grupo '{$group->name}' excluído.");
            } catch (\Exception $e) {
                $this->error("Erro ao excluir grupo '{$group->name}': {$e->getMessage()}");
            }
        }

        $this->info("Total de grupos excluídos: {$count}");
        return 0;
    }

    private function showHelp(): int
    {
        $this->info('Ações disponíveis:');
        $this->line('  list   - Listar todos os grupos');
        $this->line('  create - Criar um novo grupo interativamente');
        $this->line('  delete - Excluir grupos inativos sem projetos');
        
        $this->info('Exemplos:');
        $this->line('  php artisan demo:groups list');
        $this->line('  php artisan demo:groups create');
        $this->line('  php artisan demo:groups delete');

        return 0;
    }
} 