@extends('shared::layouts.app')

@section('title', 'Dashboard - UPMANAGER')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
    <p class="mt-2 text-sm text-gray-600">Visão geral do sistema</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Customers -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total de Clientes</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['customers'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Projects -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total de Projetos</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['projects'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Updates -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total de Atualizações</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['updates'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Customers -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Clientes Ativos</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['active_customers'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Updates -->
<div class="bg-white shadow-sm rounded-lg border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Atualizações Recentes</h2>
    </div>
    <div class="p-6">
        @if(isset($recentUpdates) && $recentUpdates->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($recentUpdates as $update)
                <li class="py-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                            <div>
                                <p class="text-sm text-gray-500">
                                    <a href="{{ route('updates.show', $update->id) }}" class="font-medium text-gray-900 hover:text-blue-600">{{ $update->title }}</a>
                                </p>
                                <p class="text-sm text-gray-500">{{ $update->project->name }}</p>
                            </div>
                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                <time datetime="{{ $update->created_at->format('Y-m-d') }}">{{ $update->created_at->format('d/m/Y') }}</time>
                                <div class="text-xs">{{ $update->views }} visualizações</div>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        @else
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma atualização encontrada</h3>
            <p class="mt-1 text-sm text-gray-500">Comece criando sua primeira atualização.</p>
            <div class="mt-6">
                <a href="{{ route('updates.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nova Atualização
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
    <a href="{{ route('customers.create') }}" 
       class="group relative bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div>
            <span class="rounded-lg inline-flex p-3 bg-blue-50 text-blue-700 ring-4 ring-white">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
            </span>
        </div>
        <div class="mt-8">
            <h3 class="text-lg font-medium">
                <span class="absolute inset-0" aria-hidden="true"></span>
                Novo Cliente
            </h3>
            <p class="mt-2 text-sm text-gray-500">
                Adicione um novo cliente ao sistema
            </p>
        </div>
        <span class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-gray-400" aria-hidden="true">
            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z"/>
            </svg>
        </span>
    </a>

    <a href="{{ route('projects.create') }}" 
       class="group relative bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-green-500 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div>
            <span class="rounded-lg inline-flex p-3 bg-green-50 text-green-700 ring-4 ring-white">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </span>
        </div>
        <div class="mt-8">
            <h3 class="text-lg font-medium">
                <span class="absolute inset-0" aria-hidden="true"></span>
                Novo Projeto
            </h3>
            <p class="mt-2 text-sm text-gray-500">
                Crie um novo projeto para seus clientes
            </p>
        </div>
        <span class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-gray-400" aria-hidden="true">
            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z"/>
            </svg>
        </span>
    </a>

    <a href="{{ route('updates.create') }}" 
       class="group relative bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-yellow-500 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div>
            <span class="rounded-lg inline-flex p-3 bg-yellow-50 text-yellow-700 ring-4 ring-white">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </span>
        </div>
        <div class="mt-8">
            <h3 class="text-lg font-medium">
                <span class="absolute inset-0" aria-hidden="true"></span>
                Nova Atualização
            </h3>
            <p class="mt-2 text-sm text-gray-500">
                Publique uma nova atualização para seus projetos
            </p>
        </div>
        <span class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-gray-400" aria-hidden="true">
            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z"/>
            </svg>
        </span>
    </a>
</div>
@endsection
