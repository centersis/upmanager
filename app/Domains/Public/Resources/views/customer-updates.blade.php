@extends('shared::layouts.public')

@section('title', $customer->name . ' - Atualizações - UPMANAGER')

@section('content')
<div class="mb-8">
    <!-- Customer Header -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-8">
        <div class="px-6 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $customer->name }}</h1>
                    <div class="mt-2 flex items-center space-x-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            Cliente
                        </span>
                        <span class="text-sm text-gray-600">
                            {{ $updates->total() }} {{ $updates->total() === 1 ? 'atualização' : 'atualizações' }}
                        </span>
                        <span class="text-sm text-gray-600">
                            {{ $projects->count() }} {{ $projects->count() === 1 ? 'projeto' : 'projetos' }}
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Portal do Cliente</div>
                    <div class="text-xs text-gray-400">Última atualização: {{ $updates->first()?->created_at?->format('d/m/Y H:i') ?? 'Nenhuma' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Info -->
    @if($projects->count() > 0)
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-8">
        <h3 class="text-sm font-medium text-green-900 mb-2">Projetos do Cliente:</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($projects as $project)
                <div class="p-3 bg-white rounded-lg border border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-green-900">{{ $project->name }}</h4>
                            @if($project->group)
                                <p class="text-xs text-green-700">{{ $project->group->name }}</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                {{ ucfirst($project->status) }}
                            </span>
                            <div class="text-xs text-green-600 mt-1">
                                {{ $project->updates->where('status', 'published')->count() }} atualizações
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 flex space-x-2">
                        <a href="{{ route('public.project', $project->hash) }}" 
                           class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded hover:bg-green-200 transition-colors">
                            Ver Todas
                        </a>
                        <a href="{{ route('public.customer.project', [$customer->hash, $project->hash]) }}" 
                           class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded hover:bg-blue-200 transition-colors">
                            Específicas do Cliente
                        </a>
                    </div>
                </div>
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
                        <!-- Project Badge -->
                        <div class="mb-3">
                            <a href="{{ route('public.project', $update->project->hash) }}" 
                               class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 hover:bg-gray-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                {{ $update->project->name }}
                            </a>
                        </div>
                        
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            <a href="{{ route('public.update', $update->hash) }}" class="hover:text-blue-600">
                                {{ $update->title }}
                            </a>
                        </h3>
                        
                        @if($update->caption)
                            <p class="text-gray-600 mb-4">{{ $update->caption }}</p>
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
                            @if($update->project->group)
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    {{ $update->project->group->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="ml-6 flex-shrink-0 flex flex-col space-y-2">
                        <a href="{{ route('public.update', $update->hash) }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Ver Detalhes
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        <a href="{{ route('public.project', $update->project->hash) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Ver Projeto
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
        <p class="mt-1 text-sm text-gray-500">Este cliente ainda não possui atualizações públicas em seus projetos.</p>
    </div>
@endif


@endsection 