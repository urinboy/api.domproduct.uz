@extends('admin.layouts.app')

@section('title', __('admin.edit_category'))

@push('styles')
<style>
    .image-preview {
        width: 150px;
        height: 150px;
        border-radius: 0.375rem;
        object-fit: cover;
        border: 3px solid #ddd;
    }
    .image-placeholder {
        width: 150px;
        height: 150px;
        border-radius: 0.375rem;
        background: #f8f9fa;
        border: 3px dashed #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    .image-placeholder:hover {
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
    .nav-tabs .nav-link.active {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('admin.edit_category') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">{{ __('admin.categories') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories.show', $category) }}">{{ $category->getName() }}</a></li>
                    <li class="breadcrumb-item active">{{ __('admin.edit') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
        <!-- Main Information -->
        <div class="col-md-8">
            <!-- Basic Information -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.basic_information') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="parent_id">{{ __('admin.parent_category') }}</label>
                                <select class="form-control @error('parent_id') is-invalid @enderror"
                                        id="parent_id" name="parent_id">
                                    <option value="">{{ __('admin.select_parent_category') }}</option>
                                    @foreach($parentCategories as $parent)
                                        <option value="{{ $parent->id }}"
                                                {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->getName() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sort_order">{{ __('admin.sort_order') }}</label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                       id="sort_order" name="sort_order"
                                       value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0">
                                @error('sort_order')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">{{ __('admin.sort_order_hint') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="icon">{{ __('admin.icon') }}</label>
                        <input type="text" class="form-control @error('icon') is-invalid @enderror"
                               id="icon" name="icon" value="{{ old('icon', $category->icon) }}"
                               placeholder="{{ __('admin.icon_placeholder') }}">
                        @error('icon')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">{{ __('admin.icon_hint') }}</small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active"
                                   name="is_active" value="1"
                                   {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">{{ __('admin.active') }}</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Translations -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.translations') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <ul class="nav nav-tabs" id="languageTabs" role="tablist">
                                @foreach($languages as $index => $language)
                                    @php
                                        $translation = $category->translations->where('language_id', $language->id)->first();
                                    @endphp
                                    <li class="nav-item">
                                        <a class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                           id="tab-{{ $language->code }}" data-toggle="tab"
                                           href="#lang-{{ $language->code }}" role="tab">
                                            <img src="{{ $language->flag_url ?? '/images/flags/' . $language->code . '.png' }}"
                                                 alt="{{ $language->name }}" class="flag-icon mr-1">
                                            {{ $language->name }}
                                            @if($language->is_default)
                                                <span class="badge badge-primary badge-sm ml-1">{{ __('admin.default') }}</span>
                                            @endif
                                            @if($translation)
                                                <i class="fas fa-check text-success ml-1"></i>
                                            @else
                                                <i class="fas fa-times text-danger ml-1"></i>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content mt-3">
                                @foreach($languages as $index => $language)
                                    @php
                                        $translation = $category->translations->where('language_id', $language->id)->first();
                                    @endphp
                                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                         id="lang-{{ $language->code }}" role="tabpanel">
                                        <div class="form-group">
                                            <label for="name_{{ $language->code }}">
                                                {{ __('admin.name') }} ({{ $language->name }})
                                                @if($language->is_default)
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            <input type="text"
                                                   class="form-control @error('translations.' . $language->code . '.name') is-invalid @enderror"
                                                   id="name_{{ $language->code }}"
                                                   name="translations[{{ $language->code }}][name]"
                                                   value="{{ old('translations.' . $language->code . '.name', $translation->name ?? '') }}"
                                                   {{ $language->is_default ? 'required' : '' }}>
                                            @error('translations.' . $language->code . '.name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="description_{{ $language->code }}">
                                                {{ __('admin.description') }} ({{ $language->name }})
                                            </label>
                                            <textarea class="form-control @error('translations.' . $language->code . '.description') is-invalid @enderror"
                                                      id="description_{{ $language->code }}"
                                                      name="translations[{{ $language->code }}][description]"
                                                      rows="4">{{ old('translations.' . $language->code . '.description', $translation->description ?? '') }}</textarea>
                                            @error('translations.' . $language->code . '.description')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="slug_{{ $language->code }}">
                                                {{ __('admin.slug') }} ({{ $language->code }})
                                            </label>
                                            <input type="text"
                                                   class="form-control @error('translations.' . $language->code . '.slug') is-invalid @enderror"
                                                   id="slug_{{ $language->code }}"
                                                   name="translations[{{ $language->code }}][slug]"
                                                   value="{{ old('translations.' . $language->code . '.slug', $translation->slug ?? '') }}">
                                            @error('translations.' . $language->code . '.slug')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                            <small class="form-text text-muted">{{ __('admin.slug_auto_generate_hint') }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Current Image -->
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.current_image') }}</h3>
                </div>
                <div class="card-body text-center">
                    <img src="{{ $category->getImageUrl('medium') }}"
                         alt="{{ $category->getName() }}"
                         class="img-fluid rounded current-image mb-3"
                         style="max-height: 200px;">

                    <div class="form-group">
                        <label for="image">{{ __('admin.upload_new_image') }}</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('image') is-invalid @enderror"
                                       id="image" name="image" accept="image/*">
                                <label class="custom-file-label" for="image">{{ __('admin.choose_file') }}</label>
                            </div>
                        </div>
                        @error('image')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">{{ __('admin.category_image_upload_hint') }}</small>
                    </div>

                    <div id="image-preview" class="mt-3" style="display: none;">
                        <label>{{ __('admin.new_image_preview') }}:</label>
                        <div>
                            <img id="preview-img" src="#" alt="Preview" class="img-fluid rounded"
                                 style="max-height: 200px;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview -->
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.category_preview') }}</h3>
                </div>
                <div class="card-body">
                    <div class="category-preview-card">
                        <div class="text-center mb-3">
                            <div id="preview-icon" class="category-icon mb-2"
                                 style="{{ $category->icon ? '' : 'display: none;' }}">
                                <i class="{{ $category->icon ?? 'fas fa-folder' }} fa-2x text-primary"></i>
                            </div>
                        </div>
                        <h5 id="preview-name" class="text-center">{{ $category->getName() }}</h5>
                        <p id="preview-description" class="text-center text-muted small">
                            {{ $category->translations->first()->description ?? __('admin.category_description') }}
                        </p>
                        <div class="text-center">
                            <span id="preview-status" class="badge {{ $category->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $category->is_active ? __('admin.active') : __('admin.inactive') }}
                            </span>
                            <span id="preview-parent" class="badge badge-info ml-1"
                                  style="{{ $category->parent ? '' : 'display: none;' }}">
                                {{ $category->parent ? __('admin.child_of') . ': ' . $category->parent->getName() : '' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Info -->
            @if($category->children->count() > 0 || $category->products->count() > 0)
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.important_info') }}</h3>
                </div>
                <div class="card-body">
                    @if($category->children->count() > 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            {{ __('admin.category_has_children_warning', ['count' => $category->children->count()]) }}
                        </div>
                    @endif

                    @if($category->products->count() > 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ __('admin.category_has_products_warning', ['count' => $category->products->count()]) }}
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save"></i> {{ __('admin.update_category') }}
                    </button>
                    <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-info btn-block">
                        <i class="fas fa-eye"></i> {{ __('admin.view_category') }}
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> {{ __('admin.back_to_list') }}
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
.flag-icon {
    width: 16px;
    height: 12px;
    object-fit: cover;
}
.category-preview-card {
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    padding: 1rem;
    background: #f8f9fa;
}
.category-icon {
    display: inline-block;
}
.current-image {
    border: 1px solid #dee2e6;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Image preview
    $('#image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-img').attr('src', e.target.result);
                $('#image-preview').show();
            }
            reader.readAsDataURL(file);

            // Update custom file label
            $('.custom-file-label').text(file.name);
        } else {
            $('#image-preview').hide();
        }
    });

    // Auto-generate slug from name
    $('input[name*="[name]"]').on('input', function() {
        const $this = $(this);
        const lang = $this.attr('name').match(/\[(\w+)\]/)[1];
        const name = $this.val();
        const slug = name.toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');

        $(`input[name="translations[${lang}][slug]"]`).val(slug);

        // Update preview if it's the active tab
        if ($(`#lang-${lang}`).hasClass('active')) {
            const previewName = name || '{{ __('admin.category_name') }}';
            $('#preview-name').text(previewName);
        }
    });

    // Live preview for description
    $('textarea[name*="[description]"]').on('input', function() {
        const $this = $(this);
        const lang = $this.attr('name').match(/\[(\w+)\]/)[1];
        const description = $this.val();

        // Update preview if it's the active tab
        if ($(`#lang-${lang}`).hasClass('active')) {
            const previewDescription = description || '{{ __('admin.category_description') }}';
            $('#preview-description').text(previewDescription);
        }
    });

    // Update preview when switching tabs
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        const lang = $(e.target).attr('href').replace('#lang-', '');
        const name = $(`input[name="translations[${lang}][name]"]`).val() || '{{ __('admin.category_name') }}';
        const description = $(`textarea[name="translations[${lang}][description]"]`).val() || '{{ __('admin.category_description') }}';

        $('#preview-name').text(name);
        $('#preview-description').text(description);
    });

    // Icon preview
    $('#icon').on('input', function() {
        const iconClass = $(this).val();
        if (iconClass) {
            $('#preview-icon').show().find('i').attr('class', iconClass + ' fa-2x text-primary');
        } else {
            $('#preview-icon').hide();
        }
    });

    // Parent category preview
    $('#parent_id').change(function() {
        const selectedText = $(this).find('option:selected').text();
        if ($(this).val()) {
            $('#preview-parent').text('{{ __('admin.child_of') }}: ' + selectedText).show();
        } else {
            $('#preview-parent').hide();
        }
    });

    // Status preview
    $('#is_active').change(function() {
        if ($(this).is(':checked')) {
            $('#preview-status').removeClass('badge-danger').addClass('badge-success')
                               .text('{{ __('admin.active') }}');
        } else {
            $('#preview-status').removeClass('badge-success').addClass('badge-danger')
                               .text('{{ __('admin.inactive') }}');
        }
    });

    // Initialize preview with current values
    const currentLang = $('.nav-link.active').attr('href').replace('#lang-', '');
    const currentName = $(`input[name="translations[${currentLang}][name]"]`).val();
    const currentDescription = $(`textarea[name="translations[${currentLang}][description]"]`).val();

    if (currentName) $('#preview-name').text(currentName);
    if (currentDescription) $('#preview-description').text(currentDescription);
});
</script>
@endpush
