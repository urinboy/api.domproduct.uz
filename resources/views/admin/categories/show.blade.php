@extends('admin.layouts.app')

@section('title', __('admin.view_category'))

@push('styles')
<style>
    .category-image {
        width: 100%;
        max-width: 300px;
        border-radius: 0.375rem;
        object-fit: cover;
    }
    .language-tab {
        margin-bottom: 1rem;
    }
    .info-table th {
        width: 30%;
        color: #495057;
        font-weight: 600;
    }
    .status-badge {
        font-size: 0.875rem;
    }
    .hierarchy-list {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 0.375rem;
        border-left: 4px solid #007bff;
    }
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('admin.view_category') }}: {{ $category->getName() }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">{{ __('admin.categories') }}</a></li>
                    <li class="breadcrumb-item active">{{ $category->getName() }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
    <div class="col-md-8">
        <!-- Category Details -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ __('admin.category_details') }}</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> {{ __('admin.edit') }}
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> {{ __('admin.back') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th>{{ __('admin.id') }}:</th>
                                <td>{{ $category->id }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.parent_category') }}:</th>
                                <td>
                                    @if($category->parent)
                                        <a href="{{ route('admin.categories.show', $category->parent) }}" class="badge badge-info">
                                            {{ $category->parent->getName() }}
                                        </a>
                                    @else
                                        <span class="text-muted">{{ __('admin.root_category') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.sort_order') }}:</th>
                                <td><span class="badge badge-light">{{ $category->sort_order ?? 0 }}</span></td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.status') }}:</th>
                                <td>
                                    @if($category->is_active)
                                        <span class="badge badge-success">{{ __('admin.active') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('admin.inactive') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.children_count') }}:</th>
                                <td>
                                    <span class="badge badge-primary">{{ $category->children->count() }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.products_count') }}:</th>
                                <td>
                                    <span class="badge badge-success">{{ $category->products->count() }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th>{{ __('admin.created_at') }}:</th>
                                <td>{{ $category->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.updated_at') }}:</th>
                                <td>{{ $category->updated_at->format('d M Y H:i') }}</td>
                            </tr>
                            @if($category->icon)
                            <tr>
                                <th>{{ __('admin.icon') }}:</th>
                                <td>
                                    <i class="{{ $category->icon }} fa-lg text-primary"></i>
                                    <code class="ml-2">{{ $category->icon }}</code>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <th>{{ __('admin.depth_level') }}:</th>
                                <td><span class="badge badge-secondary">{{ $category->getDepth() }}</span></td>
                            </tr>
                            @if($category->getPath())
                            <tr>
                                <th>{{ __('admin.category_path') }}:</th>
                                <td>
                                    <small class="text-muted">{{ implode(' > ', $category->getPath()) }}</small>
                                </td>
                            </tr>
                            @endif
                        </table>
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
                @if($category->translations->count() > 0)
                    <div class="row">
                        @foreach($category->translations as $translation)
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <img src="{{ $translation->language->flag_url ?? '/images/flags/' . $translation->language->code . '.png' }}"
                                                 alt="{{ $translation->language->name }}" class="flag-icon mr-1">
                                            {{ $translation->language->name }}
                                            @if($translation->language->is_default)
                                                <span class="badge badge-primary badge-sm ml-1">{{ __('admin.default') }}</span>
                                            @endif
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <th>{{ __('admin.name') }}:</th>
                                                <td>{{ $translation->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('admin.slug') }}:</th>
                                                <td><code>{{ $translation->slug }}</code></td>
                                            </tr>
                                            @if($translation->description)
                                            <tr>
                                                <th>{{ __('admin.description') }}:</th>
                                                <td>{{ Str::limit($translation->description, 100) }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-language fa-2x text-muted mb-2"></i>
                        <p class="text-muted">{{ __('admin.no_translations_found') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Child Categories -->
        @if($category->children->count() > 0)
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">{{ __('admin.child_categories') }} ({{ $category->children->count() }})</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($category->children as $child)
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        {!! $child->getImageTag('thumbnail', [
                                            'class' => 'img-circle mr-3',
                                            'style' => 'width: 40px; height: 40px; object-fit: cover;'
                                        ]) !!}
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <a href="{{ route('admin.categories.show', $child) }}">
                                                    {{ $child->getName() }}
                                                </a>
                                            </h6>
                                            <small class="text-muted">
                                                {{ $child->products->count() }} {{ __('admin.products') }}
                                            </small>
                                        </div>
                                        <div>
                                            @if($child->is_active)
                                                <span class="badge badge-success">{{ __('admin.active') }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ __('admin.inactive') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Products -->
        @if($category->products->count() > 0)
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">{{ __('admin.category_products') }} ({{ $category->products->count() }})</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.products.index') }}?category_id={{ $category->id }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> {{ __('admin.view_all') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>{{ __('admin.image') }}</th>
                                <th>{{ __('admin.name') }}</th>
                                <th>{{ __('admin.price') }}</th>
                                <th>{{ __('admin.status') }}</th>
                                <th>{{ __('admin.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->products->take(5) as $product)
                            <tr>
                                <td>
                                    <img src="{{ $product->getImageUrl('thumbnail') }}"
                                         alt="{{ $product->name ?? $product->sku ?? 'Product' }}"
                                         class="img-thumbnail"
                                         style="width: 30px; height: 30px; object-fit: cover;">
                                </td>
                                <td>{{ $product->name ?? $product->sku ?? 'Product #' . $product->id }}</td>
                                <td>
                                    @if($product->price)
                                        <span class="badge badge-success">${{ number_format($product->price, 2) }}</span>
                                    @else
                                        <span class="text-muted">{{ __('admin.no_price') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge badge-success">{{ __('admin.active') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('admin.inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-info btn-xs">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($category->products->count() > 5)
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.products.index') }}?category_id={{ $category->id }}" class="btn btn-outline-primary btn-sm">
                            {{ __('admin.view_all_products') }} ({{ $category->products->count() }})
                        </a>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Category Image -->
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">{{ __('admin.category_image') }}</h3>
            </div>
            <div class="card-body text-center">
                {!! $category->getImageTag('medium', [
                    'class' => 'img-fluid rounded category-main-image',
                    'style' => 'max-height: 300px;'
                ]) !!}

                <!-- Different sizes -->
                <div class="mt-3">
                    <h6>{{ __('admin.available_sizes') }}:</h6>
                    <div class="btn-group-vertical btn-group-sm w-100">
                        @foreach(['thumbnail', 'small', 'medium', 'large', 'original'] as $size)
                            <a href="{{ $category->getImageUrl($size) }}" target="_blank" class="btn btn-outline-info">
                                {{ __('admin.' . $size) }} {{ __('admin.size') }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ __('admin.quick_actions') }}</h3>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning btn-block">
                        <i class="fas fa-edit"></i> {{ __('admin.edit_category') }}
                    </a>

                    @if($category->children->count() == 0 && $category->products->count() == 0)
                        <button type="button" class="btn btn-danger btn-block delete-category"
                                data-id="{{ $category->id }}"
                                data-name="{{ $category->getName() }}">
                            <i class="fas fa-trash"></i> {{ __('admin.delete_category') }}
                        </button>
                    @endif

                    <button type="button" class="btn btn-{{ $category->is_active ? 'secondary' : 'success' }} btn-block toggle-status"
                            data-id="{{ $category->id }}">
                        <i class="fas fa-{{ $category->is_active ? 'pause' : 'play' }}"></i>
                        {{ $category->is_active ? __('admin.deactivate') : __('admin.activate') }}
                    </button>

                    <a href="{{ route('admin.categories.create') }}?parent_id={{ $category->id }}" class="btn btn-info btn-block">
                        <i class="fas fa-plus"></i> {{ __('admin.add_subcategory') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Category Stats -->
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">{{ __('admin.statistics') }}</h3>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="description-block border-right">
                            <span class="description-percentage text-primary">
                                <i class="fas fa-sitemap"></i>
                            </span>
                            <h5 class="description-header">{{ $category->children->count() }}</h5>
                            <span class="description-text">{{ __('admin.subcategories') }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="description-block">
                            <span class="description-percentage text-success">
                                <i class="fas fa-box"></i>
                            </span>
                            <h5 class="description-header">{{ $category->products->count() }}</h5>
                            <span class="description-text">{{ __('admin.products') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        </div>
    </div>
</section>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">{{ __('admin.confirm_delete') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ __('admin.are_you_sure_delete_category') }} "{{ $category->getName() }}"?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ __('admin.delete_category_warning') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    {{ __('admin.cancel') }}
                </button>
                <form id="delete-form" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> {{ __('admin.delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.flag-icon {
    width: 16px;
    height: 12px;
    object-fit: cover;
}
.category-main-image {
    border: 1px solid #dee2e6;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle status
    $('.toggle-status').click(function() {
        const categoryId = $(this).data('id');

        $.ajax({
            url: `/admin/categories/${categoryId}/toggle-status`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                toastr.error('{{ __('admin.error_occurred') }}');
            }
        });
    });

    // Delete category
    $('.delete-category').click(function() {
        const categoryId = $(this).data('id');
        const categoryName = $(this).data('name');

        $('#category-name').text(categoryName);
        $('#delete-form').attr('action', `/admin/categories/${categoryId}`);
        $('#deleteModal').modal('show');
    });
});
</script>
@endpush
