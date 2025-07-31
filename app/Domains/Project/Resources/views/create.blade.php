@extends('shared::layouts.app')

@section('title', __('projects.create') . ' - UPMANAGER')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
            <li>
                <div>
                    <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-500">
                        {{ __('dashboard.title') }}
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <a href="{{ route('projects.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                        {{ __('projects.title') }}
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-4 text-sm font-medium text-gray-500">{{ __('projects.create') }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('projects.create_title') }}</h1>
            <p class="mt-1 text-sm text-gray-600">{{ __('projects.fill_info') }}</p>
        </div>
        
        <form action="{{ route('projects.store') }}" method="POST" class="px-6 py-6 space-y-6">
            @csrf
            
            <!-- Nome -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('projects.name') }} <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror"
                    placeholder="{{ __('projects.name_placeholder') }}"
                    required
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grupo -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="group_id" class="block text-sm font-medium text-gray-700">
                        {{ __('projects.group') }}
                    </label>
                    <div class="flex items-center space-x-2">
                        <button type="button" 
                                onclick="openNewGroupModal()"
                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('projects.new_group') }}
                        </button>
                        <a href="{{ route('groups.index') }}" 
                           class="text-xs text-blue-600 hover:text-blue-700 underline"
                           target="_blank">
                            {{ __('projects.manage_groups') }}
                        </a>
                    </div>
                </div>
                <select 
                    name="group_id" 
                    id="group_id" 
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('group_id') border-red-300 @enderror"
                >
                    <option value="">{{ __('projects.select_group') }}</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                            {{ $group->name }}
                            @if($group->description)
                                - {{ $group->description }}
                            @endif
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">{{ __('projects.groups_help') }}</p>
                @error('group_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('projects.status') }} <span class="text-red-500">*</span>
                </label>
                <select 
                    name="status" 
                    id="status" 
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-300 @enderror"
                    required
                >
                    <option value="">{{ __('projects.select_status') }}</option>
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>{{ __('projects.active') }}</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>{{ __('projects.inactive') }}</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Clientes -->
            <div>
                <label for="customer_ids" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('projects.associated_customers') }}
                </label>
                <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3">
                    @forelse($customers as $customer)
                        <div class="flex items-center justify-between">
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
                            
                            <!-- Indicador de projetos existentes -->
                            @php
                                $projectsCount = $customer->projects->count();
                            @endphp
                            @if($projectsCount > 0)
                                <div class="text-xs text-gray-500">
                                    {{ $projectsCount }} {{ $projectsCount === 1 ? 'projeto existente' : 'projetos existentes' }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Nenhum cliente disponível</p>
                    @endforelse
                </div>
                <p class="mt-1 text-xs text-gray-500">
                    {{ __('projects.customers_help') }}
                </p>
                @error('customer_ids')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('customer_ids.*')
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
                    {{ __('common.cancel') }}
                </a>
                
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('projects.create_button') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para Novo Grupo -->
<div id="newGroupModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center p-4 hidden z-50">
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between p-5 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">{{ __('groups.create_title') }}</h3>
            <button type="button" onclick="closeNewGroupModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Body -->
        <div class="p-5">
            <form id="newGroupForm" onsubmit="createGroup(event)">
                @csrf
                
                <!-- Nome do Grupo -->
                <div class="mb-4">
                    <label for="modal_group_name" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('groups.name') }} <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="modal_group_name" 
                        name="name" 
                        required
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder="{{ __('groups.name_placeholder') }}"
                    >
                    <div id="modal_name_error" class="mt-1 text-sm text-red-600 hidden"></div>
                </div>

                <!-- Descrição -->
                <div class="mb-4">
                    <label for="modal_group_description" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('groups.description') }} <span class="text-gray-400">({{ __('common.optional') }})</span>
                    </label>
                    <textarea 
                        id="modal_group_description" 
                        name="description" 
                        rows="2"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 resize-none"
                        placeholder="{{ __('groups.description_placeholder') }}"
                    ></textarea>
                </div>

                <!-- Cor -->
                <div class="mb-6">
                    <label for="modal_group_color" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('groups.color') }}
                    </label>
                    <div class="flex items-center space-x-3">
                        <input 
                            type="color" 
                            id="modal_group_color" 
                            name="color" 
                            value="#3B82F6"
                            class="h-9 w-16 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 cursor-pointer"
                        >
                        <div class="flex-1">
                            <input 
                                type="text" 
                                id="modal_group_color_hex" 
                                value="#3B82F6"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                                placeholder="#3B82F6"
                                readonly
                            >
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="flex items-center justify-end space-x-3 px-5 py-4 border-t border-gray-200 bg-gray-50">
            <button type="button" 
                    onclick="closeNewGroupModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                {{ __('common.cancel') }}
            </button>
            <button type="submit" 
                    form="newGroupForm"
                    id="createGroupBtn"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <span id="createGroupBtnText">{{ __('groups.create_button') }}</span>
                <svg id="createGroupBtnSpinner" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white hidden" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
