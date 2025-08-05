@extends('admin.layouts.app')

@section('title', __('admin.edit_profile'))

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('admin.edit_profile') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.profile.show') }}">{{ __('admin.profile') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('admin.edit_profile') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.edit_profile') }}</h3>
                    </div>

                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <!-- Avatar Section -->
                            <div class="form-group text-center">
                                <label for="avatar">{{ __('admin.avatar') }}</label>
                                <div class="mb-3">
                                    <img id="avatar-preview"
                                         src="{{ auth()->user()->avatar ? auth()->user()->avatar : asset('images/default-avatar.png') }}"
                                         alt="{{ __('admin.avatar') }}"
                                         class="img-circle elevation-2"
                                         style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file"
                                               class="custom-file-input @error('avatar') is-invalid @enderror"
                                               id="avatar"
                                               name="avatar"
                                               accept="image/*">
                                        <label class="custom-file-label" for="avatar">{{ __('admin.choose_file') }}</label>
                                    </div>
                                </div>
                                @error('avatar')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">{{ __('admin.max_file_size') }}: 2MB</small>
                            </div>

                            <!-- Basic Information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">{{ __('admin.name') }} <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               id="name"
                                               name="name"
                                               value="{{ old('name', $user->name) }}"
                                               required>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">{{ __('admin.email') }} <span class="text-danger">*</span></label>
                                        <input type="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               id="email"
                                               name="email"
                                               value="{{ old('email', $user->email) }}"
                                               required>
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">{{ __('admin.first_name') }}</label>
                                        <input type="text"
                                               class="form-control @error('first_name') is-invalid @enderror"
                                               id="first_name"
                                               name="first_name"
                                               value="{{ old('first_name', $user->first_name) }}">
                                        @error('first_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name">{{ __('admin.last_name') }}</label>
                                        <input type="text"
                                               class="form-control @error('last_name') is-invalid @enderror"
                                               id="last_name"
                                               name="last_name"
                                               value="{{ old('last_name', $user->last_name) }}">
                                        @error('last_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">{{ __('admin.phone') }}</label>
                                        <input type="text"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               id="phone"
                                               name="phone"
                                               value="{{ old('phone', $user->phone) }}">
                                        @error('phone')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Password Section -->
                            <h5>{{ __('admin.change_password') }}</h5>
                            <p class="text-muted">{{ __('admin.leave_blank_to_keep_current') }}</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="current_password">{{ __('admin.current_password') }}</label>
                                        <input type="password"
                                               class="form-control @error('current_password') is-invalid @enderror"
                                               id="current_password"
                                               name="current_password">
                                        @error('current_password')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">{{ __('admin.new_password') }}</label>
                                        <input type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               id="password"
                                               name="password">
                                        @error('password')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation">{{ __('admin.confirm_password') }}</label>
                                        <input type="password"
                                               class="form-control"
                                               id="password_confirmation"
                                               name="password_confirmation">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('admin.save_changes') }}
                            </button>
                            <a href="{{ route('admin.profile.show') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> {{ __('admin.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Profile Info Card -->
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.profile_info') }}</h3>
                    </div>
                    <div class="card-body">
                        <strong><i class="fas fa-user mr-1"></i> {{ __('admin.full_name') }}</strong>
                        <p class="text-muted">
                            {{ $user->first_name && $user->last_name ? $user->first_name . ' ' . $user->last_name : $user->name }}
                        </p>

                        <hr>

                        <strong><i class="fas fa-envelope mr-1"></i> {{ __('admin.email') }}</strong>
                        <p class="text-muted">{{ $user->email }}</p>

                        <hr>

                        <strong><i class="fas fa-phone mr-1"></i> {{ __('admin.phone') }}</strong>
                        <p class="text-muted">{{ $user->phone ?: __('admin.not_specified') }}</p>

                        <hr>

                        <strong><i class="fas fa-calendar mr-1"></i> {{ __('admin.member_since') }}</strong>
                        <p class="text-muted">{{ $user->created_at->format('d M Y') }}</p>

                        <hr>

                        <strong><i class="fas fa-clock mr-1"></i> {{ __('admin.last_updated') }}</strong>
                        <p class="text-muted">{{ $user->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // File input change event
    $('#avatar').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#avatar-preview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);

            // Update file label
            const fileName = file.name.length > 20 ? file.name.substring(0, 20) + '...' : file.name;
            $(this).next('.custom-file-label').text(fileName);
        }
    });

    // Password fields toggle
    $('#password, #password_confirmation').on('input', function() {
        if ($('#password').val() || $('#password_confirmation').val()) {
            $('#current_password').prop('required', true);
        } else {
            $('#current_password').prop('required', false);
        }
    });
});
</script>
@endpush
