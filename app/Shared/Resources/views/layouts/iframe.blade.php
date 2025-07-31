<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Atualizações - UPMANAGER')</title>
    
    <!-- Favicons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.svg') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .iframe-container {
            min-height: 700px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }
        .iframe-container::-webkit-scrollbar {
            width: 6px;
        }
        .iframe-container::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .iframe-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        .iframe-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="iframe-container">
        @yield('content')
    </div>
</body>
</html> 