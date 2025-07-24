@extends('layouts.app')

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
                        <span class="text-sm text-gray-600">
                            Criado em {{ $project->created_at->format('d/m/Y') }}
                        </span>
                    </div>
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
                    @forelse($project->updates->sortByDesc('created_at') as $update)
                        <div class="relative pb-8 {{ !$loop->last ? 'border-l-2 border-gray-200 ml-4' : '' }}">
                            <div class="relative flex items-start space-x-3">
                                <div class="relative">
                                    <div class="h-8 w-8 bg-blue-500 rounded-full flex items-center justify-center ring-8 ring-white">
                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    @if(!$loop->last)
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
                                        <p class="mt-1">{{ Str::limit($update->description, 150) }}</p>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 