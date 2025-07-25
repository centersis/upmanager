@extends('shared::layouts.public')

@section('title', $project->name . ' - Atualizações - UPMANAGER')

@section('content')
<div class="mb-8">
    <!-- Project Header -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-8">
        <div class="px-6 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $project->name }}</h1>
                    <div class="mt-2 flex items-center space-x-4">
                        @if($project->group)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $project->group->name }}
                            </span>
                        @endif
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            {{ ucfirst($project->status) }}
                        </span>
                        <span class="text-sm text-gray-600">
                            {{ $updates->total() }} {{ $updates->total() === 1 ? 'atualização' : 'atualizações' }}
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Portal Público</div>
                    <div class="text-xs text-gray-400">Última atualização: {{ $updates->count() > 0 ? $updates->first()->created_at->format('d/m/Y H:i') : 'Nenhuma' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Clients Info -->
    @if($project->customers->count() > 0)
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
        <h3 class="text-sm font-medium text-blue-900 mb-2">Clientes do Projeto:</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($project->customers as $customer)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $customer->name }}
                </span>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Updates List -->
@if($updates->count() > 0)
    <div class="space-y-6">
        @foreach($updates as $update)
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            <a href="{{ route('public.update', $update->hash) }}" class="hover:text-blue-600">
                                {{ $update->title }}
                            </a>
                        </h3>
                        
                        @if($update->caption)
                            <p class="text-gray-600 mb-3">{{ $update->caption }}</p>
                        @endif
                        
                        @if($update->description)
                            <div class="text-gray-700 mb-4 line-clamp-3">
                                {{ Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($update->description))), 200) }}
                            </div>
                        @endif
                        
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $update->created_at->format('d/m/Y H:i') }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                {{ $update->views }} {{ $update->views === 1 ? 'visualização' : 'visualizações' }}
                            </span>
                            @if($update->customer)
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $update->customer->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="ml-6 flex-shrink-0">
                        <a href="{{ route('public.update', $update->hash) }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Ver Detalhes
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($updates->hasPages())
        <div class="mt-8">
            {{ $updates->links() }}
        </div>
    @endif
@else
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma atualização publicada</h3>
        <p class="mt-1 text-sm text-gray-500">Este projeto ainda não possui atualizações públicas.</p>
    </div>
@endif

<!-- Share Links -->
<div class="mt-12 bg-gray-50 rounded-lg p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Compartilhar este projeto</h3>
    <div class="space-y-3">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Link público do projeto:</label>
            <div class="flex">
                <input type="text" 
                       value="{{ route('public.project', $project->hash) }}" 
                       class="flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500 text-sm"
                       readonly>
                <button onclick="copyToClipboard('{{ route('public.project', $project->hash) }}')"
                        class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 rounded-r-md bg-gray-50 text-gray-500 text-sm hover:bg-gray-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Feedback visual simples
        alert('Link copiado para a área de transferência!');
    });
}
</script>
@endsection 