<!-- AdminLTE Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">{{ __('admin.home') }}</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Language Selector -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                @php
                    $currentLocale = app()->getLocale();
                    $languages = [
                        'uz' => ['name' => 'O\'zbek', 'flag' => '🇺🇿'],
                        'en' => ['name' => 'English', 'flag' => '🇺🇸'],
                        'ru' => ['name' => 'Русский', 'flag' => '🇷🇺']
                    ];
                @endphp
                <span class="flag-icon">{{ $languages[$currentLocale]['flag'] ?? '🇺🇿' }}</span>
                <span class="d-none d-md-inline">{{ $languages[$currentLocale]['name'] ?? 'O\'zbek' }}</span>
                <i class="fas fa-angle-down ml-1"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <div class="dropdown-header">{{ __('admin.choose_language') }}</div>
                <div class="dropdown-divider"></div>

                @foreach($languages as $code => $language)
                    <a href="{{ url()->current() }}?lang={{ $code }}"
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

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="far fa-bell"></i>
                @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                    <span class="badge badge-warning navbar-badge">{{ $unreadNotificationsCount }}</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">{{ $unreadNotificationsCount ?? 0 }} {{ __('admin.notifications') }}</span>

                <div class="dropdown-divider"></div>

                @if(isset($recentNotifications) && count($recentNotifications) > 0)
                    @foreach($recentNotifications->take(3) as $notification)
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> {{ Str::limit($notification->title ?? 'Notification', 30) }}
                            <span class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans() ?? 'now' }}</span>
                        </a>
                        @if(!$loop->last)
                            <div class="dropdown-divider"></div>
                        @endif
                    @endforeach
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.notifications.index') }}" class="dropdown-item dropdown-footer">{{ __('admin.see_all_notifications') }}</a>
                @else
                    <div class="dropdown-item text-center text-muted">
                        <i class="fas fa-bell-slash mr-2"></i>
                        {{ __('admin.no_notifications') }}
                    </div>
                @endif
            </div>
        </li>

        <!-- Quick Add Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="fas fa-plus"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">{{ __('admin.quick_add') }}</span>
                <div class="dropdown-divider"></div>

                <a href="{{ route('admin.products.create') }}" class="dropdown-item">
                    <i class="fas fa-box mr-2"></i> {{ __('admin.add_product') }}
                </a>
                <div class="dropdown-divider"></div>

                <a href="{{ route('admin.categories.create') }}" class="dropdown-item">
                    <i class="fas fa-tags mr-2"></i> {{ __('admin.add_category') }}
                </a>
                <div class="dropdown-divider"></div>

                <a href="{{ route('admin.users.create') }}" class="dropdown-item">
                    <i class="fas fa-user-plus mr-2"></i> {{ __('admin.add_user') }}
                </a>
            </div>
        </li>

        <!-- User Account Dropdown -->
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                @if(auth()->user()->avatar)
                    <img src="{{ auth()->user()->avatar }}" class="user-image img-circle elevation-2" alt="{{ auth()->user()->name }}">
                @else
                    <div class="user-image img-circle elevation-2 d-inline-flex align-items-center justify-content-center bg-primary text-white"
                         style="width: 25px; height: 25px; font-size: 12px;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User image -->
                <li class="user-header bg-primary">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" class="img-circle elevation-2" alt="{{ auth()->user()->name }}">
                    @else
                        <div class="img-circle elevation-2 d-flex align-items-center justify-content-center bg-white text-primary"
                             style="width: 90px; height: 90px; font-size: 36px; margin: 0 auto;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif

                    <p class="text-white">
                        {{ auth()->user()->name }}
                        <small>{{ __('admin.member_since') }} {{ auth()->user()->created_at->format('M Y') }}</small>
                    </p>
                </li>

                <!-- Menu Footer-->
                <li class="user-footer">
                    <a href="{{ route('admin.profile') }}" class="btn btn-default btn-flat">
                        <i class="fas fa-user mr-2"></i>{{ __('admin.profile') }}
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="btn btn-default btn-flat">
                        <i class="fas fa-cog mr-2"></i>{{ __('admin.settings') }}
                    </a>
                    <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-default btn-flat float-right">
                            <i class="fas fa-sign-out-alt mr-2"></i>{{ __('admin.sign_out') }}
                        </button>
                    </form>
                </li>
            </ul>
        </li>

        <!-- Control Sidebar -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
