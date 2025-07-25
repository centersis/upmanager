@extends('shared::layouts.app')

@section('title', 'Nova Atualização - UPMANAGER')

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
                    <span class="ml-4 text-sm font-medium text-gray-500">Nova</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Nova Atualização</h1>
            <p class="mt-1 text-sm text-gray-600">Registre uma nova atualização para um projeto</p>
        </div>
        
        <form action="{{ route('updates.store') }}" method="POST" class="px-6 py-6 space-y-6" onsubmit="return validateCustomerSelection()">
            @csrf
            
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
                                {{ old('project_id') == $project->id ? 'selected' : '' }}>
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
                            {{ old('is_global') == '1' ? 'checked' : '' }}
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
                            {{ old('is_global', '0') == '0' ? 'checked' : '' }}
                            onchange="toggleCustomerField()"
                        >
                        <label for="specific_update" class="ml-2 block text-sm text-gray-700">
                            <strong>Atualização Específica</strong>
                            <span class="block text-xs text-gray-500">Aplicável apenas a um cliente específico</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Clientes (condicional) -->
            <div id="customer_field" style="display: {{ old('is_global', '0') == '0' ? 'block' : 'none' }};">
                <label for="customer_ids" class="block text-sm font-medium text-gray-700 mb-2">
                    Clientes <span class="text-red-500">*</span>
                </label>
                <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3">
                    @foreach($customers as $customer)
                        <div class="flex items-center customer-option" data-customer-id="{{ $customer->id }}">
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
                    @endforeach
                </div>
                <p class="mt-1 text-xs text-gray-500">
                    <strong>Selecione um ou mais clientes.</strong> Uma atualização será criada para cada cliente selecionado.
                    Apenas clientes associados ao projeto selecionado estarão disponíveis.
                </p>
                @error('customer_ids')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('customer_ids.*')
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
                    value="{{ old('title') }}"
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
                    value="{{ old('caption') }}"
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
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Rascunho</option>
                    <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Publicado</option>
                    <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Arquivado</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>



            <!-- Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('updates.index') }}" 
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
                    Criar Atualização
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