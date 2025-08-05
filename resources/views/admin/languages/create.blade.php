@extends('admin.layouts.app')

@section('title', __('admin.add_language'))

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ __('admin.add_language') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.languages.index') }}">{{ __('admin.languages') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('admin.add_language') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.languages.store') }}" method="POST">
            @csrf
            <div class="row">
                <!-- Main Form -->
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('admin.basic_information') }}</h3>
                        </div>

                        <div class="card-body">
                            <!-- Language Name -->
                            <div class="form-group">
                                <label for="name" class="required">{{ __('admin.language_name') }}</label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name') }}"
                                       placeholder="{{ __('admin.enter_language_name') }}"
                                       required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">{{ __('admin.language_name_hint') }}</small>
                            </div>

                            <!-- Language Code -->
                            <div class="form-group">
                                <label for="code" class="required">{{ __('admin.language_code') }}</label>
                                <input type="text"
                                       class="form-control @error('code') is-invalid @enderror"
                                       id="code"
                                       name="code"
                                       value="{{ old('code') }}"
                                       placeholder="{{ __('admin.enter_language_code') }}"
                                       maxlength="5"
                                       required>
                                @error('code')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">{{ __('admin.language_code_hint') }}</small>
                            </div>

                            <!-- Flag -->
                            <div class="form-group">
                                <label for="flag">{{ __('admin.flag') }}</label>
                                <input type="text"
                                       class="form-control @error('flag') is-invalid @enderror"
                                       id="flag"
                                       name="flag"
                                       value="{{ old('flag') }}"
                                       placeholder="{{ __('admin.enter_flag') }}">
                                @error('flag')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">{{ __('admin.flag_hint') }}</small>
                            </div>

                            <!-- Sort Order -->
                            <div class="form-group">
                                <label for="sort_order">{{ __('admin.sort_order') }}</label>
                                <input type="number"
                                       class="form-control @error('sort_order') is-invalid @enderror"
                                       id="sort_order"
                                       name="sort_order"
                                       value="{{ old('sort_order', 0) }}"
                                       min="0">
                                @error('sort_order')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">{{ __('admin.sort_order_hint') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Sidebar -->
                <div class="col-md-4">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('admin.status_settings') }}</h3>
                        </div>

                        <div class="card-body">
                            <!-- Active Status -->
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="is_active"
                                           name="is_active"
                                           value="1"
                                           {{ old('is_active', 1) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">
                                        {{ __('admin.active') }}
                                    </label>
                                </div>
                                <small class="form-text text-muted">{{ __('admin.language_active_hint') }}</small>
                            </div>

                            <!-- Default Status -->
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="hidden" name="is_default" value="0">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="is_default"
                                           name="is_default"
                                           value="1"
                                           {{ old('is_default') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_default">
                                        {{ __('admin.default_language') }}
                                    </label>
                                </div>
                                <small class="form-text text-muted">{{ __('admin.default_language_hint') }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Info -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('admin.quick_info') }}</h3>
                        </div>

                        <div class="card-body">
                            <div class="info-box-content">
                                <div class="info-item">
                                    <strong>{{ __('admin.common_language_codes') }}:</strong>
                                    <ul class="mb-2">
                                        <li>uz - O'zbek</li>
                                        <li>ru - Русский</li>
                                        <li>en - English</li>
                                        <li>ar - العربية</li>
                                        <li>de - Deutsch</li>
                                        <li>fr - Français</li>
                                    </ul>
                                </div>
                                <div class="info-item">
                                    <strong>{{ __('admin.common_flags') }}:</strong>
                                    <ul class="mb-0">
                                        <li>🇺🇿 - O'zbekiston</li>
                                        <li>🇷🇺 - Rossiya</li>
                                        <li>🇺🇸 - USA</li>
                                        <li>🇬🇧 - UK</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('admin.save') }}
                            </button>
                            <a href="{{ route('admin.languages.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> {{ __('admin.cancel') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('styles')
<style>
.required::after {
    content: " *";
    color: red;
}

.info-item {
    margin-bottom: 1rem;
}

.info-item ul {
    font-size: 0.875rem;
    color: #6c757d;
}

.info-item ul li {
    margin-bottom: 0.25rem;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-generate code from name
    $('#name').on('input', function() {
        const name = $(this).val().toLowerCase();
        let code = '';

        // Simple mapping for common languages
        const languageMap = {
            'uzbek': 'uz',
            'o\'zbek': 'uz',
            'ozbek': 'uz',
            'russian': 'ru',
            'русский': 'ru',
            'english': 'en',
            'ingliz': 'en',
            'arabic': 'ar',
            'arab': 'ar',
            'german': 'de',
            'nemis': 'de',
            'french': 'fr',
            'fransuz': 'fr',
            'spanish': 'es',
            'ispan': 'es',
            'chinese': 'zh',
            'xitoy': 'zh',
            'japanese': 'ja',
            'yapon': 'ja',
            'korean': 'ko',
            'koreya': 'ko'
        };

        if (languageMap[name]) {
            code = languageMap[name];
        } else {
            // Take first two letters
            code = name.substring(0, 2);
        }

        // Only set if code field is empty
        if ($('#code').val() === '') {
            $('#code').val(code);
        }
    });

    // Auto-suggest flag based on language
    $('#name, #code').on('input', function() {
        const name = $('#name').val().toLowerCase();
        const code = $('#code').val().toLowerCase();

        const flagMap = {
            'uz': '🇺🇿',
            'uzbek': '🇺🇿',
            'ru': '🇷🇺',
            'russian': '🇷🇺',
            'en': '🇺🇸',
            'english': '🇺🇸',
            'ar': '🇸🇦',
            'arabic': '🇸🇦',
            'de': '🇩🇪',
            'german': '🇩🇪',
            'fr': '🇫🇷',
            'french': '🇫🇷',
            'es': '🇪🇸',
            'spanish': '🇪🇸',
            'zh': '🇨🇳',
            'chinese': '🇨🇳',
            'ja': '🇯🇵',
            'japanese': '🇯🇵',
            'ko': '🇰🇷',
            'korean': '🇰🇷'
        };

        let flag = flagMap[code] || flagMap[name];

        // Only set if flag field is empty
        if (flag && $('#flag').val() === '') {
            $('#flag').val(flag);
        }
    });
});
</script>
@endpush
