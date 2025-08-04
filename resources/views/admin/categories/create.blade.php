@extends('admin.layouts.app')

@section('title', __('admin.add_category'))
@section('page-title', __('admin.add_category'))

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">{{ __('admin.categories') }}</a></li>
<li class="breadcrumb-item active">{{ __('admin.add_category') }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ __('admin.category_information') }}</h3>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">{{ __('admin.name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">{{ __('admin.description') }}</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="image">{{ __('admin.image') }}</label>
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
                        <small class="form-text text-muted">{{ __('admin.image_upload_hint') }}</small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">{{ __('admin.active') }}</label>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('admin.save') }}
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> {{ __('admin.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">{{ __('admin.preview') }}</h3>
            </div>
            <div class="card-body">
                <div id="image-preview" class="text-center mb-3">
                    <img id="preview-img" src="#" alt="Preview" class="img-fluid rounded" style="display: none; max-height: 200px;">
                    <div id="no-image" class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 200px;">
                        <i class="fas fa-image fa-3x text-muted"></i>
                    </div>
                </div>
                <div id="category-preview">
                    <h5 id="preview-name" class="text-muted">{{ __('admin.category_name') }}</h5>
                    <p id="preview-description" class="text-muted small">{{ __('admin.category_description') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Image preview
    $('#image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-img').attr('src', e.target.result).show();
                $('#no-image').hide();
            }
            reader.readAsDataURL(file);

            // Update custom file label
            $('.custom-file-label').text(file.name);
        }
    });

    // Live preview
    $('#name').on('input', function() {
        const value = $(this).val() || '{{ __('admin.category_name') }}';
        $('#preview-name').text(value);
    });

    $('#description').on('input', function() {
        const value = $(this).val() || '{{ __('admin.category_description') }}';
        $('#preview-description').text(value);
    });
});
</script>
@endpush
