@extends('shared::layouts.app')

@section('title', 'Editar ' . $update->title . ' - UPMANAGER')

@section('head')
    <!-- Summernote Lite CSS (sem dependência do Bootstrap) -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    
    <style>
        /* Estilos customizados para o Summernote */
        .note-editor {
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
        }
        .note-toolbar {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e2e8f0;
        }
        .note-editing-area {
            min-height: 300px;
        }
        .note-editing-area img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .note-editing-area table {
            border-collapse: collapse;
            width: 100%;
            margin: 15px 0;
        }
        .note-editing-area table td, 
        .note-editing-area table th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .note-editing-area table th {
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

            <!-- Cliente -->
            <div>
                <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Cliente <span class="text-red-500">*</span>
                </label>
                <select 
                    name="customer_id" 
                    id="customer_id" 
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('customer_id') border-red-300 @enderror"
                    required
                >
                    <option value="">Selecione um cliente</option>
                    @foreach($customers as $customer)
                        <option 
                            value="{{ $customer->id }}" 
                            {{ old('customer_id', $update->customer_id) == $customer->id ? 'selected' : '' }}
                            data-customer-id="{{ $customer->id }}"
                        >
                            {{ $customer->name }}
                            ({{ $customer->status === 'active' ? 'Ativo' : 'Inativo' }})
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">
                    Selecione o cliente para quem esta atualização é direcionada. 
                    <strong>Nota:</strong> Para alterar para múltiplos clientes, considere criar uma nova atualização.
                </p>
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

            <!-- Descrição -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Descrição
                </label>
                <!-- Editor Controls -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">
                        <strong>Summernote Editor:</strong> Use a toolbar para formatar texto, inserir tabelas, listas, imagens e vídeos. 
                        Clique em <strong>"Code View"</strong> para alternar entre visual e código HTML.
                    </small>
                </div>
                
                <!-- Summernote Editor -->
                <div id="editor-container">
                    <textarea id="summernote-editor" name="description_editor">{{ old('description', $update->description) }}</textarea>
                </div>
                
                <input type="hidden" name="description" id="description" value="{{ old('description', $update->description) }}">
                @error('description')
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

@endsection

@section('scripts')
<!-- jQuery (necessário) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote Lite JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<!-- Summernote PT-BR -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/lang/summernote-pt-BR.min.js"></script>

<script>
function updateCustomerOptions() {
    const projectSelect = document.getElementById('project_id');
    const customerSelect = document.getElementById('customer_id');
    const selectedOption = projectSelect.options[projectSelect.selectedIndex];
    
    Array.from(customerSelect.options).forEach(option => {
        if (option.value === '') return;
        
        const customerId = option.dataset.customerId;
        
        if (selectedOption.value === '') {
            option.disabled = false;
            option.style.display = 'block';
        } else {
            const projectCustomers = selectedOption.dataset.customers.split(',').filter(id => id !== '');
            
            if (projectCustomers.includes(customerId)) {
                option.disabled = false;
                option.style.display = 'block';
            } else {
                option.disabled = true;
                option.style.display = 'none';
            }
        }
    });
}



function initializeSummernote() {
    try {
        // Destruir instância anterior se existir
        if ($('#summernote-editor').hasClass('note-editor')) {
            $('#summernote-editor').summernote('destroy');
        }
        
        // Configuração ROBUSTA e TESTADA
        $('#summernote-editor').summernote({
            height: 300,
            minHeight: 200,
            maxHeight: 600,
            focus: false,
            lang: 'pt-BR',
            placeholder: 'Digite a descrição detalhada da atualização...',
            
            // Toolbar simplificada mas funcional
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            
            // Configurações adicionais para garantir funcionamento
            fontNames: [
                'Arial', 'Arial Black', 'Comic Sans MS', 'Courier New',
                'Helvetica Neue', 'Helvetica', 'Impact', 'Lucida Grande',
                'Tahoma', 'Times New Roman', 'Verdana'
            ],
            fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '24', '36'],
            
            // Configurações de tabela
            tableClassName: 'table table-bordered',
            
            // Callbacks
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
                },
                onBlur: function() {
                    // Garantir que o conteúdo seja salvo
                    const descriptionInput = document.getElementById('description');
                    if (descriptionInput) {
                        descriptionInput.value = $('#summernote-editor').summernote('code');
                    }
                }
            }
        });
        
    } catch (error) {
        console.error('Summernote initialization error:', error);
    }
    
    // Garantir salvamento no submit
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            const descriptionInput = document.getElementById('description');
            if (descriptionInput) {
                descriptionInput.value = $('#summernote-editor').summernote('code');
            }
        });
    }
    

}

// Inicialização quando DOM estiver pronto
$(document).ready(function() {
    // Aguardar todas as bibliotecas carregarem
    setTimeout(() => {
        initializeSummernote();
        
        // Inicializar outras funcionalidades
        updateCustomerOptions();
        
        const projectSelect = document.getElementById('project_id');
        if (projectSelect) {
            projectSelect.addEventListener('change', updateCustomerOptions);
        }
    }, 300);
});
</script>
@endsection 