@extends('shared::layouts.app')

@section('title', $customer->name . ' - UPMANAGER')

@section('content')
<div class="mb-8">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
            <li>
                <div>
                    <a href="{{ route('customers.index') }}" class="text-gray-400 hover:text-gray-500">
                        Clientes
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-4 text-sm font-medium text-gray-500">{{ $customer->name }}</span>
                </div>
            </li>
        </ol>
    </nav>
</div>

<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $customer->name }}</h1>
            <div class="mt-2 flex items-center space-x-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $customer->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $customer->status === 'active' ? 'Ativo' : 'Inativo' }}
                </span>
                <span class="text-sm text-gray-500">Cliente desde {{ $customer->created_at->format('d/m/Y') }}</span>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('customers.edit', $customer->id) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Editar
            </a>
            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja excluir este cliente? Esta ação não pode ser desfeita.')">
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
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Projetos</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $customer->projects->count() }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total de Updates</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $customer->projects->sum(fn($project) => $project->updates->count()) }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total de Visualizações</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $customer->projects->sum(fn($project) => $project->updates->sum('views')) }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Projects -->
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">Projetos do Cliente</h3>
        
        @forelse($customer->projects as $project)
        <div class="mb-8 last:mb-0">
            <div class="border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-xl font-semibold text-gray-900">
                        <a href="{{ route('projects.show', $project->id) }}" class="hover:text-blue-600">
                            {{ $project->name }}
                        </a>
                    </h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $project->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $project->status === 'active' ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>

                <div class="mb-4">
                                                    <p class="text-sm text-gray-600">{{ $project->updates->count() }} {{ $project->updates->count() === 1 ? 'atualização' : 'atualizações' }} • Hash: <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ substr($project->hash, 0, 8) }}...</code></p>
                </div>

                @if($project->updates->count() > 0)
                <div class="space-y-3">
                    <h5 class="text-sm font-medium text-gray-900">Últimas Atualizações:</h5>
                    @foreach($project->updates->sortByDesc('created_at')->take(3) as $update)
                    <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded">
                        <div>
                            <a href="{{ route('updates.show', $update->id) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600">
                                {{ $update->title }}
                            </a>
                            <p class="text-xs text-gray-500">{{ $update->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $update->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $update->status === 'published' ? 'Publicado' : 'Rascunho' }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">{{ $update->views }} visualizações</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4 text-gray-500">
                    <p class="text-sm">Nenhuma atualização encontrada para este projeto</p>
                </div>
                @endif

                <!-- Iframe Code Section for this project -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <h5 class="text-sm font-medium text-gray-900 mb-3">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                        Código iframe (últimas 5 atualizações):
                    </h5>
                    <div class="space-y-2">
                        <textarea 
                            id="iframe-code-customer-{{ $customer->id }}-project-{{ $project->id }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-xs font-mono"
                            rows="3"
                            readonly><iframe src="{{ route('public.iframe', [$customer->hash, $project->hash]) }}" width="100%" height="700" frameborder="0" scrolling="auto" style="border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden;"></iframe></textarea>
                        <div class="flex space-x-2">
                            <button onclick="copyIframeCode('iframe-code-customer-{{ $customer->id }}-project-{{ $project->id }}')"
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
                            <a href="{{ route('public.customer.project', [$customer->hash, $project->hash]) }}" 
                               target="_blank"
                               class="inline-flex items-center px-3 py-1.5 border border-green-300 rounded-md bg-green-100 text-green-700 text-xs hover:bg-green-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Página Pública
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum projeto encontrado</h3>
            <p class="mt-1 text-sm text-gray-500">Este cliente ainda não possui projetos.</p>
        </div>
        @endforelse
    </div>
</div>

<script>
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