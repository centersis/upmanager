@extends('shared::layouts.app')

@section('title', $update->title . ' - UPMANAGER')

@section('head')
    <style>
        /* Content display styles - Complete Summernote compatibility */
        .content-display {
            line-height: 1.6;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* Text formatting - inline styles */
        .content-display strong,
        .content-display b {
            font-weight: bold;
        }
        
        .content-display em,
        .content-display i {
            font-style: italic;
        }
        
        .content-display u {
            text-decoration: underline;
        }
        
        .content-display s,
        .content-display strike,
        .content-display del {
            text-decoration: line-through;
        }
        
        .content-display sub {
            vertical-align: sub;
            font-size: smaller;
        }
        
        .content-display sup {
            vertical-align: super;
            font-size: smaller;
        }

        /* Text alignment */
        .content-display [style*="text-align: left"],
        .content-display .text-left {
            text-align: left !important;
        }
        
        .content-display [style*="text-align: center"],
        .content-display .text-center {
            text-align: center !important;
        }
        
        .content-display [style*="text-align: right"],
        .content-display .text-right {
            text-align: right !important;
        }
        
        .content-display [style*="text-align: justify"],
        .content-display .text-justify {
            text-align: justify !important;
        }

        /* Headings */
        .content-display h1, 
        .content-display h2, 
        .content-display h3, 
        .content-display h4, 
        .content-display h5, 
        .content-display h6 {
            margin-top: 24px;
            margin-bottom: 12px;
            font-weight: bold;
            line-height: 1.3;
        }
        
        .content-display h1 { font-size: 2rem; }
        .content-display h2 { font-size: 1.75rem; }
        .content-display h3 { font-size: 1.5rem; }
        .content-display h4 { font-size: 1.25rem; }
        .content-display h5 { font-size: 1.125rem; }
        .content-display h6 { font-size: 1rem; }

        /* Paragraphs */
        .content-display p {
            margin: 12px 0;
            line-height: 1.6;
        }
        
        .content-display p:first-child {
            margin-top: 0;
        }
        
        .content-display p:last-child {
            margin-bottom: 0;
        }

        /* Lists */
        .content-display ul, 
        .content-display ol {
            margin: 12px 0;
            padding-left: 24px;
        }
        
        .content-display ul {
            list-style-type: disc;
        }
        
        .content-display ol {
            list-style-type: decimal;
        }
        
        .content-display ul ul {
            list-style-type: circle;
            margin-top: 4px;
            margin-bottom: 4px;
        }
        
        .content-display ul ul ul {
            list-style-type: square;
        }
        
        .content-display ol ol {
            list-style-type: lower-alpha;
            margin-top: 4px;
            margin-bottom: 4px;
        }
        
        .content-display ol ol ol {
            list-style-type: lower-roman;
        }
        
        .content-display li {
            margin: 4px 0;
            line-height: 1.6;
        }

        /* Links */
        .content-display a {
            color: #3b82f6;
            text-decoration: underline;
            transition: color 0.2s;
        }
        
        .content-display a:hover {
            color: #2563eb;
        }

        /* Images */
        .content-display img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin: 10px 0;
            display: inline-block;
        }

        /* Videos */
        .content-display video {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin: 10px 0;
            display: block;
        }

        /* iFrames (for embedded content) */
        .content-display iframe {
            max-width: 100%;
            height: 315px;
            border-radius: 8px;
            margin: 10px 0;
            border: none;
        }

        /* Tables */
        .content-display table {
            border-collapse: collapse;
            width: 100%;
            margin: 15px 0;
            border: 1px solid #ddd;
            background-color: white;
        }
        
        .content-display table td, 
        .content-display table th {
            border: 1px solid #ddd;
            padding: 8px 12px;
            text-align: left;
            vertical-align: top;
        }
        
        .content-display table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        /* Preserve inline table styles */
        .content-display table td[style*="background-color"],
        .content-display table th[style*="background-color"] {
            background-color: inherit !important;
        }
        
        .content-display table td[style*="text-align"],
        .content-display table th[style*="text-align"] {
            text-align: inherit !important;
        }

        /* Blockquotes */
        .content-display blockquote {
            border-left: 4px solid #e2e8f0;
            padding-left: 16px;
            margin: 16px 0;
            color: #6b7280;
            font-style: italic;
        }

        /* Code blocks */
        .content-display code {
            background-color: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', 'Monaco', 'Consolas', monospace;
            font-size: 0.9em;
        }
        
        .content-display pre {
            background-color: #f3f4f6;
            padding: 16px;
            border-radius: 8px;
            overflow-x: auto;
            margin: 16px 0;
        }
        
        .content-display pre code {
            background-color: transparent;
            padding: 0;
            border-radius: 0;
        }

        /* Horizontal rule */
        .content-display hr {
            border: none;
            border-top: 2px solid #e5e7eb;
            margin: 24px 0;
        }

        /* Preserve all inline styles from Summernote */
        .content-display [style*="font-size"] {
            /* Font sizes are preserved via inline styles */
        }
        
        .content-display [style*="color"] {
            /* Colors are preserved via inline styles */
        }
        
        .content-display [style*="background-color"] {
            /* Background colors are preserved via inline styles */
        }
        
        .content-display [style*="font-family"] {
            /* Font families are preserved via inline styles */
        }

        /* Line breaks */
        .content-display br {
            display: block;
            content: "";
            margin-top: 0.5em;
        }

        /* Divs (for custom formatting) */
        .content-display div {
            margin: 0;
        }

        /* Spans (for inline formatting) */
        .content-display span {
            /* Preserve inline styles */
        }

        /* Font sizes (common Summernote values) */
        .content-display [style*="font-size: 8px"] { font-size: 8px !important; }
        .content-display [style*="font-size: 9px"] { font-size: 9px !important; }
        .content-display [style*="font-size: 10px"] { font-size: 10px !important; }
        .content-display [style*="font-size: 11px"] { font-size: 11px !important; }
        .content-display [style*="font-size: 12px"] { font-size: 12px !important; }
        .content-display [style*="font-size: 14px"] { font-size: 14px !important; }
        .content-display [style*="font-size: 16px"] { font-size: 16px !important; }
        .content-display [style*="font-size: 18px"] { font-size: 18px !important; }
        .content-display [style*="font-size: 24px"] { font-size: 24px !important; }
        .content-display [style*="font-size: 36px"] { font-size: 36px !important; }

        /* Ensure nested formatting works */
        .content-display * {
            /* Allow all inline styles to cascade properly */
        }
    </style>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                    <span class="ml-4 text-sm font-medium text-gray-500">{{ Str::limit($update->title, 30) }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Update Content -->
    <article class="bg-white shadow-sm rounded-lg border border-gray-200">
        <!-- Header -->
        <div class="px-6 py-6 border-b border-gray-200">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $update->title }}</h1>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $update->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $update->status === 'published' ? __('updates.published') : __('updates.draft') }}
                        </span>
                        @if($update->shared_hash)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                                Vinculada
                            </span>
                        @endif
                    </div>
                    
                    <p class="text-xl text-gray-700 font-medium mb-4">{{ $update->caption }}</p>
                    
                    <div class="flex items-center space-x-6 text-sm text-gray-500">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <a href="{{ route('projects.show', $update->project->id) }}" class="hover:text-blue-600">
                                {{ $update->project->name }}
                            </a>
                        </div>
                        
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            {{ $update->views }} {{ __('updates.views') }}
                        </div>
                        
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $update->created_at->format('d/m/Y \à\s H:i') }}
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('updates.edit', $update->id) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        {{ __('common.edit') }}
                    </a>
                    <form action="{{ route('updates.destroy', $update->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja excluir esta atualização? Esta ação não pode ser desfeita.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            {{ __('common.delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="px-6 py-8">
                            <div class="prose max-w-none">
                    <div class="content-display text-gray-900 leading-relaxed">
                        {!! $update->description !!}
                    </div>
                </div>
        </div>

        <!-- Footer with metadata -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <dl class="grid grid-cols-1 sm:grid-cols-3 gap-6">                
                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('updates.last_update') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $update->updated_at->format('d/m/Y \à\s H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('updates.total_views') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $update->views }}</dd>
                </div>
            </dl>
        </div>
    </article>

    @if($linkedUpdates && $linkedUpdates->count() > 1)
    <!-- Linked Updates Information -->
    <div class="mt-8 bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Atualizações Vinculadas</h2>
            <p class="mt-1 text-sm text-gray-600">Esta atualização está vinculada a outras atualizações. Qualquer alteração será aplicada a todas.</p>
        </div>
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($linkedUpdates as $linkedUpdate)
                    <div class="border border-gray-200 rounded-lg p-4 {{ $linkedUpdate->id === $update->id ? 'bg-blue-50 border-blue-200' : 'bg-gray-50' }}">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-900">
                                @if($linkedUpdate->id === $update->id)
                                    <span class="text-blue-600">{{ $linkedUpdate->title }}</span>
                                    <span class="text-xs text-blue-500">(Atual)</span>
                                @else
                                    <a href="{{ route('updates.show', $linkedUpdate->id) }}" class="hover:text-blue-600">
                                        {{ $linkedUpdate->title }}
                                    </a>
                                @endif
                            </h3>
                        </div>
                        <div class="text-xs text-gray-500">
                            <div class="flex items-center mb-1">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ $linkedUpdate->customer->name }}
                            </div>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                {{ $linkedUpdate->views }} visualizações
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Project Information -->
    <div class="mt-8 bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">{{ __('updates.project_info') }}</h2>
        </div>
        <div class="px-6 py-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        <a href="{{ route('projects.show', $update->project->id) }}" class="hover:text-blue-600">
                            {{ $update->project->name }}
                        </a>
                    </h3>
                    
                    <div class="flex items-center space-x-4 text-sm text-gray-600 mb-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $update->project->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $update->project->status === 'active' ? __('projects.active') : __('projects.inactive') }}
                        </span>
                        <span>{{ __('updates.created_at') }} {{ $update->project->created_at->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">                        
                        <div>
                            <span class="font-medium text-gray-500">{{ __('updates.total_updates') }}:</span>
                            <span class="ml-2 text-gray-900">{{ $update->project->updates->count() }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="ml-6">
                    <a href="{{ route('projects.show', $update->project->id) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        {{ __('updates.view_project') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="mt-8 flex justify-between">
        <a href="{{ route('updates.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
                                    {{ __('updates.back_to_updates') }}
        </a>
    </div>
</div>
@endsection 