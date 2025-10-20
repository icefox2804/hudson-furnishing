<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Hudson Furnishing')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/floatingchat.js'])
    
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <!-- Header -->
    @include('components.header')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.footer')

    <!-- Floating Chat Buttons -->
<div class="chat-float" id="chatFloat">
    <button class="chat-main-btn" id="chatMainBtn">
        <i class="fa-regular fa-comment"></i>
    </button>

    <a href="{{ $siteSettings['chat_zalo_url'] ?? 'https://zalo.me/' }}" target="_blank" class="chat-btn zalo-btn">
        <i class="fa-brands fa-zalo"></i>Zalo 
    </a>

    <a href="{{ $siteSettings['chat_messenger_url'] ?? 'https://m.me/' }}" target="_blank" class="chat-btn messenger-btn">
        <i class="fa-brands fa-facebook-messenger"></i>
    </a>

    <a href="tel:{{ $siteSettings['chat_phone_number'] ?? '0123456789' }}" class="chat-btn phone-btn">
        <i class="fa-solid fa-phone"></i>
    </a>
</div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
