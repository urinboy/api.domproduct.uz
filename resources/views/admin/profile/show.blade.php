@extends('admin.layouts.app')

@section('title', __('admin.my_profile'))

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('admin.my_profile') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('admin.profile') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4">
                <!-- Profile Image Card -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle"
                                 src="{{ $user->avatar ? $user->avatar : asset('images/default-avatar.png') }}"
                                 alt="{{ $user->name }}"
                                 style="width: 128px; height: 128px; object-fit: cover;">
                        </div>

                        <h3 class="profile-username text-center">{{ $user->name }}</h3>

                        <p class="text-muted text-center">
                            {{ $user->first_name && $user->last_name ? $user->first_name . ' ' . $user->last_name : __('admin.administrator') }}
                        </p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>{{ __('admin.member_since') }}</b>
                                <a class="float-right">{{ $user->created_at->format('M Y') }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{ __('admin.last_login') }}</b>
                                <a class="float-right">{{ $user->updated_at->format('d M Y') }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{ __('admin.role') }}</b>
                                <a class="float-right">{{ __('admin.administrator') }}</a>
                            </li>
                        </ul>

                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-edit"></i> {{ __('admin.edit_profile') }}
                        </a>
                    </div>
                </div>

                <!-- Contact Information Card -->
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.contact_information') }}</h3>
                    </div>
                    <div class="card-body">
                        <strong><i class="fas fa-envelope mr-1"></i> {{ __('admin.email') }}</strong>
                        <p class="text-muted">
                            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                        </p>

                        <hr>

                        <strong><i class="fas fa-phone mr-1"></i> {{ __('admin.phone') }}</strong>
                        <p class="text-muted">
                            @if($user->phone)
                                <a href="tel:{{ $user->phone }}">{{ $user->phone }}</a>
                            @else
                                {{ __('admin.not_specified') }}
                            @endif
                        </p>

                        <hr>

                        <strong><i class="fas fa-calendar mr-1"></i> {{ __('admin.account_created') }}</strong>
                        <p class="text-muted">{{ $user->created_at->format('d F Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <!-- Account Details Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.account_details') }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.profile.edit') }}" class="btn btn-tool">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('admin.name') }}:</label>
                                    <p class="form-control-static">{{ $user->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('admin.email') }}:</label>
                                    <p class="form-control-static">{{ $user->email }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('admin.first_name') }}:</label>
                                    <p class="form-control-static">{{ $user->first_name ?: __('admin.not_specified') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('admin.last_name') }}:</label>
                                    <p class="form-control-static">{{ $user->last_name ?: __('admin.not_specified') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('admin.phone') }}:</label>
                                    <p class="form-control-static">{{ $user->phone ?: __('admin.not_specified') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('admin.account_status') }}:</label>
                                    <p class="form-control-static">
                                        <span class="badge badge-success">{{ __('admin.active') }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.security_information') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('admin.password') }}:</label>
                                    <p class="form-control-static">
                                        ••••••••••••
                                        <a href="{{ route('admin.profile.edit') }}" class="ml-2">
                                            <i class="fas fa-edit"></i> {{ __('admin.change') }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('admin.last_password_change') }}:</label>
                                    <p class="form-control-static">{{ $user->updated_at->format('d F Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <h5><i class="icon fas fa-info"></i> {{ __('admin.security_tips') }}</h5>
                                    <ul class="mb-0">
                                        <li>{{ __('admin.security_tip_1') }}</li>
                                        <li>{{ __('admin.security_tip_2') }}</li>
                                        <li>{{ __('admin.security_tip_3') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.recent_activity') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="time-label">
                                <span class="bg-green">{{ $user->updated_at->format('d M Y') }}</span>
                            </div>
                            <div>
                                <i class="fas fa-user bg-blue"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> {{ $user->updated_at->format('H:i') }}</span>
                                    <h3 class="timeline-header">{{ __('admin.profile_updated') }}</h3>
                                    <div class="timeline-body">
                                        {{ __('admin.profile_last_updated_on') }} {{ $user->updated_at->format('d F Y H:i') }}
                                    </div>
                                </div>
                            </div>
                            <div>
                                <i class="fas fa-plus bg-green"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> {{ $user->created_at->format('H:i') }}</span>
                                    <h3 class="timeline-header">{{ __('admin.account_created') }}</h3>
                                    <div class="timeline-body">
                                        {{ __('admin.account_created_on') }} {{ $user->created_at->format('d F Y H:i') }}
                                    </div>
                                </div>
                            </div>
                            <div>
                                <i class="fas fa-clock bg-gray"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.profile-user-img {
    border: 3px solid #adb5bd;
    margin: 0 auto;
    padding: 3px;
}

.form-control-static {
    min-height: 34px;
    padding-top: 7px;
    padding-bottom: 7px;
    margin-bottom: 0;
}

.timeline {
    position: relative;
    margin: 0 0 30px 0;
    padding: 0;
    list-style: none;
}

.timeline:before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 31px;
    width: 4px;
    background: #dee2e6;
}

.timeline > div {
    position: relative;
}

.timeline > div > .timeline-item {
    box-shadow: 0 1px 3px rgba(0,0,0,.12), 0 1px 2px rgba(0,0,0,.24);
    border-radius: 3px;
    margin-top: 0;
    background: #fff;
    color: #444;
    margin-left: 60px;
    margin-right: 15px;
    margin-bottom: 15px;
    padding: 0;
}

.timeline > div > .timeline-item > .time {
    color: #999;
    float: right;
    padding: 10px;
    font-size: 12px;
}

.timeline > div > .timeline-item > .timeline-header {
    margin: 0;
    color: #555;
    border-bottom: 1px solid #f4f4f4;
    padding: 10px;
    font-size: 16px;
    line-height: 1.1;
}

.timeline > div > .timeline-item > .timeline-body,
.timeline > div > .timeline-item > .timeline-footer {
    padding: 10px;
}

.timeline > div > .fas {
    width: 30px;
    height: 30px;
    font-size: 15px;
    line-height: 30px;
    position: absolute;
    color: #666;
    background: #d2d6de;
    border-radius: 50%;
    text-align: center;
    left: 18px;
    top: 0;
}

.timeline > .time-label > span {
    font-weight: 600;
    color: #fff;
    font-size: 12px;
    padding: 5px 10px;
    display: inline-block;
    border-radius: 4px;
}
</style>
@endpush
