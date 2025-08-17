<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'DomProduct') }} - @yield('title', 'Online Do\'kon')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fruktur:ital@0;1&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/variables.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/base.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/components.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/pages.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/responsive.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    <div id="app">
        <!-- Navigation -->
        @include('mobile.components.navigation')
        
        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>
        
        <!-- Footer -->
        @include('mobile.components.footer')
        
        <!-- Toast Notifications -->
        @include('mobile.components.toast')
        
        <!-- Loading Overlay -->
        @include('mobile.components.loading')
    </div>
    
    <!-- JavaScript -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/forms.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
