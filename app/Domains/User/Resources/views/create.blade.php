@extends('shared::layouts.app')

@section('title', 'Novo Usuário - UPMANAGER')

@section('content')
<div class="max-w-2xl mx-auto">
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
                    <a href="{{ route('users.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                        Usuários
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-4 text-sm font-medium text-gray-500">Novo Usuário</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Criar Novo Usuário</h2>
            <p class="mt-1 text-sm text-gray-600">Preencha as informações do usuário abaixo.</p>
        </div>

        <form method="POST" action="{{ route('users.store') }}" class="p-6 space-y-6" novalidate>
            @csrf

            <!-- Nome -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nome Completo</label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror" 
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       value="{{ old('email') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-300 @enderror" 
                       required>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Telefone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Telefone</label>
                <input type="text" 
                       name="phone" 
                       id="phone" 
                       value="{{ old('phone') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-300 @enderror"
                       placeholder="(11) 99999-9999">
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Posição/Cargo -->
            <div>
                <label for="position" class="block text-sm font-medium text-gray-700">Cargo/Posição</label>
                <input type="text" 
                       name="position" 
                       id="position" 
                       value="{{ old('position') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('position') border-red-300 @enderror"
                       placeholder="Ex: Desenvolvedor, Gerente, etc.">
                @error('position')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Nível de Acesso</label>
                <select name="role" 
                        id="role" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-300 @enderror" 
                        required>
                    <option value="">Selecione o nível de acesso</option>
                    <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Usuário</option>
                    <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Gerente</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">
                    <strong>Usuário:</strong> Acesso básico ao sistema<br>
                    <strong>Gerente:</strong> Acesso de gerenciamento<br>
                    <strong>Administrador:</strong> Acesso total ao sistema
                </p>
            </div>

            <!-- Senha -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-300 @enderror" 
                           required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Senha</label>
                    <input type="password" 
                           name="password_confirmation" 
                           id="password_confirmation" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                           required>
                </div>
            </div>

            <!-- Status Ativo -->
            <div class="flex items-center">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" 
                       name="is_active" 
                       id="is_active" 
                       value="1"
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                       {{ old('is_active', true) ? 'checked' : '' }}>
                <label for="is_active" class="ml-2 block text-sm text-gray-700">
                    Usuário ativo
                </label>
            </div>
            <p class="text-xs text-gray-500">Usuários inativos não podem fazer login no sistema.</p>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('users.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Criar Usuário
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 