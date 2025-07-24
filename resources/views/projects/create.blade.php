@extends('layouts.app')

@section('title', 'Novo Projeto - UPMANAGER')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                    <span class="ml-4 text-sm font-medium text-gray-500">Novo Projeto</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Criar Novo Projeto</h1>
            <p class="mt-1 text-sm text-gray-600">Preencha as informações do projeto</p>
        </div>
        
        <form action="{{ route('projects.store') }}" method="POST" class="px-6 py-6 space-y-6">
            @csrf
            
            <!-- Nome -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nome do Projeto <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror"
                    placeholder="Digite o nome do projeto"
                    required
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select 
                    name="status" 
                    id="status" 
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-300 @enderror"
                    required
                >
                    <option value="">Selecione o status</option>
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Ativo</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inativo</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Clientes -->
            <div>
                <label for="customer_ids" class="block text-sm font-medium text-gray-700 mb-2">
                    Clientes Associados
                </label>
                <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3">
                    @forelse($customers as $customer)
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="customer_ids[]" 
                                value="{{ $customer->id }}" 
                                id="customer_{{ $customer->id }}"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                {{ in_array($customer->id, old('customer_ids', [])) ? 'checked' : '' }}
                            >
                            <label for="customer_{{ $customer->id }}" class="ml-2 text-sm text-gray-700">
                                {{ $customer->name }}
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ml-2
                                    {{ $customer->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $customer->status === 'active' ? 'Ativo' : 'Inativo' }}
                                </span>
                            </label>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Nenhum cliente disponível</p>
                    @endforelse
                </div>
                @error('customer_ids')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('customer_ids.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Hash (opcional - será gerado automaticamente se não fornecido) -->
            <div>
                <label for="hash" class="block text-sm font-medium text-gray-700 mb-2">
                    Hash do Projeto <span class="text-gray-400">(opcional)</span>
                </label>
                <input 
                    type="text" 
                    name="hash" 
                    id="hash" 
                    value="{{ old('hash') }}"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('hash') border-red-300 @enderror"
                    placeholder="Deixe em branco para gerar automaticamente"
                >
                <p class="mt-1 text-xs text-gray-500">Se não fornecido, um hash único será gerado automaticamente</p>
                @error('hash')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('projects.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Cancelar
                </a>
                
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Criar Projeto
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 