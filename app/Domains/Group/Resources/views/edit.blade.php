@extends('shared::layouts.app')

@section('title', 'Editar ' . $group->name . ' - UPMANAGER')

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
                    <a href="{{ route('groups.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                        Grupos
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <a href="{{ route('groups.show', $group->id) }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                        {{ $group->name }}
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-4 text-sm font-medium text-gray-500">Editar</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Editar Grupo</h1>
            <p class="mt-1 text-sm text-gray-600">Atualize as informações do grupo</p>
        </div>
        
        <form action="{{ route('groups.update', $group->id) }}" method="POST" class="px-6 py-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Nome -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nome do Grupo <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name', $group->name) }}"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror"
                    placeholder="Digite o nome do grupo"
                    required
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descrição -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Descrição
                </label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="3"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 @enderror"
                    placeholder="Descreva o propósito do grupo"
                >{{ old('description', $group->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Cor -->
            <div>
                <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                    Cor do Grupo
                </label>
                <div class="flex items-center space-x-3">
                    <input 
                        type="color" 
                        name="color" 
                        id="color" 
                        value="{{ old('color', $group->color) }}"
                        class="h-10 w-20 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('color') border-red-300 @enderror"
                    >
                    <div class="flex-1">
                        <input 
                            type="text" 
                            id="color_hex" 
                            value="{{ old('color', $group->color) }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="#3B82F6"
                            readonly
                        >
                    </div>
                </div>
                <p class="mt-1 text-xs text-gray-500">Escolha uma cor para identificar o grupo visualmente</p>
                @error('color')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status
                </label>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <input 
                            type="radio" 
                            name="is_active" 
                            id="active" 
                            value="1" 
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                            {{ old('is_active', $group->is_active) == '1' ? 'checked' : '' }}
                        >
                        <label for="active" class="ml-2 text-sm text-gray-700">
                            Ativo
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input 
                            type="radio" 
                            name="is_active" 
                            id="inactive" 
                            value="0" 
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                            {{ old('is_active', $group->is_active) == '0' ? 'checked' : '' }}
                        >
                        <label for="inactive" class="ml-2 text-sm text-gray-700">
                            Inativo
                        </label>
                    </div>
                </div>
                @error('is_active')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('groups.show', $group->id) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Cancelar
                </a>
                
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Sincronizar color picker com input de texto
    document.getElementById('color').addEventListener('input', function() {
        document.getElementById('color_hex').value = this.value;
        document.getElementById('color').setAttribute('name', 'color');
    });
    
    document.getElementById('color_hex').addEventListener('input', function() {
        const value = this.value;
        if (/^#[0-9A-F]{6}$/i.test(value)) {
            document.getElementById('color').value = value;
        }
    });
</script>
@endsection 