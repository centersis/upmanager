<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verificar E-mail - UPMANAGER</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <!-- Logo -->
        <div class="mb-6">
            <h1 class="text-4xl font-bold text-gray-900">UPMANAGER</h1>
            <p class="text-center text-gray-600 mt-2">Sistema de Gerenciamento de Atualizações</p>
        </div>

        <!-- Verify Email Card -->
        <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-lg rounded-lg border border-gray-200">
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-900 text-center">Verificar E-mail</h2>
                <p class="text-center text-gray-600 mt-2 text-sm">
                    Obrigado por se cadastrar! Antes de começar, você poderia verificar seu endereço de e-mail clicando no link que acabamos de enviar? Se você não recebeu o e-mail, ficaremos felizes em enviar outro.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md">
                    <div class="flex">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-green-700">
                            Um novo link de verificação foi enviado para o endereço de e-mail fornecido durante o cadastro.
                        </p>
                    </div>
                </div>
            @endif

            <div class="flex items-center justify-between space-x-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button 
                        type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Reenviar E-mail
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button 
                        type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Sair
                    </button>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center">
            <p class="text-xs text-gray-500">
                © {{ date('Y') }} UPMANAGER. Todos os direitos reservados.
            </p>
        </div>
    </div>
</body>
</html>
