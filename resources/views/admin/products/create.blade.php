@extends('admin.layouts.app')

@section('title', __('admin.add_product'))

@section('content')
<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('admin.add_product') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">{{ __('admin.products') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('admin.add_product') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Main Product Info -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('admin.product_information') }}</h3>
                        </div>
                        <div class="card-body">
                            <!-- SKU & Barcode -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sku">{{ __('admin.sku') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="sku" id="sku" class="form-control @error('sku') is-invalid @enderror"
                                               value="{{ old('sku') }}" required>
                                        @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="barcode">{{ __('admin.barcode') }}</label>
                                        <input type="text" name="barcode" id="barcode" class="form-control @error('barcode') is-invalid @enderror"
                                               value="{{ old('barcode') }}">
                                        @error('barcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Multilingual Fields -->
                            <div class="form-group">
                                <label>{{ __('admin.product_translations') }} <span class="text-danger">*</span></label>
                                <div class="card">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills" id="language-pills" role="tablist">
                                            @foreach($languages as $index => $language)
                                                <li class="nav-item">
                                                    <a class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                                       id="lang-{{ $language->code }}-tab"
                                                       data-toggle="pill"
                                                       href="#lang-{{ $language->code }}"
                                                       role="tab"
                                                       aria-controls="lang-{{ $language->code }}"
                                                       aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                                        <span class="flag-icon mr-1">{{ $language->flag ?? '🇺🇿' }}</span>
                                                        {{ $language->name }}
                                                        @if($language->is_default)
                                                            <span class="badge badge-primary badge-sm ml-1">{{ __('admin.default') }}</span>
                                                        @endif
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content" id="language-content">
                                            @foreach($languages as $index => $language)
                                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                                     id="lang-{{ $language->code }}"
                                                     role="tabpanel"
                                                     aria-labelledby="lang-{{ $language->code }}-tab">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="translations[{{ $language->id }}][name]">
                                                                    {{ __('admin.product_name') }}
                                                                    @if($language->is_default)
                                                                        <span class="text-danger">*</span>
                                                                    @endif
                                                                </label>
                                                                <input type="text"
                                                                       name="translations[{{ $language->id }}][name]"
                                                                       id="translations[{{ $language->id }}][name]"
                                                                       class="form-control @error('translations.'.$language->id.'.name') is-invalid @enderror"
                                                                       value="{{ old('translations.'.$language->id.'.name') }}"
                                                                       placeholder="{{ __('admin.enter_product_name') }}"
                                                                       {{ $language->is_default ? 'required' : '' }}>
                                                                @error('translations.'.$language->id.'.name')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="translations[{{ $language->id }}][slug]">
                                                                    {{ __('admin.slug') }}
                                                                </label>
                                                                <input type="text"
                                                                       name="translations[{{ $language->id }}][slug]"
                                                                       id="translations[{{ $language->id }}][slug]"
                                                                       class="form-control @error('translations.'.$language->id.'.slug') is-invalid @enderror"
                                                                       value="{{ old('translations.'.$language->id.'.slug') }}"
                                                                       placeholder="{{ __('admin.auto_generated') }}">
                                                                @error('translations.'.$language->id.'.slug')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="translations[{{ $language->id }}][description]">
                                                            {{ __('admin.description') }}
                                                        </label>
                                                        <textarea name="translations[{{ $language->id }}][description]"
                                                                  id="translations[{{ $language->id }}][description]"
                                                                  class="form-control @error('translations.'.$language->id.'.description') is-invalid @enderror"
                                                                  rows="4"
                                                                  placeholder="{{ __('admin.enter_product_description') }}">{{ old('translations.'.$language->id.'.description') }}</textarea>
                                                        @error('translations.'.$language->id.'.description')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pricing -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cost_price">{{ __('admin.cost_price') }}</label>
                                        <div class="input-group">
                                            <input type="number" name="cost_price" id="cost_price"
                                                   class="form-control @error('cost_price') is-invalid @enderror"
                                                   value="{{ old('cost_price') }}" step="0.01" min="0">
                                            <div class="input-group-append">
                                                <span class="input-group-text">UZS</span>
                                            </div>
                                        </div>
                                        @error('cost_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">{{ __('admin.cost_price_hint') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="price">{{ __('admin.price') }} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="price" id="price"
                                                   class="form-control @error('price') is-invalid @enderror"
                                                   value="{{ old('price') }}" step="0.01" min="0" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text">UZS</span>
                                            </div>
                                        </div>
                                        @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sale_price">{{ __('admin.sale_price') }}</label>
                                        <div class="input-group">
                                            <input type="number" name="sale_price" id="sale_price"
                                                   class="form-control @error('sale_price') is-invalid @enderror"
                                                   value="{{ old('sale_price') }}" step="0.01" min="0">
                                            <div class="input-group-append">
                                                <span class="input-group-text">UZS</span>
                                            </div>
                                        </div>
                                        @error('sale_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">{{ __('admin.sale_price_hint') }}</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Unit Information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="unit_type">{{ __('admin.unit_type') }} <span class="text-danger">*</span></label>
                                        <select name="unit_type" id="unit_type" class="form-control @error('unit_type') is-invalid @enderror" required>
                                            <option value="">{{ __('admin.select_unit_type') }}</option>
                                            @foreach(\App\Models\Product::getUnitTypes() as $key => $label)
                                                <option value="{{ $key }}" {{ old('unit_type') == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('unit_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="unit_value">{{ __('admin.unit_value') }}</label>
                                        <input type="number" name="unit_value" id="unit_value"
                                               class="form-control @error('unit_value') is-invalid @enderror"
                                               value="{{ old('unit_value', 1) }}" step="0.001" min="0.001">
                                        @error('unit_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">{{ __('admin.unit_value_hint') }}</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Stock Information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="stock_quantity">{{ __('admin.stock_quantity') }}</label>
                                        <input type="number" name="stock_quantity" id="stock_quantity"
                                               class="form-control @error('stock_quantity') is-invalid @enderror"
                                               value="{{ old('stock_quantity', 0) }}" min="0">
                                        @error('stock_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="stock_status">{{ __('admin.stock_status') }}</label>
                                        <select name="stock_status" id="stock_status" class="form-control @error('stock_status') is-invalid @enderror">
                                            <option value="in_stock" {{ old('stock_status') == 'in_stock' ? 'selected' : '' }}>{{ __('admin.in_stock') }}</option>
                                            <option value="low_stock" {{ old('stock_status') == 'low_stock' ? 'selected' : '' }}>{{ __('admin.low_stock') }}</option>
                                            <option value="out_of_stock" {{ old('stock_status') == 'out_of_stock' ? 'selected' : '' }}>{{ __('admin.out_of_stock') }}</option>
                                        </select>
                                        @error('stock_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Images Section -->
                <div class="col-md-4">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('admin.product_images') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="images">{{ __('admin.upload_images') }}</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="images[]" id="images"
                                               class="custom-file-input @error('images') is-invalid @enderror"
                                               multiple accept="image/*">
                                        <label class="custom-file-label" for="images">{{ __('admin.choose_files') }}</label>
                                    </div>
                                </div>
                                @error('images')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">{{ __('admin.images_help') }}</small>
                            </div>

                            <!-- Image Preview -->
                            <div id="imagePreview" class="row">
                                <div id="no-images" class="col-12 bg-light d-flex align-items-center justify-content-center rounded" style="height: 200px;">
                                    <div class="text-center">
                                        <i class="fas fa-images fa-3x text-muted mb-2"></i>
                                        <p class="text-muted">{{ __('admin.no_images_selected') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category & Status -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('admin.product_settings') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="category_id">{{ __('admin.category') }} <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                    <option value="">{{ __('admin.select_category') }}</option>
                                    @foreach($categories ?? [] as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            @foreach($category->translations as $translation)
                                                @if($translation->language_id == 1)
                                                    {{ $translation->name }}
                                                @endif
                                            @endforeach
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="is_active">{{ __('admin.status') }}</label>
                                <select name="is_active" id="is_active" class="form-control @error('is_active') is-invalid @enderror">
                                    <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                                    <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
                                </select>
                                @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="is_featured" id="is_featured"
                                           class="custom-control-input" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_featured">
                                        {{ __('admin.featured_product') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('admin.save_product') }}
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
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

@push('scripts')
<script>
$(document).ready(function() {
    // Custom file label update
    $('#images').on('change', function() {
        const files = this.files;
        const label = $(this).next('.custom-file-label');

        if (files.length === 0) {
            label.text('{{ __("admin.choose_files") }}');
            $('#imagePreview').html(`
                <div id="no-images" class="col-12 bg-light d-flex align-items-center justify-content-center rounded" style="height: 200px;">
                    <div class="text-center">
                        <i class="fas fa-images fa-3x text-muted mb-2"></i>
                        <p class="text-muted">{{ __('admin.no_images_selected') }}</p>
                    </div>
                </div>
            `);
        } else if (files.length === 1) {
            label.text(files[0].name);
            showImagePreviews(files);
        } else {
            label.text(files.length + ' {{ __("admin.files_selected") }}');
            showImagePreviews(files);
        }
    });

    function showImagePreviews(files) {
        const preview = $('#imagePreview');
        preview.empty();

        Array.from(files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.append(`
                        <div class="col-6 mb-2">
                            <div class="card">
                                <img src="${e.target.result}" class="card-img-top" style="height: 120px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <small class="text-muted text-truncate d-block">${file.name}</small>
                                </div>
                            </div>
                        </div>
                    `);
                };
                reader.readAsDataURL(file);
            }
        });
    }
                                </div>
                            </div>
                        `);
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            preview.hide();
        }
    });

    // Auto-generate SKU based on name
    $('input[name="translations[0][name]"]').on('input', function() {
        const name = $(this).val();
        if (name && !$('#sku').val()) {
            const sku = name.toUpperCase()
                .replace(/[^A-Z0-9\s]/g, '')
                .replace(/\s+/g, '-')
                .substring(0, 20);
            $('#sku').val(sku);
        }
    });

    // Price validation
    $('#sale_price').on('input', function() {
        const price = parseFloat($('#price').val()) || 0;
        const salePrice = parseFloat($(this).val()) || 0;

        if (salePrice > 0 && salePrice >= price) {
            alert('{{ __("admin.sale_price_validation") }}');
            $(this).val('');
        }
    });

    // Auto-generate slug from name
    $('input[name*="[name]"]').on('input', function() {
        const nameInput = $(this);
        const languageId = nameInput.attr('name').match(/\[(\d+)\]/)[1];
        const slugInput = $('input[name="translations[' + languageId + '][slug]"]');

        if (nameInput.val() && !slugInput.data('manually-changed')) {
            const slug = nameInput.val()
                .toLowerCase()
                .replace(/[^a-z0-9\u0400-\u04FF\u0100-\u017F]+/g, '-') // Cyrillic and Latin support
                .replace(/^-+|-+$/g, '');
            slugInput.val(slug);
        }
    });

    // Mark slug as manually changed when user types in it
    $('input[name*="[slug]"]').on('input', function() {
        $(this).data('manually-changed', true);
    });
});
</script>
@endpush
