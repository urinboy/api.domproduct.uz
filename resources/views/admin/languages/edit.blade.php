@extends('admin.layouts.app')

@section('title', __('admin.edit_language'))

@push('styles')
<style>
    .form-section {
        border: 1px solid #e9ecef;
        border-radius: 0.375rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #fff;
    }
    .form-section h4 {
        margin-bottom: 1rem;
        color: #495057;
        border-bottom: 2px solid #007bff;
        padding-bottom: 0.5rem;
        font-size: 1.1rem;
    }
    .info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .info-card h5 {
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    .flag-preview {
        font-size: 2rem;
        margin-right: 0.5rem;
    }
    .language-badge {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
        border-radius: 0.25rem;
        margin-right: 0.5rem;
    }
    .quick-suggestions {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1rem;
        margin-top: 1rem;
    }
    .suggestion-item {
        cursor: pointer;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        margin: 0.125rem;
        display: inline-block;
        background: #e9ecef;
        transition: all 0.2s;
    }
    .suggestion-item:hover {
        background: #007bff;
        color: white;
    }
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('admin.edit_language') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.languages.index') }}">{{ __('admin.languages') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('admin.edit_language') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <!-- Language Info Card -->
                <div class="info-card">
                    <div class="d-flex align-items-center">
                        <div class="flag-preview">
                            {{ $language->flag ?? '🏳️' }}
                        </div>
                        <div>
                            <h5>{{ $language->name }}</h5>
                            <div>
                                <span class="language-badge {{ $language->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $language->is_active ? __('admin.active') : __('admin.inactive') }}
                                </span>
                                @if($language->is_default)
                                    <span class="language-badge bg-warning">{{ __('admin.default') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.languages.update', $language) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information -->
                    <div class="form-section">
                        <h4><i class="fas fa-info-circle"></i> {{ __('admin.basic_information') }}</h4>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">{{ __('admin.name') }} <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $language->name) }}"
                                           placeholder="{{ __('admin.enter_language_name') }}"
                                           required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('admin.language_name_hint') }}</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code">{{ __('admin.code') }} <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('code') is-invalid @enderror"
                                           id="code"
                                           name="code"
                                           value="{{ old('code', $language->code) }}"
                                           placeholder="{{ __('admin.enter_language_code') }}"
                                           maxlength="5"
                                           required>
                                    @error('code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('admin.language_code_hint') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="flag">{{ __('admin.flag') }}</label>
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control @error('flag') is-invalid @enderror"
                                               id="flag"
                                               name="flag"
                                               value="{{ old('flag', $language->flag) }}"
                                               placeholder="{{ __('admin.enter_flag') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="flag-preview">
                                                {{ $language->flag ?? '🏳️' }}
                                            </span>
                                        </div>
                                    </div>
                                    @error('flag')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('admin.flag_hint') }}</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sort_order">{{ __('admin.sort_order') }}</label>
                                    <input type="number"
                                           class="form-control @error('sort_order') is-invalid @enderror"
                                           id="sort_order"
                                           name="sort_order"
                                           value="{{ old('sort_order', $language->sort_order) }}"
                                           min="0"
                                           step="1">
                                    @error('sort_order')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('admin.sort_order_hint') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings -->
                    <div class="form-section">
                        <h4><i class="fas fa-cogs"></i> {{ __('admin.settings') }}</h4>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="is_active"
                                               name="is_active"
                                               value="1"
                                               {{ old('is_active', $language->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">
                                            <strong>{{ __('admin.active') }}</strong>
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">{{ __('admin.language_active_hint') }}</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="is_default"
                                               name="is_default"
                                               value="1"
                                               {{ old('is_default', $language->is_default) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_default">
                                            <strong>{{ __('admin.default') }}</strong>
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">{{ __('admin.default_language_hint') }}</small>
                                </div>
                            </div>
                        </div>

                        @if($language->is_default)
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                {{ __('admin.this_is_default_language') }}
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-section">
                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ __('admin.update') }}
                                </button>
                                <a href="{{ route('admin.languages.index') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-arrow-left"></i> {{ __('admin.back') }}
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('admin.languages.show', $language) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i> {{ __('admin.view') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-4">
                <!-- Quick Info -->
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.quick_info') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-box-content">
                            <span class="info-box-text">{{ __('admin.created_at') }}</span>
                            <span class="info-box-number">{{ $language->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                        <hr>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ __('admin.updated_at') }}</span>
                            <span class="info-box-number">{{ $language->updated_at->format('d.m.Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Suggestions -->
                <div class="card card-secondary collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.quick_suggestions') }}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body" style="display: none;">
                        <div class="quick-suggestions">
                            <h6>{{ __('admin.common_language_codes') }}</h6>
                            <div class="suggestions-container">
                                <span class="suggestion-item" data-code="uz" data-flag="🇺🇿">uz - O'zbek</span>
                                <span class="suggestion-item" data-code="en" data-flag="🇺🇸">en - English</span>
                                <span class="suggestion-item" data-code="ru" data-flag="🇷🇺">ru - Русский</span>
                                <span class="suggestion-item" data-code="ar" data-flag="🇸🇦">ar - العربية</span>
                                <span class="suggestion-item" data-code="fr" data-flag="🇫🇷">fr - Français</span>
                                <span class="suggestion-item" data-code="de" data-flag="��">de - Deutsch</span>
                                <span class="suggestion-item" data-code="es" data-flag="��">es - Español</span>
                                <span class="suggestion-item" data-code="it" data-flag="��">it - Italiano</span>
                                <span class="suggestion-item" data-code="tr" data-flag="��">tr - Türkçe</span>
                                <span class="suggestion-item" data-code="zh" data-flag="��">zh - 中文</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('code');
    const flagInput = document.getElementById('flag');
    const flagPreview = document.getElementById('flag-preview');

    // Language mapping for auto-suggestions
    const languageMap = {
        'O\'zbek': { code: 'uz', flag: '🇺🇿' },
        'O\'zbekcha': { code: 'uz', flag: '🇺🇿' },
        'English': { code: 'en', flag: '🇺🇸' },
        'Русский': { code: 'ru', flag: '🇷🇺' },
        'العربية': { code: 'ar', flag: '🇸🇦' },
        'Français': { code: 'fr', flag: '🇫🇷' },
        'Deutsch': { code: 'de', flag: '🇩🇪' },
        'Español': { code: 'es', flag: '🇪🇸' },
        'Italiano': { code: 'it', flag: '🇮🇹' },
        'Türkçe': { code: 'tr', flag: '🇹🇷' },
        '中文': { code: 'zh', flag: '🇨🇳' }
    };

    // Auto-suggest based on language name (only for new suggestions)
    nameInput.addEventListener('input', function() {
        const name = this.value.trim();

        // Only suggest if fields are empty or very similar to suggestions
        const shouldSuggestCode = !codeInput.value || codeInput.value.length <= 2;
        const shouldSuggestFlag = !flagInput.value || flagInput.value.length <= 2;

        if (languageMap[name]) {
            if (shouldSuggestCode) {
                codeInput.value = languageMap[name].code;
            }
            if (shouldSuggestFlag) {
                flagInput.value = languageMap[name].flag;
                flagPreview.textContent = languageMap[name].flag;
            }
        }
    });

    // Format code input
    codeInput.addEventListener('input', function() {
        this.value = this.value.toLowerCase().replace(/[^a-z]/g, '');
    });

    // Update flag preview
    flagInput.addEventListener('input', function() {
        flagPreview.textContent = this.value || '🏳️';
    });

    // Quick suggestion clicks
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('suggestion-item')) {
            const code = e.target.dataset.code;
            const flag = e.target.dataset.flag;

            if (code && !codeInput.value) {
                codeInput.value = code;
            }
            if (flag && !flagInput.value) {
                flagInput.value = flag;
                flagPreview.textContent = flag;
            }
        }
    });

    // Default language checkbox logic
    const isDefaultCheckbox = document.getElementById('is_default');
    const isActiveCheckbox = document.getElementById('is_active');

    isDefaultCheckbox.addEventListener('change', function() {
        if (this.checked) {
            isActiveCheckbox.checked = true;
            isActiveCheckbox.disabled = true;
        } else {
            isActiveCheckbox.disabled = false;
        }
    });

    // Initialize default language logic
    if (isDefaultCheckbox.checked) {
        isActiveCheckbox.disabled = true;
    }
});
</script>
@endpush

@endsection
