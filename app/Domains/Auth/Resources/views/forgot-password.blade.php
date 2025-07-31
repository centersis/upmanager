<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('auth.reset_password') }} - UPMANAGER</title>
    
    <!-- Favicons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.svg') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <!-- Logo -->
        <div class="mb-6 text-center">
            <div class="flex items-center justify-center mb-2">
                <img src="{{ asset('img/logo.svg') }}" alt="UpManager" class="h-12 w-auto mr-3">
                <h1 class="text-4xl font-bold text-gray-900">UPMANAGER</h1>
            </div>
            <p class="text-gray-600 mt-2">{{ __('auth.system_description') }}</p>
        </div>

        <!-- Language Selector -->
        <div class="w-full sm:max-w-md mb-4 flex justify-end">
            @include('shared::components.language-selector')
        </div>

        <!-- Forgot Password Card -->
        <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-lg rounded-lg border border-gray-200">
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-900 text-center">{{ __('auth.forgot_password_title') }}</h2>
                <p class="text-center text-gray-600 mt-1 text-sm">
                    {{ __('auth.forgot_password_text') }}
                </p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('auth.email') }} <span class="text-red-500">*</span>
                    </label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-300 @enderror"
                        placeholder="{{ __('auth.email_placeholder') }}"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('login') }}" 
                       class="text-sm text-gray-600 hover:text-gray-500 hover:underline">
                        {{ __('auth.back_to_login') }}
                    </a>
                    
                    <button 
                        type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ __('auth.send_reset_link') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center">
            <p class="text-xs text-gray-500">
                © {{ date('Y') }} UPMANAGER. {{ __('auth.all_rights_reserved') }}.
            </p>
        </div>
    </div>
</body>
</html>
