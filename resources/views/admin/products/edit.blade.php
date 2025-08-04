@extends('admin.layouts.app')

@section('title', __('admin.edit_product'))

@section('content')
<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('admin.edit_product') }}:
                    @foreach($product->translations as $translation)
                        @if($translation->language_id == 1)
                            {{ $translation->name }}
                        @endif
                    @endforeach
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">{{ __('admin.products') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('admin.edit_product') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

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
                                               value="{{ old('sku', $product->sku) }}" required>
                                        @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="barcode">{{ __('admin.barcode') }}</label>
                                        <input type="text" name="barcode" id="barcode" class="form-control @error('barcode') is-invalid @enderror"
                                               value="{{ old('barcode', $product->barcode) }}">
                                        @error('barcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Translations -->
                            <div class="form-group">
                                <label>{{ __('admin.translations') }} <span class="text-danger">*</span></label>

                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" id="translationTabs" role="tablist">
                                    @foreach($languages ?? [['id' => 1, 'code' => 'uz', 'name' => 'O\'zbek'], ['id' => 2, 'code' => 'en', 'name' => 'English'], ['id' => 3, 'code' => 'ru', 'name' => 'Русский']] as $index => $language)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                           id="tab-{{ $language['code'] }}"
                                           data-toggle="tab"
                                           href="#translation-{{ $language['code'] }}"
                                           role="tab">
                                            {{ $language['name'] }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>

                                <!-- Tab content -->
                                <div class="tab-content border border-top-0 p-3">
                                    @foreach($languages ?? [['id' => 1, 'code' => 'uz', 'name' => 'O\'zbek'], ['id' => 2, 'code' => 'en', 'name' => 'English'], ['id' => 3, 'code' => 'ru', 'name' => 'Русский']] as $index => $language)
                                    @php
                                        $existingTranslation = $product->translations->where('language_id', $language['id'])->first();
                                    @endphp
                                    <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"
                                         id="translation-{{ $language['code'] }}"
                                         role="tabpanel">
                                        <input type="hidden" name="translations[{{ $index }}][language_id]" value="{{ $language['id'] }}">
                                        @if($existingTranslation)
                                            <input type="hidden" name="translations[{{ $index }}][id]" value="{{ $existingTranslation->id }}">
                                        @endif

                                        <div class="form-group">
                                            <label for="name_{{ $language['code'] }}">{{ __('admin.name') }} ({{ $language['name'] }})
                                                @if($index == 0)<span class="text-danger">*</span>@endif
                                            </label>
                                            <input type="text"
                                                   name="translations[{{ $index }}][name]"
                                                   id="name_{{ $language['code'] }}"
                                                   class="form-control @error('translations.'.$index.'.name') is-invalid @enderror"
                                                   value="{{ old('translations.'.$index.'.name', $existingTranslation->name ?? '') }}"
                                                   {{ $index == 0 ? 'required' : '' }}>
                                            @error('translations.'.$index.'.name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="description_{{ $language['code'] }}">{{ __('admin.description') }} ({{ $language['name'] }})</label>
                                            <textarea name="translations[{{ $index }}][description]"
                                                      id="description_{{ $language['code'] }}"
                                                      class="form-control @error('translations.'.$index.'.description') is-invalid @enderror"
                                                      rows="3">{{ old('translations.'.$index.'.description', $existingTranslation->description ?? '') }}</textarea>
                                            @error('translations.'.$index.'.description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Pricing -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">{{ __('admin.price') }} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="price" id="price"
                                                   class="form-control @error('price') is-invalid @enderror"
                                                   value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text">UZS</span>
                                            </div>
                                        </div>
                                        @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sale_price">{{ __('admin.sale_price') }}</label>
                                        <div class="input-group">
                                            <input type="number" name="sale_price" id="sale_price"
                                                   class="form-control @error('sale_price') is-invalid @enderror"
                                                   value="{{ old('sale_price', $product->sale_price) }}" step="0.01" min="0">
                                            <div class="input-group-append">
                                                <span class="input-group-text">UZS</span>
                                            </div>
                                        </div>
                                        @error('sale_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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
                                               value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0">
                                        @error('stock_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="stock_status">{{ __('admin.stock_status') }}</label>
                                        <select name="stock_status" id="stock_status" class="form-control @error('stock_status') is-invalid @enderror">
                                            <option value="in_stock" {{ old('stock_status', $product->stock_status) == 'in_stock' ? 'selected' : '' }}>{{ __('admin.in_stock') }}</option>
                                            <option value="low_stock" {{ old('stock_status', $product->stock_status) == 'low_stock' ? 'selected' : '' }}>{{ __('admin.low_stock') }}</option>
                                            <option value="out_of_stock" {{ old('stock_status', $product->stock_status) == 'out_of_stock' ? 'selected' : '' }}>{{ __('admin.out_of_stock') }}</option>
                                        </select>
                                        @error('stock_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Images -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('admin.product_images') }}</h3>
                        </div>
                        <div class="card-body">
                            <!-- Existing Images -->
                            @if($product->images && count($product->images) > 0)
                            <div class="form-group">
                                <label>{{ __('admin.existing_images') }}</label>
                                <div class="row" id="existingImages">
                                    @foreach($product->images as $image)
                                    <div class="col-md-3 mb-3" id="image-{{ $image->id }}">
                                        <div class="card">
                                            <img src="{{ Storage::url($image->path) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                            <div class="card-body p-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="main_image" value="{{ $image->id }}"
                                                               class="custom-control-input main-image-radio"
                                                               id="main-{{ $image->id }}"
                                                               {{ $image->is_main ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="main-{{ $image->id }}">
                                                            <small>{{ __('admin.main') }}</small>
                                                        </label>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-danger delete-image"
                                                            data-image-id="{{ $image->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- New Images Upload -->
                            <div class="form-group">
                                <label for="images">{{ __('admin.add_new_images') }}</label>
                                <input type="file" name="images[]" id="images"
                                       class="form-control @error('images') is-invalid @enderror"
                                       multiple accept="image/*">
                                <small class="form-text text-muted">
                                    {{ __('admin.images_help') }}
                                </small>
                                @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- New Image Preview -->
                            <div id="imagePreview" class="row" style="display: none;">
                                <!-- Images will be dynamically added here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
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
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                <label for="status">{{ __('admin.status') }}</label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                                    <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="is_featured" id="is_featured"
                                           class="custom-control-input" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_featured">
                                        {{ __('admin.featured_product') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('admin.update_product') }}
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> {{ __('admin.cancel') }}
                            </a>
                        </div>
                    </div>

                    <!-- Product Statistics -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('admin.statistics') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-info"><i class="fas fa-eye"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('admin.views') }}</span>
                                    <span class="info-box-number">{{ $product->views ?? 0 }}</span>
                                </div>
                            </div>

                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-calendar"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('admin.created') }}</span>
                                    <span class="info-box-number">{{ $product->created_at->format('d.m.Y') }}</span>
                                </div>
                            </div>
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
    // Image preview functionality for new images
    $('#images').change(function() {
        const files = this.files;
        const preview = $('#imagePreview');
        preview.empty();

        if (files.length > 0) {
            preview.show();

            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.append(`
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <small class="text-muted">${file.name}</small>
                                    </div>
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

    // Handle main image selection (only one can be selected)
    $('.main-image-radio').change(function() {
        if ($(this).is(':checked')) {
            $('.main-image-radio').not(this).prop('checked', false);
        }
    });

    // Delete existing image
    $('.delete-image').click(function() {
        const imageId = $(this).data('image-id');

        if (confirm('{{ __("admin.confirm_delete_image") }}')) {
            // Add hidden input to mark image for deletion
            $('form').append(`<input type="hidden" name="delete_images[]" value="${imageId}">`);

            // Remove image from display
            $('#image-' + imageId).fadeOut(300, function() {
                $(this).remove();
            });
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
});
</script>
@endpush
