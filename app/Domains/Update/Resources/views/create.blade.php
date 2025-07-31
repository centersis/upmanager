@extends('shared::layouts.app')

@section('title', __('updates.create') . ' - UPMANAGER')

@section('head')
    <!-- jQuery (Lite only depende do jQuery) -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    
    <style>
        .note-editor {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
        }
        .note-toolbar {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 8px;
        }
        .note-editing-area {
            min-height: 300px;
            padding: 12px;
        }
        .note-editor .note-editing-area img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .note-editor .note-editing-area video {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .note-editor .note-editing-area iframe {
            max-width: 100%;
            height: 315px;
            border-radius: 4px;
        }
        .note-editor .note-editing-area table {
            border-collapse: collapse;
            width: 100%;
            margin: 15px 0;
        }
        .note-editor .note-editing-area table td, 
        .note-editor .note-editing-area table th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .note-editor .note-editing-area table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

    </style>
@endsection

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
                    <a href="{{ route('updates.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                        {{ __('updates.title') }}
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-4 text-sm font-medium text-gray-500">{{ __('common.new') }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('updates.create') }}</h1>
        <p class="mt-1 text-sm text-gray-600">{{ __('updates.create_description') }}</p>
        </div>
        
        <form action="{{ route('updates.store') }}" method="POST" class="px-6 py-6 space-y-6" onsubmit="return validateCustomerSelection()">
            @csrf
            
            <!-- Projeto -->
            <div>
                <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('updates.project') }} <span class="text-red-500">*</span>
                </label>
                <select 
                    name="project_id" 
                    id="project_id" 
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('project_id') border-red-300 @enderror"
                    required
                    onchange="updateCustomerOptions()"
                >
                    <option value="">{{ __('updates.select_project') }}</option>
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

            <!-- Clientes -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('updates.customers') }} <span class="text-red-500">*</span>
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
                                    {{ $customer->status === 'active' ? __('customers.active') : __('customers.inactive') }}
                                </span>
                            </label>
                        </div>
                    @endforeach
                </div>
                <p class="mt-1 text-xs text-gray-500">
                    {{ __('updates.customers_help') }}
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
                    {{ __('updates.title_field') }} <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    value="{{ old('title') }}"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-300 @enderror"
                    placeholder="{{ __('updates.title_placeholder') }}"
                    required
                >
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Legenda -->
            <div>
                <label for="caption" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('updates.caption') }}
                </label>
                <input 
                    type="text" 
                    name="caption" 
                    id="caption" 
                    value="{{ old('caption') }}"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('caption') border-red-300 @enderror"
                    placeholder="{{ __('updates.caption_placeholder') }}"
                >
                @error('caption')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descrição -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('updates.description') }}
                </label>
                
                
                <!-- Summernote Editor -->
                <div id="editor-container">
                    <textarea id="summernote-editor" name="description_editor">{{ old('description') }}</textarea>
                </div>
                
                <input type="hidden" name="description" id="description" value="{{ old('description') }}">
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('updates.status') }} <span class="text-red-500">*</span>
                </label>
                <select 
                    name="status" 
                    id="status" 
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-300 @enderror"
                    required
                >
                    <option value="">{{ __('updates.select_status') }}</option>
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>{{ __('updates.draft') }}</option>
                    <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>{{ __('updates.published') }}</option>
                    <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>{{ __('updates.archived') }}</option>
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
                    {{ __('common.cancel') }}
                </a>
                
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('updates.create_button') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<!-- jQuery (necessário) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote Lite JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<!-- Summernote PT-BR -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/lang/summernote-pt-BR.min.js"></script>

<script>
// Função para limpar editor anterior
function cleanupEditor() {
    try {
        if ($('#summernote-editor').hasClass('note-editor')) {
            $('#summernote-editor').summernote('destroy');
        }
    } catch (error) {
        console.log('Erro ao limpar editor:', error.message);
    }
}

// Configuração básica e robusta
function initializeSummernoteRobust() {
    try {
        $('#summernote-editor').summernote({
            height: 300,
            minHeight: 200,
            maxHeight: 600,
            focus: false,
            lang: 'pt-BR',
                            placeholder: '{{ __('updates.description_placeholder') }}',
            
            // Toolbar mínima para garantir funcionamento
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            
            // Configurações básicas
            fontNames: ['Arial', 'Helvetica', 'Times', 'Courier'],
            fontSizes: ['8', '10', '12', '14', '16', '18', '24', '36'],
            
            callbacks: {
                onImageUpload: function(files) {
                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $('#summernote-editor').summernote('insertImage', e.target.result);
                        };
                        reader.readAsDataURL(file);
                    }
                },
                
                onChange: function(contents, $editable) {
                    const descriptionInput = document.getElementById('description');
                    if (descriptionInput) {
                        descriptionInput.value = contents;
                    }
                }
            },
            
            // Configuração de vídeo
            videoAttributes: {
                'controls': '',
                'class': 'video-fluid',
                'style': 'max-width: 100%; height: auto;'
            }
        });
        
    } catch (error) {
        console.log('Erro na inicialização do Summernote:', error.message);
    }
}

// Funções auxiliares (simplified)
function updateCustomerOptions() {
    const projectSelect = document.getElementById('project_id');
    const customerOptions = document.querySelectorAll('.customer-option');
    const selectedOption = projectSelect.options[projectSelect.selectedIndex];
    
    if (selectedOption.value === '') {
        customerOptions.forEach(option => option.style.display = 'flex');
        return;
    }
    
    const projectCustomers = selectedOption.dataset.customers.split(',').filter(id => id !== '');
    
    customerOptions.forEach(option => {
        const customerId = option.dataset.customerId;
        if (projectCustomers.includes(customerId)) {
            option.style.display = 'flex';
        } else {
            option.style.display = 'none';
            const checkbox = option.querySelector('input[type="checkbox"]');
            if (checkbox) checkbox.checked = false;
        }
    });
}

function validateCustomerSelection() {
    const visibleCustomers = document.querySelectorAll('.customer-option[style*="flex"] input[name="customer_ids[]"]:checked');
    if (visibleCustomers.length === 0) {
        alert('Por favor, selecione pelo menos um cliente para criar a atualização.');
        return false;
    }
    return true;
}

// Inicialização principal
$(document).ready(function() {
    // Aguardar um pouco para garantir que todas as libs carregaram
    setTimeout(() => {
        // Limpar qualquer instância anterior
        cleanupEditor();
        
        // Inicializar Summernote
        initializeSummernoteRobust();
        
        // Inicializar outras funcionalidades
        updateCustomerOptions();
        
        const projectSelect = document.getElementById('project_id');
        if (projectSelect) {
            projectSelect.addEventListener('change', updateCustomerOptions);
        }
        
        // Form validation
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!validateCustomerSelection()) {
                    e.preventDefault();
                    return;
                }
                
                // Garantir que conteúdo seja salvo
                const descriptionInput = document.getElementById('description');
                if (descriptionInput) {
                    descriptionInput.value = $('#summernote-editor').summernote('code');
                }
            });
        }
        
    }, 500); // Aguardar 500ms para garantir carregamento
});


</script>
@endsection 