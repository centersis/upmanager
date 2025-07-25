@extends('shared::layouts.app')

@section('title', $project->name . ' - UPMANAGER')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
            <li>
                <div>
                    <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-500">
                        Dashboard
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <a href="{{ route('projects.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                        Projetos
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-4 text-sm font-medium text-gray-500">{{ $project->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Project Header -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-8">
        <div class="px-6 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $project->name }}</h1>
                    <div class="mt-2 flex items-center space-x-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $project->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $project->status === 'active' ? 'Ativo' : 'Inativo' }}
                        </span>
                        @if($project->group)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium text-white" 
                                  style="background-color: {{ $project->group->color }}">
                                {{ $project->group->name }}
                            </span>
                        @endif
                        <span class="text-sm text-gray-600">
                            Criado em {{ $project->created_at->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('projects.edit', $project->id) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar
                    </a>
                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja excluir este projeto? Esta ação não pode ser desfeita.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Excluir
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="mt-6">
                <dl class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Hash do Projeto</dt>
                        <dd class="mt-1">
                            <code class="text-sm bg-gray-100 px-2 py-1 rounded">{{ $project->hash }}</code>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total de Clientes</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $project->customers->count() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total de Atualizações</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $project->updates->count() }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Clientes Associados -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Clientes Associados</h2>
                </div>
                <div class="p-6">
                    @forelse($project->customers as $customer)
                        <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <div>
                                <a href="{{ route('customers.show', $customer->id) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600">
                                    {{ $customer->name }}
                                </a>
                                <p class="text-xs text-gray-500 mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ $customer->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $customer->status === 'active' ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">Nenhum cliente associado</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Atualizações -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Atualizações do Projeto</h2>
                </div>
                <div class="p-6">
                    @php
                        $limitedUpdates = $project->updates->sortByDesc('created_at')->take(5);
                        $hasMoreUpdates = $project->updates->count() > 5;
                    @endphp
                    
                    @forelse($limitedUpdates as $update)
                        <div class="relative pb-8 {{ !$loop->last && !$hasMoreUpdates ? 'border-l-2 border-gray-200 ml-4' : ($hasMoreUpdates ? 'border-l-2 border-gray-200 ml-4' : '') }}">
                            <div class="relative flex items-start space-x-3">
                                <div class="relative">
                                    <div class="h-8 w-8 bg-blue-500 rounded-full flex items-center justify-center ring-8 ring-white">
                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    @if(!$loop->last || $hasMoreUpdates)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div>
                                        <div class="text-sm">
                                            <a href="{{ route('updates.show', $update->id) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                                {{ $update->title }}
                                            </a>
                                        </div>
                                        <p class="mt-0.5 text-sm text-gray-500">
                                            {{ $update->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-700">
                                        <p class="font-medium">{{ $update->caption }}</p>
                                    </div>
                                    <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            {{ $update->views }} visualizações
                                        </span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                            {{ $update->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $update->status === 'published' ? 'Publicado' : 'Rascunho' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma atualização</h3>
                            <p class="mt-1 text-sm text-gray-500">Este projeto ainda não possui atualizações.</p>
                        </div>
                    @endforelse

                    @if($hasMoreUpdates)
                        <div class="relative">
                            <div class="relative flex items-start space-x-3">
                                <div class="relative">
                                    <div class="h-8 w-8 bg-gray-300 rounded-full flex items-center justify-center ring-8 ring-white">
                                        <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm text-gray-600">
                                                <strong>{{ $project->updates->count() - 5 }}</strong> {{ $project->updates->count() - 5 === 1 ? 'atualização adicional' : 'atualizações adicionais' }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">Clique para ver todas as atualizações deste projeto</p>
                                        </div>
                                        <a href="{{ route('public.project', $project->hash) }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-3 py-2 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                            Ver Todas
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Public Links Section -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-8">
        <h3 class="text-lg font-medium text-blue-900 mb-4">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
            </svg>
            Links Públicos do Projeto
        </h3>
        <p class="text-blue-700 text-sm mb-4">
            Compartilhe estes links com clientes para que possam acessar as atualizações sem fazer login.
        </p>
        
        <div class="space-y-4">
            <!-- Project Public Link -->
            <div>
                <label class="block text-sm font-medium text-blue-900 mb-2">
                    Link público do projeto (todas as atualizações):
                </label>
                <div class="flex">
                    <input type="text" 
                           value="{{ route('public.project', $project->hash) }}" 
                           class="flex-1 min-w-0 block w-full px-3 py-2 border border-blue-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500 text-sm bg-white"
                           readonly>
                    <button onclick="copyToClipboard('{{ route('public.project', $project->hash) }}')"
                            class="inline-flex items-center px-3 py-2 border border-l-0 border-blue-300 rounded-r-md bg-blue-100 text-blue-700 text-sm hover:bg-blue-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </button>
                    <a href="{{ route('public.project', $project->hash) }}" 
                       target="_blank"
                       class="inline-flex items-center px-3 py-2 border border-l-0 border-blue-300 rounded-r-md bg-blue-600 text-white text-sm hover:bg-blue-700 ml-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Customer Links -->
            @if($project->customers->count() > 0)
                <div>
                    <label class="block text-sm font-medium text-blue-900 mb-2">
                        Links específicos por cliente:
                    </label>
                    <div class="space-y-2">
                        @foreach($project->customers as $customer)
                            <div class="bg-white rounded-md p-3 border border-blue-200">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-900">{{ $customer->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $customer->email }}</span>
                                </div>
                                <div class="flex">
                                    <input type="text" 
                                           value="{{ route('public.customer', $customer->hash) }}" 
                                           class="flex-1 min-w-0 block w-full px-2 py-1 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500 text-xs bg-gray-50"
                                           readonly>
                                    <button onclick="copyToClipboard('{{ route('public.customer', $customer->hash) }}')"
                                            class="inline-flex items-center px-2 py-1 border border-l-0 border-gray-300 rounded-r-md bg-gray-100 text-gray-600 text-xs hover:bg-gray-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </button>
                                    <a href="{{ route('public.customer', $customer->hash) }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-2 py-1 border border-l-0 border-gray-300 rounded-r-md bg-blue-600 text-white text-xs hover:bg-blue-700 ml-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Iframe Code Section -->
            @if($project->customers->count() > 0)
                <div>
                    <label class="block text-sm font-medium text-blue-900 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                        Código iframe para incorporar (últimas 5 atualizações):
                    </label>
                    <div class="space-y-3">
                        @foreach($project->customers as $customer)
                            <div class="bg-white rounded-md p-3 border border-blue-200">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-900">{{ $customer->name }}</span>
                                    <span class="text-xs text-gray-500">Widget iframe</span>
                                </div>
                                <div class="space-y-2">
                                    <textarea 
                                        id="iframe-code-{{ $customer->id }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-xs font-mono"
                                        rows="3"
                                        readonly><iframe src="{{ route('public.iframe', [$customer->hash, $project->hash]) }}" width="100%" height="700" frameborder="0" scrolling="auto" style="border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden;"></iframe></textarea>
                                    <div class="flex space-x-2">
                                        <button onclick="copyIframeCode('iframe-code-{{ $customer->id }}')"
                                                class="inline-flex items-center px-3 py-1.5 border border-blue-300 rounded-md bg-blue-100 text-blue-700 text-xs hover:bg-blue-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            Copiar Código
                                        </button>
                                        <a href="{{ route('public.iframe', [$customer->hash, $project->hash]) }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md bg-gray-100 text-gray-700 text-xs hover:bg-gray-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Visualizar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Feedback visual
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
        notification.textContent = 'Link copiado para a área de transferência!';
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }).catch(function() {
        alert('Erro ao copiar link. Tente selecionar e copiar manualmente.');
    });
}

function copyIframeCode(textareaId) {
    const textarea = document.getElementById(textareaId);
    const iframeCode = textarea.value;
    
    navigator.clipboard.writeText(iframeCode).then(function() {
        // Feedback visual
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-blue-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
        notification.textContent = 'Código iframe copiado para a área de transferência!';
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }).catch(function() {
        // Fallback para navegadores que não suportam clipboard API
        textarea.select();
        textarea.setSelectionRange(0, 99999); // Para dispositivos móveis
        try {
            document.execCommand('copy');
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-blue-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
            notification.textContent = 'Código iframe copiado para a área de transferência!';
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        } catch (err) {
            alert('Erro ao copiar código. Tente selecionar e copiar manualmente.');
        }
    });
}
</script>

@endsection 