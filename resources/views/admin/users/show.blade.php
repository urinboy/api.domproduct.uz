@extends('admin.layouts.app')

@section('title', __('admin.user_details'))

@push('styles')
<style>
    .user-avatar-large {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        padding: 1.5rem;
        color: white;
        margin-bottom: 1rem;
    }
    .stat-card h3 {
        margin: 0;
        font-size: 2rem;
        font-weight: bold;
    }
    .stat-card p {
        margin: 0;
        opacity: 0.9;
    }
    .info-section {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 0.375rem;
        margin-bottom: 1.5rem;
    }
    .info-section h4 {
        color: #495057;
        border-bottom: 2px solid #007bff;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #e9ecef;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        font-weight: 600;
        color: #6c757d;
    }
    .info-value {
        color: #495057;
    }
    .badge-role {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
    }
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('admin.user_details') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">{{ __('admin.users') }}</a></li>
                    <li class="breadcrumb-item active">{{ $user->full_name }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- User Profile Card -->
            <div class="col-md-4">
                <div class="card card-primary card-outline">
                    <div class="card-body text-center">
                        <img src="{{ $user->getAvatarUrl('large') }}" alt="{{ $user->full_name }}" class="user-avatar-large mx-auto d-block">

                        <h3 class="profile-username text-center mt-3">{{ $user->full_name }}</h3>

                        <p class="text-muted text-center">
                            <span class="badge badge-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'manager' ? 'warning' : 'info') }} badge-role">
                                {{ __('admin.' . $user->role) }}
                            </span>
                        </p>

                        <div class="text-center mt-3">
                            @if($user->is_active)
                                <span class="badge badge-success">{{ __('admin.active') }}</span>
                            @else
                                <span class="badge badge-secondary">{{ __('admin.inactive') }}</span>
                            @endif

                            @if($user->email_verified_at)
                                <span class="badge badge-success">{{ __('admin.email_verified') }}</span>
                            @else
                                <span class="badge badge-danger">{{ __('admin.email_not_verified') }}</span>
                            @endif

                            @if($user->phone_verified)
                                <span class="badge badge-success">{{ __('admin.phone_verified') }}</span>
                            @endif
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-block">
                                <i class="fas fa-edit mr-2"></i>{{ __('admin.edit_user') }}
                            </a>

                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="mt-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-{{ $user->is_active ? 'secondary' : 'success' }} btn-block">
                                    <i class="fas fa-{{ $user->is_active ? 'pause' : 'play' }} mr-2"></i>
                                    {{ $user->is_active ? __('admin.deactivate') : __('admin.activate') }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" class="mt-2"
                                  onsubmit="return confirm('{{ __('admin.confirm_reset_password') }}')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-info btn-block">
                                    <i class="fas fa-key mr-2"></i>{{ __('admin.reset_password') }}
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.user_statistics') }}</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="p-3">
                            <div class="stat-card">
                                <h3>{{ $stats['orders_count'] ?? 0 }}</h3>
                                <p>{{ __('admin.total_orders') }}</p>
                            </div>

                            <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <h3>${{ number_format($stats['total_spent'] ?? 0, 2) }}</h3>
                                <p>{{ __('admin.total_spent') }}</p>
                            </div>

                            <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                <h3>{{ $stats['products_viewed'] ?? 0 }}</h3>
                                <p>{{ __('admin.products_viewed') }}</p>
                            </div>

                            <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                                <h3>{{ $stats['ratings_given'] ?? 0 }}</h3>
                                <p>{{ __('admin.ratings_given') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Information -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.user_information') }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left mr-2"></i>{{ __('admin.back_to_users') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Basic Information -->
                        <div class="info-section">
                            <h4><i class="fas fa-user mr-2"></i>{{ __('admin.basic_information') }}</h4>
                            <div class="info-row">
                                <span class="info-label">{{ __('admin.username') }}:</span>
                                <span class="info-value">{{ $user->name }}</span>
                            </div>
                            @if($user->first_name || $user->last_name)
                            <div class="info-row">
                                <span class="info-label">{{ __('admin.full_name') }}:</span>
                                <span class="info-value">{{ $user->first_name }} {{ $user->last_name }}</span>
                            </div>
                            @endif
                            <div class="info-row">
                                <span class="info-label">{{ __('admin.email') }}:</span>
                                <span class="info-value">
                                    {{ $user->email }}
                                    @if($user->email_verified_at)
                                        <i class="fas fa-check-circle text-success ml-2"></i>
                                    @endif
                                </span>
                            </div>
                            @if($user->phone)
                            <div class="info-row">
                                <span class="info-label">{{ __('admin.phone') }}:</span>
                                <span class="info-value">
                                    {{ $user->phone }}
                                    @if($user->phone_verified)
                                        <i class="fas fa-check-circle text-success ml-2"></i>
                                    @endif
                                </span>
                            </div>
                            @endif
                            @if($user->birth_date)
                            <div class="info-row">
                                <span class="info-label">{{ __('admin.birth_date') }}:</span>
                                <span class="info-value">{{ $user->birth_date->format('d.m.Y') }} ({{ $user->birth_date->age }} {{ __('admin.years_old') }})</span>
                            </div>
                            @endif
                            @if($user->gender)
                            <div class="info-row">
                                <span class="info-label">{{ __('admin.gender') }}:</span>
                                <span class="info-value">{{ __('admin.' . $user->gender) }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- Address Information -->
                        @if($user->address || $user->city || $user->district)
                        <div class="info-section">
                            <h4><i class="fas fa-map-marker-alt mr-2"></i>{{ __('admin.address_information') }}</h4>
                            @if($user->address)
                            <div class="info-row">
                                <span class="info-label">{{ __('admin.address') }}:</span>
                                <span class="info-value">{{ $user->address }}</span>
                            </div>
                            @endif
                            @if($user->city)
                            <div class="info-row">
                                <span class="info-label">{{ __('admin.city') }}:</span>
                                <span class="info-value">{{ $user->city }}</span>
                            </div>
                            @endif
                            @if($user->district)
                            <div class="info-row">
                                <span class="info-label">{{ __('admin.district') }}:</span>
                                <span class="info-value">{{ $user->district }}</span>
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- Account Information -->
                        <div class="info-section">
                            <h4><i class="fas fa-cog mr-2"></i>{{ __('admin.account_information') }}</h4>
                            <div class="info-row">
                                <span class="info-label">{{ __('admin.role') }}:</span>
                                <span class="info-value">
                                    <span class="badge badge-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'manager' ? 'warning' : 'info') }}">
                                        {{ __('admin.' . $user->role) }}
                                    </span>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">{{ __('admin.status') }}:</span>
                                <span class="info-value">
                                    @if($user->is_active)
                                        <span class="badge badge-success">{{ __('admin.active') }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ __('admin.inactive') }}</span>
                                    @endif
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">{{ __('admin.created_date') }}:</span>
                                <span class="info-value">{{ $user->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">{{ __('admin.last_updated') }}:</span>
                                <span class="info-value">{{ $user->updated_at->format('d.m.Y H:i') }}</span>
                            </div>
                            @if($user->last_login_at)
                            <div class="info-row">
                                <span class="info-label">{{ __('admin.last_login') }}:</span>
                                <span class="info-value">{{ $user->last_login_at->format('d.m.Y H:i') }}</span>
                            </div>
                            @endif
                        </div>

                        @if($user->preferences)
                        <!-- Preferences -->
                        <div class="info-section">
                            <h4><i class="fas fa-sliders-h mr-2"></i>{{ __('admin.preferences') }}</h4>
                            <div class="info-row">
                                <span class="info-label">{{ __('admin.preferences_data') }}:</span>
                                <span class="info-value">
                                    <code>{{ json_encode($user->preferences, JSON_PRETTY_PRINT) }}</code>
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Orders -->
                @if($user->orders()->exists())
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.recent_orders') }}</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.order_id') }}</th>
                                    <th>{{ __('admin.total_amount') }}</th>
                                    <th>{{ __('admin.status') }}</th>
                                    <th>{{ __('admin.order_date') }}</th>
                                    <th>{{ __('admin.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->orders()->latest()->limit(5)->get() as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>${{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $order->status == 'completed' ? 'success' : 'warning' }}">
                                            {{ __('admin.' . $order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('d.m.Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
