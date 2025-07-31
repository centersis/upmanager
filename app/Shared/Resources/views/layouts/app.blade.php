<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'UPMANAGER')</title>
    
    <!-- Favicons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.svg') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- TinyMCE CDN -->
    <script src="https://cdn.tiny.cloud/1/eluedarnrevptvryk2bf4e1bbuyoootthlbqazdmz8f4leab/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    
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
                                Dashboard
                            </a>
                            <a href="{{ route('customers.index') }}" 
                               class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 {{ request()->routeIs('customers.*') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                Clientes
                            </a>
                            <a href="{{ route('projects.index') }}" 
                               class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 {{ request()->routeIs('projects.*') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                Projetos
                            </a>
                            <a href="{{ route('updates.index') }}" 
                               class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 {{ request()->routeIs('updates.*') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                Atualizações
                            </a>
                            {{-- @if(Auth::user()->isAdmin()) --}}
                                <a href="{{ route('users.index') }}" 
                                   class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 {{ request()->routeIs('users.*') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                    Usuários
                                </a>
                            {{-- @endif --}}
                        </div>
                        @endauth
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center space-x-4">
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
                                    Sair
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
                                    Login
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
    
    <!-- TinyMCE Configuration Script -->
    <script>
        // Global TinyMCE configuration
        window.initTinyMCE = function(selector = '.tinymce') {
            if (typeof tinymce !== 'undefined') {
                tinymce.init({
                    selector: selector,
                    height: 400,
                    menubar: 'edit view insert format tools table help',
                    plugins: [
                        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                        'insertdatetime', 'media', 'table', 'wordcount', 'help', 'emoticons',
                        'codesample', 'quickbars'
                    ],
                    toolbar: 'undo redo | blocks fontfamily fontsize | ' +
                        'bold italic underline strikethrough | forecolor backcolor | ' +
                        'alignleft aligncenter alignright alignjustify | ' +
                        'bullist numlist outdent indent | ' +
                        'link image media table | codesample emoticons | ' +
                        'removeformat code fullscreen help',
                    
                    // Content styling
                    content_style: `
                        body { 
                            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; 
                            font-size: 14px;
                            line-height: 1.6;
                            color: #374151;
                            margin: 1rem;
                        }
                        img { 
                            max-width: 100%; 
                            height: auto; 
                            border-radius: 4px;
                            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                        }
                        iframe { max-width: 100%; }
                        .video-wrapper { 
                            position: relative; 
                            padding-bottom: 56.25%; 
                            height: 0; 
                            overflow: hidden;
                            border-radius: 4px;
                            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                        }
                        .video-wrapper iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
                        pre { 
                            background: #f8fafc; 
                            border: 1px solid #e2e8f0; 
                            border-radius: 4px; 
                            padding: 1rem; 
                            overflow-x: auto; 
                        }
                        code { 
                            background: #f1f5f9; 
                            padding: 0.2rem 0.4rem; 
                            border-radius: 3px; 
                            font-size: 0.875em; 
                        }
                    `,
                    
                    // Enable automatic uploads and paste
                    automatic_uploads: true,
                    paste_data_images: true,
                    file_picker_types: 'image',
                    images_upload_url: false, // Disable server upload since we're using base64
                    convert_urls: false, // Keep base64 URLs as-is
                    
                    // Image upload configuration - Base64 encoding
                    images_upload_handler: function (blobInfo, progress) {
                        return new Promise((resolve, reject) => {
                            try {
                                // Validate file size (limit to 2MB)
                                if (blobInfo.blob().size > 2 * 1024 * 1024) {
                                    reject({
                                        message: 'Imagem muito grande. Máximo 2MB permitido.',
                                        remove: true
                                    });
                                    return;
                                }
                                
                                // Validate file type
                                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                                if (!validTypes.includes(blobInfo.blob().type)) {
                                    reject({
                                        message: 'Tipo de arquivo não suportado. Use JPEG, PNG, GIF ou WebP.',
                                        remove: true
                                    });
                                    return;
                                }
                                
                                // Convert blob to base64
                                const reader = new FileReader();
                                
                                reader.onload = function() {
                                    try {
                                        // Get the base64 result
                                        const base64 = reader.result;
                                        
                                        if (!base64) {
                                            reject({
                                                message: 'Erro ao converter imagem para base64.',
                                                remove: true
                                            });
                                            return;
                                        }
                                        
                                        console.log('Imagem convertida para base64:', {
                                            filename: blobInfo.filename(),
                                            size: blobInfo.blob().size,
                                            type: blobInfo.blob().type,
                                            base64Length: base64.length
                                        });
                                        
                                        // Return the base64 data URL directly
                                        resolve(base64);
                                    } catch (error) {
                                        console.error('Erro no onload do FileReader:', error);
                                        reject({
                                            message: 'Erro interno ao processar imagem.',
                                            remove: true
                                        });
                                    }
                                };
                                
                                reader.onerror = function(error) {
                                    console.error('Erro no FileReader:', error);
                                    reject({
                                        message: 'Erro ao ler o arquivo de imagem.',
                                        remove: true
                                    });
                                };
                                
                                reader.onprogress = function(e) {
                                    if (e.lengthComputable && progress) {
                                        progress((e.loaded / e.total) * 100);
                                    }
                                };
                                
                                // Start reading the file as data URL (base64)
                                reader.readAsDataURL(blobInfo.blob());
                                
                            } catch (error) {
                                console.error('Erro geral no upload de imagem:', error);
                                reject({
                                    message: 'Erro inesperado ao processar imagem.',
                                    remove: true
                                });
                            }
                        });
                    },
                    
                    // Media (YouTube) configuration
                    media_live_embeds: true,
                    media_url_resolver: function (data, resolve) {
                        if (data.url.indexOf('youtube.com') !== -1 || data.url.indexOf('youtu.be') !== -1) {
                            let videoId = '';
                            
                            // Extract video ID from different YouTube URL formats
                            if (data.url.indexOf('youtube.com/watch?v=') !== -1) {
                                videoId = data.url.split('v=')[1].split('&')[0];
                            } else if (data.url.indexOf('youtu.be/') !== -1) {
                                videoId = data.url.split('youtu.be/')[1].split('?')[0];
                            } else if (data.url.indexOf('youtube.com/embed/') !== -1) {
                                videoId = data.url.split('embed/')[1].split('?')[0];
                            }
                            
                            if (videoId) {
                                resolve({
                                    html: `<div class="video-wrapper">
                                            <iframe src="https://www.youtube.com/embed/${videoId}" 
                                                    frameborder="0" 
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                    allowfullscreen>
                                            </iframe>
                                          </div>`
                                });
                            } else {
                                resolve({ html: '' });
                            }
                        } else {
                            resolve({ html: '' });
                        }
                    },
                    
                    // Additional configurations
                    branding: false,
                    promotion: false,
                    resize: true,
                    statusbar: true,
                    elementpath: false,
                    
                    // Block formats
                    block_formats: 'Parágrafo=p; Cabeçalho 1=h1; Cabeçalho 2=h2; Cabeçalho 3=h3; Cabeçalho 4=h4; Cabeçalho 5=h5; Cabeçalho 6=h6; Pré-formatado=pre; Endereço=address',
                    
                    // Font options
                    font_family_formats: 'Arial=arial,helvetica,sans-serif; Georgia=georgia,palatino,serif; Helvetica=helvetica,arial,sans-serif; Times New Roman=times new roman,times,serif; Verdana=verdana,geneva,sans-serif; Courier New=courier new,courier,monospace',
                    font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt',
                    
                    // Enhanced image options
                    image_advtab: true,
                    image_caption: true,
                    image_title: true,
                    
                    // Table options
                    table_responsive_width: true,
                    table_default_attributes: {
                        'class': 'table table-striped'
                    },
                    table_default_styles: {
                        'border-collapse': 'collapse',
                        'width': '100%'
                    },
                    
                    // Link options
                    link_title: false,
                    target_list: [
                        {title: 'Mesma janela', value: ''},
                        {title: 'Nova janela', value: '_blank'}
                    ],
                    
                    // Setup callback
                    setup: function (editor) {
                        editor.on('init', function () {
                            console.log('TinyMCE initialized successfully for:', selector);
                        });
                        
                        editor.on('change', function () {
                            editor.save();
                        });
                        
                        // Debug image upload events
                        editor.on('UploadComplete', function (e) {
                            console.log('Upload complete:', e);
                        });
                        
                        editor.on('UploadFailure', function (e) {
                            console.error('Upload failure:', e);
                        });
                        
                        editor.on('ImageUploadError', function (e) {
                            console.error('Image upload error:', e);
                        });
                    }
                });
            } else {
                console.error('TinyMCE not loaded');
            }
        };
        
        // Destroy TinyMCE instances
        window.destroyTinyMCE = function(selector = '.tinymce') {
            if (typeof tinymce !== 'undefined') {
                tinymce.remove(selector);
            }
        };
    </script>
    
    @yield('scripts')
</body>
</html>
