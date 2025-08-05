<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('admin.dashboard')) - DOM Product Admin</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="{{ mix('css/admin.css') }}">

    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">{{ __('admin.home') }}</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Language Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#" aria-expanded="false">
                    @php
                        $currentLocale = app()->getLocale();
                        $languages = [
                            'uz' => ['name' => 'O\'zbek', 'flag' => '🇺🇿'],
                            'en' => ['name' => 'English', 'flag' => '🇺🇸'],
                            'ru' => ['name' => 'Русский', 'flag' => '🇷🇺']
                        ];
                    @endphp
                    <span class="flag-icon">{{ $languages[$currentLocale]['flag'] ?? '🇺🇿' }}</span>
                    <span class="d-none d-md-inline ml-1">{{ $languages[$currentLocale]['name'] ?? 'O\'zbek' }}</span>
                    {{-- <i class="fas fa-angle-down ml-1"></i> --}}
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <div class="dropdown-header">{{ __('admin.choose_language') }}</div>
                    <div class="dropdown-divider"></div>

                    @foreach($languages as $code => $language)
                        <a href="{{ route('admin.language.switch', $code) }}"
                           class="dropdown-item {{ $currentLocale == $code ? 'active' : '' }}">
                            <span class="mr-2">{{ $language['flag'] }}</span>
                            {{ $language['name'] }}
                            @if($currentLocale == $code)
                                <i class="fas fa-check text-success float-right mt-1"></i>
                            @endif
                        </a>
                    @endforeach
                </div>
            </li>

            <!-- User Account -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">{{ Auth::user()->name }}</span>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">
                        <i class="fas fa-user mr-2"></i> {{ __('admin.profile') }}
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.settings.index') }}" class="dropdown-item">
                        <i class="fas fa-cogs mr-2"></i> {{ __('admin.settings') }}
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt mr-2"></i> {{ __('admin.logout') }}
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <img src="{{ asset('favicon.png') }}" alt="DOM Product Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">DOM Product</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="{{ route('admin.profile.edit') }}" class="d-block">{{ Auth::user()->name }}</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>{{ __('admin.dashboard') }}</p>
                        </a>
                    </li>

                    <!-- Users Management -->
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>{{ __('admin.users') }}</p>
                        </a>
                    </li>

                    <!-- Products Management -->
                    <li class="nav-item {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box"></i>
                            <p>
                                {{ __('admin.products') }}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('admin.all_products') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('admin.categories') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Orders Management -->
                    <li class="nav-item">
                        <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>{{ __('admin.orders') }}</p>
                        </a>
                    </li>

                    <!-- Settings -->
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>{{ __('admin.settings') }}</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; {{ date('Y') }} <a href="https://domproduct.uz">DOM Product</a>.</strong>
        Barcha huquqlar himoyalangan.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<!-- Custom Admin JS -->
<script src="{{ mix('js/admin.js') }}"></script>

@stack('scripts')

<!-- Toast Messages -->
@if(session('success'))
<script>
$(function() {
    $(document).Toasts('create', {
        class: 'bg-success',
        title: 'Muvaffaqiyat',
        body: '{{ session('success') }}',
        autohide: true,
        delay: 5000
    });
});
</script>
@endif

@if(session('error'))
<script>
$(function() {
    $(document).Toasts('create', {
        class: 'bg-danger',
        title: 'Xatolik',
        body: '{{ session('error') }}',
        autohide: true,
        delay: 5000
    });
});
</script>
@endif

@if(session('warning'))
<script>
$(function() {
    $(document).Toasts('create', {
        class: 'bg-warning',
        title: 'Ogohlantirish',
        body: '{{ session('warning') }}',
        autohide: true,
        delay: 5000
    });
});
</script>
@endif

</body>
</html>
