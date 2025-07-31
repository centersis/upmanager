<?php

if (!function_exists('plural_pt')) {
    /**
     * Pluralização em português brasileiro
     * 
     * @param string $word Palavra no singular
     * @param int $count Quantidade para determinar plural
     * @return string Palavra no singular ou plural conforme a quantidade
     */
    function plural_pt(string $word, int $count): string
    {
        if ($count === 1) {
            return $word;
        }

        // Regras básicas de pluralização em português
        $plurals = [
            'atualização' => 'atualizações',
            'cliente' => 'clientes', 
            'projeto' => 'projetos',
            'visualização' => 'visualizações',
            'usuário' => 'usuários',
            'relatório' => 'relatórios',
            'categoria' => 'categorias',
            'configuração' => 'configurações',
            'notificação' => 'notificações',
            'integração' => 'integrações',
            'migração' => 'migrações',
            'validação' => 'validações',
            'transformação' => 'transformações',
            'otimização' => 'otimizações',
            'automação' => 'automações',
            'análise' => 'análises',
            'dashboard' => 'dashboards',
            'backup' => 'backups',
            'log' => 'logs',
            'bug' => 'bugs',
            'fix' => 'fixes',
            'teste' => 'testes',
            'função' => 'funções',
            'versão' => 'versões',
            'sessão' => 'sessões',
            'permissão' => 'permissões',
            'conexão' => 'conexões',
            'transação' => 'transações',
        ];

        // Retorna plural específico se existir, caso contrário aplica regra geral
        if (isset($plurals[$word])) {
            return $plurals[$word];
        }

        // Regras gerais simples para português
        if (str_ends_with($word, 'ão')) {
            if (str_ends_with($word, 'ção')) {
                return str_replace('ção', 'ções', $word);
            }
            return str_replace('ão', 'ões', $word);
        }

        if (str_ends_with($word, 'al')) {
            return str_replace('al', 'ais', $word);
        }

        if (str_ends_with($word, 'el')) {
            return str_replace('el', 'eis', $word);
        }

        if (str_ends_with($word, 'ol')) {
            return str_replace('ol', 'óis', $word);
        }

        if (str_ends_with($word, 'ul')) {
            return str_replace('ul', 'uis', $word);
        }

        if (str_ends_with($word, 'r') || str_ends_with($word, 'z')) {
            return $word . 'es';
        }

        if (str_ends_with($word, 's')) {
            return $word; // Palavras que terminam em 's' podem ser invariáveis
        }

        // Regra padrão: adiciona 's'
        return $word . 's';
    }
} 

if (!function_exists('public_project_link')) {
    /**
     * Generate public project link with current locale
     */
    function public_project_link($projectHash, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        // For default Portuguese, don't include language parameter
        if ($locale === 'pt-BR' || $locale === 'pt_BR') {
            return route('public.project', $projectHash);
        }
        
        return route('public.project', [$projectHash, $locale]);
    }
}

if (!function_exists('public_customer_link')) {
    /**
     * Generate public customer link with current locale
     */
    function public_customer_link($customerHash, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        // For default Portuguese, don't include language parameter
        if ($locale === 'pt-BR' || $locale === 'pt_BR') {
            return route('public.customer', $customerHash);
        }
        
        return route('public.customer', [$customerHash, $locale]);
    }
}

if (!function_exists('public_customer_project_link')) {
    /**
     * Generate public customer project link with current locale
     */
    function public_customer_project_link($customerHash, $projectHash, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        // For default Portuguese, don't include language parameter
        if ($locale === 'pt-BR' || $locale === 'pt_BR') {
            return route('public.customer.project', [$customerHash, $projectHash]);
        }
        
        return route('public.customer.project', [$customerHash, $projectHash, $locale]);
    }
}

if (!function_exists('public_iframe_link')) {
    /**
     * Generate public iframe link with current locale
     */
    function public_iframe_link($customerHash, $projectHash, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        // For default Portuguese, don't include language parameter
        if ($locale === 'pt-BR' || $locale === 'pt_BR') {
            return route('public.iframe', [$customerHash, $projectHash]);
        }
        
        return route('public.iframe', [$customerHash, $projectHash, $locale]);
    }
}

if (!function_exists('public_update_link')) {
    /**
     * Generate public update link with current locale
     */
    function public_update_link($updateHash, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        // For default Portuguese, don't include language parameter
        if ($locale === 'pt-BR' || $locale === 'pt_BR') {
            return route('public.update', $updateHash);
        }
        
        return route('public.update', [$updateHash, $locale]);
    }
} 