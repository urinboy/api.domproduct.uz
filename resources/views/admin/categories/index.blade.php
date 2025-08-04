@extends('admin.layouts.app')

@section('title', __('admin.categories'))

@push('styles')
<style>
    .category-image {
        width: 50px;
        height: 50px;
        border-radius: 0.375rem;
        object-fit: cover;
    }
    .category-hierarchy {
        font-size: 0.875rem;
    }
    .search-filters {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 0.375rem;
        margin-bottom: 1rem;
    }
    .table-actions {
        white-space: nowrap;
    }
    .status-badge {
        font-size: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('admin.categories') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('admin.categories') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
        <div class="row">
            <div class="col-12">
                <!-- Search and Filter Card -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.search_and_filter') }}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body search-filters">
                        <form action="{{ route('admin.categories.index') }}" method="GET" class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="search">{{ __('admin.search') }}</label>
                                    <input type="text" class="form-control" id="search" name="search"
                                           value="{{ request('search') }}"
                                           placeholder="{{ __('admin.search_categories_placeholder') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="status">{{ __('admin.status') }}</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">{{ __('admin.all_statuses') }}</option>
                                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="parent">{{ __('admin.parent_category') }}</label>
                                    <select class="form-control" id="parent" name="parent">
                                        <option value="">{{ __('admin.all_categories') }}</option>
                                        <option value="root" {{ request('parent') == 'root' ? 'selected' : '' }}>{{ __('admin.root_categories') }}</option>
                                        @foreach($parentCategories as $parent)
                                            <option value="{{ $parent->id }}" {{ request('parent') == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->getName() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-primary mr-2">
                                            <i class="fas fa-search"></i> {{ __('admin.search') }}
                                        </button>
                                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> {{ __('admin.clear') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

        <!-- Categories List Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('admin.categories_list') }}</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> {{ __('admin.add_category') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($categories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>{{ __('admin.image') }}</th>
                                <th>{{ __('admin.name') }}</th>
                                <th>{{ __('admin.parent') }}</th>
                                <th>{{ __('admin.sort_order') }}</th>
                                <th>{{ __('admin.products_count') }}</th>
                                <th>{{ __('admin.status') }}</th>
                                <th>{{ __('admin.created_at') }}</th>
                                <th>{{ __('admin.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>
                                    <img src="{{ $category->getImageUrl('thumbnail') }}"
                                         alt="{{ $category->getName() }}"
                                         class="img-circle category-thumbnail"
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $category->getName() }}</strong>
                                        @if($category->children()->exists())
                                            <span class="badge badge-info badge-sm ml-1">
                                                {{ $category->children()->count() }} {{ __('admin.children') }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($category->getDepth() > 0)
                                        <small class="text-muted">
                                            {{ implode(' > ', $category->getPath()) }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @if($category->parent)
                                        <span class="badge badge-secondary">
                                            {{ $category->parent->getName() }}
                                        </span>
                                    @else
                                        <span class="text-muted">{{ __('admin.root') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-light">{{ $category->sort_order ?? 0 }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-primary">
                                        {{ $category->products()->count() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input status-toggle"
                                               id="status-{{ $category->id }}"
                                               data-id="{{ $category->id }}"
                                               {{ $category->is_active ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="status-{{ $category->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $category->created_at->format('d M Y') }}<br>
                                        {{ $category->created_at->format('H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.categories.show', $category) }}"
                                           class="btn btn-info btn-sm" title="{{ __('admin.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                           class="btn btn-warning btn-sm" title="{{ __('admin.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm delete-category"
                                                data-id="{{ $category->id }}"
                                                data-name="{{ $category->getName() }}"
                                                title="{{ __('admin.delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        {{ __('admin.showing_results', [
                            'from' => $categories->firstItem(),
                            'to' => $categories->lastItem(),
                            'total' => $categories->total()
                        ]) }}
                    </div>
                    <div>
                        {{ $categories->withQueryString()->links() }}
                    </div>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-th-large fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">{{ __('admin.no_categories_found') }}</h5>
                    <p class="text-muted">{{ __('admin.create_first_category') }}</p>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> {{ __('admin.add_category') }}
                    </a>
                </div>
                @endif
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
                <p>{{ __('admin.are_you_sure_delete_category') }} "<span id="category-name"></span>"?</p>
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

@push('scripts')
<script>
$(document).ready(function() {
    // Status toggle
    $('.status-toggle').change(function() {
        const categoryId = $(this).data('id');
        const isActive = $(this).is(':checked');

        $.ajax({
            url: `/admin/categories/${categoryId}/toggle-status`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                }
            },
            error: function(xhr) {
                // Revert checkbox state
                $(this).prop('checked', !isActive);
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

    // Search on enter
    $('#search').keypress(function(e) {
        if (e.which == 13) {
            $(this).closest('form').submit();
        }
    });
});
</script>
@endpush
