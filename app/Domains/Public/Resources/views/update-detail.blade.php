@extends('shared::layouts.public')

@section('title', $update->title . ' - UPMANAGER')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
            <li>
                <div>
                    <a href="{{ route('public.project', $update->project->hash) }}" class="text-gray-400 hover:text-gray-500">
                        {{ $update->project->name }}
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-4 text-sm font-medium text-gray-500">{{ $update->title }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Update Header -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-8">
        <div class="px-6 py-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $update->title }}</h1>
                    
                    @if($update->caption)
                        <p class="text-xl text-gray-600 mb-4">{{ $update->caption }}</p>
                    @endif
                    
                    <div class="flex items-center space-x-6 text-sm text-gray-500">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Publicado em {{ $update->created_at->format('d/m/Y') }} às {{ $update->created_at->format('H:i') }}
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            {{ $update->views }} {{ $update->views === 1 ? 'visualização' : 'visualizações' }}
                        </span>
                        @if($update->customer)
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Cliente: {{ $update->customer->name }}
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="ml-6 flex-shrink-0">
                    <a href="{{ route('public.project', $update->project->hash) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                        </svg>
                        Ver todas as atualizações
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-medium text-blue-900">Projeto: {{ $update->project->name }}</h3>
                <div class="mt-1 flex items-center space-x-4">
                    @if($update->project->group)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $update->project->group->name }}
                        </span>
                    @endif
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ ucfirst($update->project->status) }}
                    </span>
                </div>
            </div>
            <div class="text-right">
                @if($update->project->customers->count() > 0)
                    <div class="text-xs text-blue-700 mb-1">Clientes:</div>
                    <div class="flex flex-wrap gap-1 justify-end">
                        @foreach($update->project->customers as $customer)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $customer->name }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Update Content -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-6">
            @if($update->description)
                <div class="prose prose-lg max-w-none">
                    {!! $update->description !!}
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="mt-2">Nenhum conteúdo adicional disponível para esta atualização.</p>
                </div>
            @endif
        </div>
    </div>


</div>



<style>
/* Estilos para o conteúdo rich text */
.prose {
    color: #374151;
    line-height: 1.75;
}

.prose img {
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    margin: 1.5rem 0;
}

.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
    color: #111827;
    font-weight: 600;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.prose h1 { font-size: 1.875rem; }
.prose h2 { font-size: 1.5rem; }
.prose h3 { font-size: 1.25rem; }

.prose p {
    margin-bottom: 1.25rem;
}

.prose ul, .prose ol {
    margin: 1.25rem 0;
    padding-left: 1.625rem;
}

.prose li {
    margin: 0.5rem 0;
}

.prose blockquote {
    border-left: 4px solid #e5e7eb;
    padding-left: 1rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #6b7280;
}

.prose code {
    background-color: #f3f4f6;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.875em;
}

.prose pre {
    background-color: #1f2937;
    color: #f9fafb;
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 1.5rem 0;
}

.prose table {
    width: 100%;
    border-collapse: collapse;
    margin: 1.5rem 0;
}

.prose th, .prose td {
    border: 1px solid #e5e7eb;
    padding: 0.75rem;
    text-align: left;
}

.prose th {
    background-color: #f9fafb;
    font-weight: 600;
}
</style>
@endsection 