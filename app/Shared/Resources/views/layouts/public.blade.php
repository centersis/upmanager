<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'UPMANAGER')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen">
        <!-- Public Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="text-xl font-bold text-gray-900">
                            UPMANAGER
                        </div>
                        <div class="ml-4 text-sm text-gray-500">
                            Portal Público de Atualizações
                        </div>
                    </div>                                    
                </div>
            </div>
        </nav>

        <main class="flex-1">
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="text-center text-sm text-gray-500">
                    <p>&copy; {{ date('Y') }} UPMANAGER. Portal de atualizações de projetos.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html> 