function openNewGroupModal() {
    document.getElementById('newGroupModal').classList.remove('hidden');
    document.getElementById('modal_group_name').focus();
}

function closeNewGroupModal() {
    document.getElementById('newGroupModal').classList.add('hidden');
    // Limpar o formulário
    document.getElementById('newGroupForm').reset();
    document.getElementById('modal_group_color').value = '#3B82F6';
    document.getElementById('modal_group_color_hex').value = '#3B82F6';
    // Limpar erros
    document.getElementById('modal_name_error').classList.add('hidden');
}

// Sincronizar color picker com input de texto no modal
document.getElementById('modal_group_color').addEventListener('input', function() {
    document.getElementById('modal_group_color_hex').value = this.value;
});

document.getElementById('modal_group_color_hex').addEventListener('input', function() {
    const value = this.value;
    if (/^#[0-9A-F]{6}$/i.test(value)) {
        document.getElementById('modal_group_color').value = value;
    }
});

async function createGroup(event) {
    event.preventDefault();
    
    const btn = document.getElementById('createGroupBtn');
    const btnText = document.getElementById('createGroupBtnText');
    const btnSpinner = document.getElementById('createGroupBtnSpinner');
    const nameError = document.getElementById('modal_name_error');
    
    // Mostrar loading
    btn.disabled = true;
    btnText.textContent = 'Criando...';
    btnSpinner.classList.remove('hidden');
    nameError.classList.add('hidden');
    
    try {
        const formData = new FormData(event.target);
        const data = {
            name: formData.get('name'),
            description: formData.get('description'),
            color: formData.get('color'),
            is_active: true
        };
        
        const response = await fetch('/api/groups', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            const group = await response.json();
            
            // Adicionar nova opção ao select
            const select = document.getElementById('group_id');
            const option = new Option(
                group.description ? `${group.name} - ${group.description}` : group.name, 
                group.id, 
                false, 
                true
            );
            select.add(option);
            
            // Fechar modal
            closeNewGroupModal();
            
            // Mostrar mensagem de sucesso
            showNotification('Grupo criado com sucesso!', 'success');
            
        } else if (response.status === 422) {
            const errors = await response.json();
            if (errors.errors && errors.errors.name) {
                nameError.textContent = errors.errors.name[0];
                nameError.classList.remove('hidden');
            }
        } else {
            throw new Error('Erro ao criar grupo');
        }
        
    } catch (error) {
        console.error('Erro:', error);
        showNotification('Erro ao criar grupo. Tente novamente.', 'error');
    } finally {
        // Restaurar botão
        btn.disabled = false;
        btnText.textContent = 'Criar Grupo';
        btnSpinner.classList.add('hidden');
    }
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg ${
        type === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                ${type === 'success' 
                    ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />'
                    : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />'
                }
            </svg>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Remover após 5 segundos
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Prevenir fechar modal ao clicar fora (removido intencionalmente)
// O modal só pode ser fechado pelos botões ou ESC

// Fechar modal com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeNewGroupModal();
    }
});
</script>

@endsection 