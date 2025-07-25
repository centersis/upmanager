@extends('shared::layouts.iframe')

@section('title', $customer->name . ' - ' . $project->name . ' - Atualizações')

@section('content')
<div class="p-4 bg-white min-h-full">
    <!-- Header -->
    <div class="mb-4 border-b border-gray-200 pb-3">
        <h2 class="text-lg font-semibold text-gray-900">{{ $project->name }}</h2>
        <p class="text-sm text-gray-600">{{ $customer->name }}</p>
        @if($project->group)
            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium text-white mt-1" 
                  style="background-color: {{ $project->group->color }}">
                {{ $project->group->name }}
            </span>
        @endif
    </div>

    <!-- Updates List -->
    <div class="space-y-3">
        @forelse($updates as $update)
            <a href="{{ route('public.update', $update->hash) }}" 
               target="_blank"
               class="block border border-gray-200 rounded-lg p-3 hover:shadow-md hover:border-blue-300 transition-all cursor-pointer">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-sm font-medium text-gray-900 line-clamp-2 hover:text-blue-600 transition-colors">
                        {{ $update->title }}
                    </h3>
                    <span class="text-xs text-gray-500 ml-2 flex-shrink-0">
                        {{ $update->created_at->format('d/m/Y') }}
                    </span>
                </div>
                
                @if($update->caption)
                    <p class="text-xs text-gray-600 mb-2 line-clamp-2">
                        {{ $update->caption }}
                    </p>
                @endif

                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $update->type === 'feature' ? 'bg-blue-100 text-blue-800' : 
                               ($update->type === 'bugfix' ? 'bg-red-100 text-red-800' : 
                               ($update->type === 'improvement' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                            {{ $update->type === 'feature' ? 'Nova Funcionalidade' : 
                               ($update->type === 'bugfix' ? 'Correção' : 
                               ($update->type === 'improvement' ? 'Melhoria' : ucfirst($update->type))) }}
                        </span>
                        
                        @if($update->version)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                v{{ $update->version }}
                            </span>
                        @endif
                    </div>
                    
                    @if($update->views > 0)
                        <div class="flex items-center text-xs text-gray-500">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            {{ $update->views }}
                        </div>
                    @endif
                </div>
            </a>
        @empty
            <div class="text-center py-8">
                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="mt-2 text-sm text-gray-500">Nenhuma atualização encontrada</p>
            </div>
        @endforelse
    </div>

    <!-- Footer with "Ver Mais" button -->
    <div class="mt-4 pt-3 border-t border-gray-200 bg-white">
        <a href="{{ route('public.customer.project', [$customer->hash, $project->hash]) }}" 
           target="_blank"
           class="w-full flex justify-center items-center px-4 py-2 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
            </svg>
            Ver Todas as Atualizações
        </a>
    </div>

    <!-- Powered by -->
    <div class="mt-3 text-center bg-white">
        <p class="text-xs text-gray-400">Powered by UPMANAGER</p>
    </div>
</div>

<style>
    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }
    
    /* Garante que o footer seja sempre visível */
    body {
        padding-bottom: 20px !important;
    }
</style>
@endsection 