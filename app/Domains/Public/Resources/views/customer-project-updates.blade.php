@extends('shared::layouts.public')

@section('title', $customer->name . ' - ' . $project->name . ' - ' . __('public.updates_title') . ' - UPMANAGER')

@section('content')
<div class="mb-8">
    <!-- Navigation Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ public_customer_link($customer->hash) }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    {{ $customer->name }}
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $project->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Project Header -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-8">
        <div class="px-6 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $project->name }}</h1>
                    <p class="text-lg text-gray-600 mt-1">{{ __('public.client') }} {{ $customer->name }}</p>
                    <div class="mt-3 flex items-center space-x-4">
                        @if($project->group)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $project->group->name }}
                            </span>
                        @endif
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            {{ ucfirst($project->status) }}
                        </span>
                        <span class="text-sm text-gray-600">
                            {{ $updates->total() }} {{ $updates->total() === 1 ? __('public.update_singular') : __('public.update_plural') }}
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">{{ __('public.client_portal') }}</div>
                    <div class="text-xs text-gray-400">{{ __('public.last_update') }} {{ $updates->first()?->created_at?->format('d/m/Y H:i') ?? __('public.none') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
        <h3 class="text-sm font-medium text-blue-900 mb-2">{{ __('public.project_info') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <span class="text-xs text-blue-700 font-medium">{{ __('public.client') }}</span>
                <p class="text-sm text-blue-900">{{ $customer->name }}</p>
            </div>
            @if($project->group)
            <div>
                <span class="text-xs text-blue-700 font-medium">{{ __('public.group') }}</span>
                <p class="text-sm text-blue-900">{{ $project->group->name }}</p>
            </div>
            @endif
            <div>
                <span class="text-xs text-blue-700 font-medium">{{ __('public.status') }}</span>
                <p class="text-sm text-blue-900">{{ ucfirst($project->status) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Updates List -->
@if($updates->count() > 0)
    <div class="space-y-6">
        @foreach($updates as $update)
            <article class="bg-white shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h2 class="text-xl font-semibold text-gray-900">
                                    <a href="{{ route('public.update', $update->hash) }}" class="hover:text-blue-600 transition-colors">
                                        {{ $update->title }}
                                    </a>
                                </h2>
                                @if($update->is_global)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ __('public.global') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ __('public.specific') }}
                                    </span>
                                @endif
                            </div>
                            
                            @if($update->caption)
                                <p class="text-gray-600 mb-3 text-sm">{{ $update->caption }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 0h6m-6 0V6a1 1 0 00-1 1v1m8 0v10a2 2 0 01-2 2H7a2 2 0 01-2-2V8a2 2 0 012-2h10a2 2 0 012 2z"/>
                                    </svg>
                                    {{ $update->project->name }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $update->created_at->format('d/m/Y H:i') }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    {{ $update->views }} {{ $update->views === 1 ? __('public.view_singular') : __('public.view_plural') }}
                                </span>
                            </div>
                            
                            <a href="{{ route('public.update', $update->hash) }}" 
                               class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors">
                                {{ __('public.read_more') }}
                                <svg class="ml-1 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($updates->hasPages())
        <div class="mt-8">
            {{ $updates->links() }}
        </div>
    @endif
@else
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('public.no_updates') }}</h3>
        <p class="mt-1 text-sm text-gray-500">{{ __('public.no_project_updates_for_client') }}</p>
    </div>
@endif


@endsection 