@extends('admin.layouts.app')

@section('title', __('admin.settings'))

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('admin.settings') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('admin.settings') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- General Settings -->
                <div class="col-lg-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('admin.general_settings') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="site_name">{{ __('admin.site_name') }} <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('site_name') is-invalid @enderror"
                                       id="site_name"
                                       name="site_name"
                                       value="{{ old('site_name', $settings['site_name']) }}"
                                       required>
                                @error('site_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="site_description">{{ __('admin.site_description') }}</label>
                                <textarea class="form-control @error('site_description') is-invalid @enderror"
                                          id="site_description"
                                          name="site_description"
                                          rows="3">{{ old('site_description', $settings['site_description']) }}</textarea>
                                @error('site_description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="currency">{{ __('admin.currency') }} <span class="text-danger">*</span></label>
                                <select class="form-control @error('currency') is-invalid @enderror"
                                        id="currency"
                                        name="currency"
                                        required>
                                    <option value="UZS" {{ old('currency', $settings['currency']) == 'UZS' ? 'selected' : '' }}>
                                        {{ __('admin.uzs') }} (UZS)
                                    </option>
                                    <option value="USD" {{ old('currency', $settings['currency']) == 'USD' ? 'selected' : '' }}>
                                        {{ __('admin.usd') }} (USD)
                                    </option>
                                    <option value="EUR" {{ old('currency', $settings['currency']) == 'EUR' ? 'selected' : '' }}>
                                        {{ __('admin.eur') }} (EUR)
                                    </option>
                                </select>
                                @error('currency')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="language">{{ __('admin.default_language') }} <span class="text-danger">*</span></label>
                                <select class="form-control @error('language') is-invalid @enderror"
                                        id="language"
                                        name="language"
                                        required>
                                    <option value="uz" {{ old('language', $settings['language']) == 'uz' ? 'selected' : '' }}>
                                        {{ __('admin.uzbek') }}
                                    </option>
                                    <option value="ru" {{ old('language', $settings['language']) == 'ru' ? 'selected' : '' }}>
                                        {{ __('admin.russian') }}
                                    </option>
                                    <option value="en" {{ old('language', $settings['language']) == 'en' ? 'selected' : '' }}>
                                        {{ __('admin.english') }}
                                    </option>
                                </select>
                                @error('language')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="timezone">{{ __('admin.timezone') }} <span class="text-danger">*</span></label>
                                <select class="form-control @error('timezone') is-invalid @enderror"
                                        id="timezone"
                                        name="timezone"
                                        required>
                                    <option value="Asia/Tashkent" {{ old('timezone', $settings['timezone']) == 'Asia/Tashkent' ? 'selected' : '' }}>
                                        Asia/Tashkent (UTC+5)
                                    </option>
                                    <option value="Asia/Samarkand" {{ old('timezone', $settings['timezone']) == 'Asia/Samarkand' ? 'selected' : '' }}>
                                        Asia/Samarkand (UTC+5)
                                    </option>
                                    <option value="UTC" {{ old('timezone', $settings['timezone']) == 'UTC' ? 'selected' : '' }}>
                                        UTC (UTC+0)
                                    </option>
                                    <option value="Europe/Moscow" {{ old('timezone', $settings['timezone']) == 'Europe/Moscow' ? 'selected' : '' }}>
                                        Europe/Moscow (UTC+3)
                                    </option>
                                </select>
                                @error('timezone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="app_version">{{ __('admin.app_version') }}</label>
                                <input type="text"
                                       class="form-control @error('app_version') is-invalid @enderror"
                                       id="app_version"
                                       name="app_version"
                                       value="{{ old('app_version', $settings['app_version']) }}"
                                       placeholder="1.0.0">
                                @error('app_version')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">{{ __('admin.app_version_desc') }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- System Settings -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('admin.system_settings') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="maintenance_mode"
                                           name="maintenance_mode"
                                           value="1"
                                           {{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="maintenance_mode">
                                        {{ __('admin.maintenance_mode') }}
                                    </label>
                                </div>
                                <small class="text-muted">{{ __('admin.maintenance_mode_desc') }}</small>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="allow_registration"
                                           name="allow_registration"
                                           value="1"
                                           {{ old('allow_registration', $settings['allow_registration']) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="allow_registration">
                                        {{ __('admin.allow_registration') }}
                                    </label>
                                </div>
                                <small class="text-muted">{{ __('admin.allow_registration_desc') }}</small>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="email_verification"
                                           name="email_verification"
                                           value="1"
                                           {{ old('email_verification', $settings['email_verification']) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="email_verification">
                                        {{ __('admin.email_verification') }}
                                    </label>
                                </div>
                                <small class="text-muted">{{ __('admin.email_verification_desc') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Settings -->
                <div class="col-lg-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('admin.contact_settings') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="contact_email">{{ __('admin.contact_email') }} <span class="text-danger">*</span></label>
                                <input type="email"
                                       class="form-control @error('contact_email') is-invalid @enderror"
                                       id="contact_email"
                                       name="contact_email"
                                       value="{{ old('contact_email', $settings['contact_email']) }}"
                                       required>
                                @error('contact_email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="contact_phone">{{ __('admin.contact_phone') }}</label>
                                <input type="text"
                                       class="form-control @error('contact_phone') is-invalid @enderror"
                                       id="contact_phone"
                                       name="contact_phone"
                                       value="{{ old('contact_phone', $settings['contact_phone']) }}"
                                       placeholder="+998 90 123 45 67">
                                @error('contact_phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="address">{{ __('admin.address') }}</label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                          id="address"
                                          name="address"
                                          rows="3"
                                          placeholder="{{ __('admin.enter_address') }}">{{ old('address', $settings['address']) }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('admin.quick_actions') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-info btn-block" onclick="clearCache()">
                                        <i class="fas fa-sync"></i> {{ __('admin.clear_cache') }}
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-warning btn-block" onclick="optimizeApp()">
                                        <i class="fas fa-rocket"></i> {{ __('admin.optimize_app') }}
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{ route('admin.settings.backup') }}" class="btn btn-success btn-block">
                                        <i class="fas fa-download"></i> {{ __('admin.backup_data') }}
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-secondary btn-block" onclick="showSystemInfo()">
                                        <i class="fas fa-info-circle"></i> {{ __('admin.system_info') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Status -->
                    <div class="card card-light">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('admin.current_status') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-server"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('admin.app_version') }}</span>
                                    <span class="info-box-number">{{ $settings['app_version'] ?? config('app.version') }}</span>
                                </div>
                            </div>

                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('admin.total_users') }}</span>
                                    <span class="info-box-number">{{ \App\Models\User::count() }}</span>
                                </div>
                            </div>

                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-boxes"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('admin.total_products') }}</span>
                                    <span class="info-box-number">{{ \App\Models\Product::count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('admin.save_settings') }}
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-undo"></i> {{ __('admin.reset') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function clearCache() {
    if (confirm('{{ __("admin.confirm_clear_cache") }}')) {
        $.ajax({
            url: '{{ route("admin.settings.clear-cache") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $(document).Toasts('create', {
                    class: 'bg-success',
                    title: '{{ __("admin.success") }}',
                    body: response.message || '{{ __("admin.cache_cleared") }}',
                    autohide: true,
                    delay: 3000
                });
            },
            error: function() {
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: '{{ __("admin.error") }}',
                    body: '{{ __("admin.operation_failed") }}',
                    autohide: true,
                    delay: 3000
                });
            }
        });
    }
}

function optimizeApp() {
    if (confirm('{{ __("admin.confirm_optimize") }}')) {
        $.ajax({
            url: '{{ route("admin.settings.optimize") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $(document).Toasts('create', {
                    class: 'bg-success',
                    title: '{{ __("admin.success") }}',
                    body: response.message || '{{ __("admin.app_optimized") }}',
                    autohide: true,
                    delay: 3000
                });
            },
            error: function() {
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: '{{ __("admin.error") }}',
                    body: '{{ __("admin.operation_failed") }}',
                    autohide: true,
                    delay: 3000
                });
            }
        });
    }
}

function showSystemInfo() {
    $('#systemInfoModal').modal('show');
}
</script>
@endpush

@push('styles')
<style>
.info-box {
    margin-bottom: 15px;
}
</style>
@endpush
