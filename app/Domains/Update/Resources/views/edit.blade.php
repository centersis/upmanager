@extends('shared::layouts.app')

@section('title', 'Editar ' . $update->title . ' - UPMANAGER')

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
                    <a href="{{ route('updates.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                        Atualizações
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <a href="{{ route('updates.show', $update->id) }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                        {{ Str::limit($update->title, 30) }}
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
            <h1 class="text-2xl font-bold text-gray-900">Editar Atualização</h1>
            <p class="mt-1 text-sm text-gray-600">Atualize as informações da atualização</p>
        </div>
        
        <form action="{{ route('updates.update', $update->id) }}" method="POST" class="px-6 py-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Projeto -->
            <div>
                <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Projeto <span class="text-red-500">*</span>
                </label>
                <select 
                    name="project_id" 
                    id="project_id" 
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('project_id') border-red-300 @enderror"
                    required
                    onchange="updateCustomerOptions()"
                >
                    <option value="">Selecione um projeto</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" 
                                data-customers="{{ $project->customers->pluck('id')->join(',') }}"
                                {{ old('project_id', $update->project_id) == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                            @if($project->group)
                                ({{ $project->group->name }})
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('project_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tipo de Atualização -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Tipo de Atualização
                </label>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input 
                            type="radio" 
                            name="is_global" 
                            value="1" 
                            id="global_update"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                            {{ old('is_global', $update->is_global ? '1' : '0') == '1' ? 'checked' : '' }}
                            onchange="toggleCustomerField()"
                        >
                        <label for="global_update" class="ml-2 block text-sm text-gray-700">
                            <strong>Atualização Global</strong>
                            <span class="block text-xs text-gray-500">Aplicável a todos os clientes do projeto</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input 
                            type="radio" 
                            name="is_global" 
                            value="0" 
                            id="specific_update"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                            {{ old('is_global', $update->is_global ? '1' : '0') == '0' ? 'checked' : '' }}
                            onchange="toggleCustomerField()"
                        >
                        <label for="specific_update" class="ml-2 block text-sm text-gray-700">
                            <strong>Atualização Específica</strong>
                            <span class="block text-xs text-gray-500">Aplicável apenas a um cliente específico</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Cliente (condicional) -->
            <div id="customer_field" style="display: {{ old('is_global', $update->is_global ? '1' : '0') == '0' ? 'block' : 'none' }};">
                <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Cliente <span class="text-red-500">*</span>
                </label>
                <select 
                    name="customer_id" 
                    id="customer_id" 
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('customer_id') border-red-300 @enderror"
                >
                    <option value="">Selecione um cliente</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id', $update->customer_id) == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">Apenas clientes associados ao projeto selecionado estarão disponíveis</p>
                @error('customer_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Título -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Título <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    value="{{ old('title', $update->title) }}"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-300 @enderror"
                    placeholder="Título da atualização"
                    required
                >
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Legenda -->
            <div>
                <label for="caption" class="block text-sm font-medium text-gray-700 mb-2">
                    Legenda
                </label>
                <input 
                    type="text" 
                    name="caption" 
                    id="caption" 
                    value="{{ old('caption', $update->caption) }}"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('caption') border-red-300 @enderror"
                    placeholder="Breve descrição da atualização"
                >
                @error('caption')
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
                    <option value="draft" {{ old('status', $update->status) === 'draft' ? 'selected' : '' }}>Rascunho</option>
                    <option value="published" {{ old('status', $update->status) === 'published' ? 'selected' : '' }}>Publicado</option>
                    <option value="archived" {{ old('status', $update->status) === 'archived' ? 'selected' : '' }}>Arquivado</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>



            <!-- Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('updates.show', $update->id) }}" 
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
function toggleCustomerField() {
    const isGlobal = document.getElementById('global_update').checked;
    const customerField = document.getElementById('customer_field');
    const customerCheckboxes = document.querySelectorAll('input[name="customer_ids[]"]');
    
    if (isGlobal) {
        customerField.style.display = 'none';
        // Uncheck all customer checkboxes and remove required
        customerCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
            checkbox.removeAttribute('required');
        });
    } else {
        customerField.style.display = 'block';
        // Don't set individual checkboxes as required, we'll validate manually
        customerCheckboxes.forEach(checkbox => {
            checkbox.removeAttribute('required');
        });
    }
}

function updateCustomerOptions() {
    const projectSelect = document.getElementById('project_id');
    const customerOptions = document.querySelectorAll('.customer-option');
    const selectedOption = projectSelect.options[projectSelect.selectedIndex];
    
    if (selectedOption.value === '') {
        // Show all customer options
        customerOptions.forEach(option => {
            option.style.display = 'flex';
        });
        return;
    }
    
    const projectCustomers = selectedOption.dataset.customers.split(',').filter(id => id !== '');
    
    // Show/hide customer options based on project selection
    customerOptions.forEach(option => {
        const customerId = option.dataset.customerId;
        
        if (projectCustomers.includes(customerId)) {
            option.style.display = 'flex';
        } else {
            option.style.display = 'none';
            // Uncheck hidden options
            const checkbox = option.querySelector('input[type="checkbox"]');
            if (checkbox) {
                checkbox.checked = false;
            }
        }
    });
}

// Add validation for at least one customer selected
function validateCustomerSelection() {
    const isGlobal = document.getElementById('global_update').checked;
    
    // If it's global, no need to validate customers
    if (isGlobal) return true;
    
    // Check if at least one customer is selected and visible
    const visibleCustomers = document.querySelectorAll('.customer-option[style*="flex"] input[name="customer_ids[]"]:checked');
    
    if (visibleCustomers.length === 0) {
        // Find the first visible customer option to focus
        const firstVisibleCustomer = document.querySelector('.customer-option[style*="flex"] input[name="customer_ids[]"]');
        if (firstVisibleCustomer) {
            firstVisibleCustomer.focus();
            // Add visual feedback
            firstVisibleCustomer.parentElement.parentElement.style.border = '2px solid #ef4444';
            setTimeout(() => {
                firstVisibleCustomer.parentElement.parentElement.style.border = '';
            }, 3000);
        }
        
        alert('Por favor, selecione pelo menos um cliente para a atualização específica.');
        return false;
    }
    
    return true;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleCustomerField();
    updateCustomerOptions();
    

});
</script>
@endsection 