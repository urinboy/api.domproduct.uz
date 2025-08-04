@extends('admin.layouts.app')

@section('title', __('admin.edit_user'))

@push('styles')
<style>
    .avatar-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #ddd;
    }
    .avatar-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: #f8f9fa;
        border: 3px dashed #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    .avatar-placeholder:hover {
        border-color: #007bff;
        background: #e3f2fd;
    }
    .form-section {
        border: 1px solid #e9ecef;
        border-radius: 0.375rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .form-section h4 {
        margin-bottom: 1rem;
        color: #495057;
        border-bottom: 2px solid #007bff;
        padding-bottom: 0.5rem;
    }
    .current-avatar {
        position: relative;
    }
    .remove-avatar {
        position: absolute;
        top: -10px;
        right: -10px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('admin.edit_user') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">{{ __('admin.users') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('admin.edit_user') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.edit_user') }}: {{ $user->full_name }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info btn-sm mr-2">
                                <i class="fas fa-eye"></i> {{ __('admin.view_user') }}
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> {{ __('admin.back_to_users') }}
                            </a>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <!-- Basic Information Section -->
                            <div class="form-section">
                                <h4><i class="fas fa-user mr-2"></i>{{ __('admin.basic_information') }}</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">{{ __('admin.username') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                                   value="{{ old('name', $user->name) }}" placeholder="{{ __('admin.enter_username') }}" required>
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">{{ __('admin.email') }} <span class="text-danger">*</span></label>
                                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                                   value="{{ old('email', $user->email) }}" placeholder="{{ __('admin.enter_email') }}" required>
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
                                            <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                                   value="{{ old('first_name', $user->first_name) }}" placeholder="{{ __('admin.enter_first_name') }}">
                                            @error('first_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="last_name">{{ __('admin.last_name') }}</label>
                                            <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                                   value="{{ old('last_name', $user->last_name) }}" placeholder="{{ __('admin.enter_last_name') }}">
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
                                            <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
                                                   value="{{ old('phone', $user->phone) }}" placeholder="{{ __('admin.enter_phone') }}">
                                            @error('phone')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="birth_date">{{ __('admin.birth_date') }}</label>
                                            <input type="date" name="birth_date" id="birth_date" class="form-control @error('birth_date') is-invalid @enderror"
                                                   value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}">
                                            @error('birth_date')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="gender">{{ __('admin.gender') }}</label>
                                            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                                                <option value="">{{ __('admin.select_gender') }}</option>
                                                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>{{ __('admin.male') }}</option>
                                                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>{{ __('admin.female') }}</option>
                                                <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>{{ __('admin.other') }}</option>
                                            </select>
                                            @error('gender')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role">{{ __('admin.role') }} <span class="text-danger">*</span></label>
                                            <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                                                <option value="">{{ __('admin.select_role') }}</option>
                                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>{{ __('admin.admin') }}</option>
                                                <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>{{ __('admin.manager') }}</option>
                                                <option value="employee" {{ old('role', $user->role) == 'employee' ? 'selected' : '' }}>{{ __('admin.employee') }}</option>
                                                <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>{{ __('admin.customer') }}</option>
                                            </select>
                                            @error('role')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Security Section -->
                            <div class="form-section">
                                <h4><i class="fas fa-lock mr-2"></i>{{ __('admin.security_settings') }}</h4>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>{{ __('admin.password_change_note') }}
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">{{ __('admin.new_password') }}</label>
                                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                                   placeholder="{{ __('admin.enter_new_password') }}">
                                            @error('password')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                            <small class="form-text text-muted">{{ __('admin.password_requirements') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation">{{ __('admin.confirm_new_password') }}</label>
                                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                                                   placeholder="{{ __('admin.confirm_new_password') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Address Information Section -->
                            <div class="form-section">
                                <h4><i class="fas fa-map-marker-alt mr-2"></i>{{ __('admin.address_information') }}</h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="address">{{ __('admin.address') }}</label>
                                            <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror"
                                                      placeholder="{{ __('admin.enter_address') }}">{{ old('address', $user->address) }}</textarea>
                                            @error('address')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="city">{{ __('admin.city') }}</label>
                                            <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror"
                                                   value="{{ old('city', $user->city) }}" placeholder="{{ __('admin.enter_city') }}">
                                            @error('city')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="district">{{ __('admin.district') }}</label>
                                            <input type="text" name="district" id="district" class="form-control @error('district') is-invalid @enderror"
                                                   value="{{ old('district', $user->district) }}" placeholder="{{ __('admin.enter_district') }}">
                                            @error('district')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Avatar Section -->
                            <div class="form-section">
                                <h4><i class="fas fa-image mr-2"></i>{{ __('admin.avatar') }}</h4>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            @if($user->hasAvatar())
                                                <div class="current-avatar">
                                                    <img src="{{ $user->getAvatarUrl('medium') }}" alt="{{ $user->full_name }}" class="avatar-preview" id="current-avatar">
                                                    <button type="button" class="remove-avatar" onclick="removeCurrentAvatar()" title="{{ __('admin.remove_avatar') }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <input type="hidden" name="remove_avatar" id="remove_avatar_flag" value="0">
                                            @else
                                                <div class="avatar-placeholder" onclick="document.getElementById('avatar').click()">
                                                    <i class="fas fa-camera fa-2x text-muted"></i>
                                                    <br><small>{{ __('admin.upload_avatar') }}</small>
                                                </div>
                                            @endif
                                            <img id="avatar-preview" class="avatar-preview" style="display: none;">
                                            <p class="mt-2 text-sm text-muted">{{ __('admin.click_to_upload') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="file" name="avatar" id="avatar" class="d-none @error('avatar') is-invalid @enderror"
                                               accept="image/*" onchange="previewAvatar(this)">
                                        @error('avatar')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                {{ __('admin.avatar_requirements') }}
                                            </small>
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="document.getElementById('avatar').click()">
                                            <i class="fas fa-upload mr-2"></i>{{ __('admin.choose_new_avatar') }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Section -->
                            <div class="form-section">
                                <h4><i class="fas fa-toggle-on mr-2"></i>{{ __('admin.status_settings') }}</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
                                                       {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    {{ __('admin.active_user') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="checkbox" name="email_verified" id="email_verified" class="form-check-input" value="1"
                                                       {{ old('email_verified', $user->email_verified) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="email_verified">
                                                    {{ __('admin.email_verified') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="checkbox" name="phone_verified" id="phone_verified" class="form-check-input" value="1"
                                                       {{ old('phone_verified', $user->phone_verified) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="phone_verified">
                                                    {{ __('admin.phone_verified') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-2"></i>{{ __('admin.update_user') }}
                                    </button>
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary ml-2">
                                        <i class="fas fa-times mr-2"></i>{{ __('admin.cancel') }}
                                    </a>
                                </div>
                                <div class="col-md-6 text-right">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        {{ __('admin.last_updated') }}: {{ $user->updated_at->format('d.m.Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            const currentAvatar = document.getElementById('current-avatar');
            const placeholder = document.querySelector('.avatar-placeholder');

            preview.src = e.target.result;
            preview.style.display = 'block';

            if (currentAvatar) {
                currentAvatar.style.display = 'none';
            }
            if (placeholder) {
                placeholder.style.display = 'none';
            }
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function removeCurrentAvatar() {
    if (confirm('{{ __("admin.confirm_remove_avatar") }}')) {
        document.getElementById('remove_avatar_flag').value = '1';
        document.getElementById('current-avatar').style.display = 'none';
        document.querySelector('.current-avatar').style.display = 'none';

        // Show placeholder
        const placeholder = document.createElement('div');
        placeholder.className = 'avatar-placeholder';
        placeholder.onclick = function() { document.getElementById('avatar').click(); };
        placeholder.innerHTML = '<i class="fas fa-camera fa-2x text-muted"></i>';
        document.querySelector('.text-center').prepend(placeholder);
    }
}

$(document).ready(function() {
    // Form validation
    $('form').on('submit', function(e) {
        const password = $('#password').val();
        const confirmPassword = $('#password_confirmation').val();

        if (password && confirmPassword) {
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('{{ __("admin.passwords_do_not_match") }}');
                return false;
            }

            if (password.length < 8) {
                e.preventDefault();
                alert('{{ __("admin.password_too_short") }}');
                return false;
            }
        }
    });

    // Real-time password confirmation
    $('#password_confirmation').on('keyup', function() {
        const password = $('#password').val();
        const confirmPassword = $(this).val();

        if (password && confirmPassword) {
            if (password === confirmPassword) {
                $(this).removeClass('is-invalid').addClass('is-valid');
            } else {
                $(this).removeClass('is-valid').addClass('is-invalid');
            }
        }
    });
});
</script>
@endpush
