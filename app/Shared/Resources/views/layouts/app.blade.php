<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'UPMANAGER')</title>
    
    <!-- Favicons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.svg') }}">
    
    <link rel="stylesheet" href="/build/assets/app-BSDPyD84.css">
    <script type="module" src="/build/assets/app-DtCVKgHt.js"></script>        
    
    @yield('head')
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('dashboard') }}" class="flex items-center">
                            <img src="{{ asset('img/logo.svg') }}" alt="UpManager" class="h-8 w-auto mr-2">
                            <span class="text-xl font-bold text-gray-900">UPMANAGER</span>
                        </a>
                        
                        <!-- Navigation Links -->
                        @auth
                        <div class="hidden md:ml-8 md:flex md:space-x-4">
                            <a href="{{ route('dashboard') }}" 
                               class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 {{ request()->routeIs('dashboard') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                {{ __('dashboard.title') }}
                            </a>
                            <a href="{{ route('customers.index') }}" 
                               class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 {{ request()->routeIs('customers.*') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                {{ __('dashboard.customers') }}
                            </a>
                            <a href="{{ route('projects.index') }}" 
                               class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 {{ request()->routeIs('projects.*') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                {{ __('dashboard.projects') }}
                            </a>
                            <a href="{{ route('updates.index') }}" 
                               class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 {{ request()->routeIs('updates.*') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                {{ __('dashboard.updates') }}
                            </a>
                            {{-- @if(Auth::user()->isAdmin()) --}}
                                <a href="{{ route('users.index') }}" 
                                   class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 {{ request()->routeIs('users.*') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                    {{ __('dashboard.users') }}
                                </a>
                            {{-- @endif --}}
                        </div>
                        @endauth
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center space-x-4">
                        <!-- Language Selector -->
                        @include('shared::components.language-selector')
                        
                        @auth
                            <!-- User Info -->
                            <div class="hidden md:flex md:items-center md:space-x-2">
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->role_display }}</p>
                                </div>
                                <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-blue-600">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    {{ __('auth.logout') }}
                                </button>
                            </form>
                        @else
                            <!-- Login Link -->
                            <div class="flex items-center">
                                <a href="{{ route('login') }}" 
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    {{ __('auth.login') }}
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-1">
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>

    @yield('scripts')
    @stack('scripts')
</body>
</html>
