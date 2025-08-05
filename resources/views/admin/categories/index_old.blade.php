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
    .bulk-actions {
        background: #e3f2fd;
        padding: 0.75rem 1rem;
        border-radius: 0.375rem;
        margin-bottom: 1rem;
        display: none;
    }
    .bulk-actions.show {
        display: block;
    }
    .table-responsive {
        border-radius: 0.375rem;
    }
    @media (max-width: 768px) {
        .card-tools .btn {
            margin-bottom: 0.5rem;
        }
        .search-filters .form-group {
            margin-bottom: 1rem;
        }
        .table-actions .btn {
            margin-bottom: 0.25rem;
        }
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
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.categories_list') }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> {{ __('admin.add_category') }}
                            </a>
                        </div>
                    </div>

                    <!-- Search Filters -->
                    <div class="search-filters">
                        <form method="GET" action="{{ route('admin.categories.index') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ __('admin.search') }}</label>
                                        <input type="text" name="search" class="form-control form-control-sm"
                                               placeholder="{{ __('admin.search_categories_placeholder') }}"
                                               value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>{{ __('admin.status') }}</label>
                                        <select name="status" class="form-control form-control-sm">
                                            <option value="">{{ __('admin.all_statuses') }}</option>
                                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ __('admin.parent_category') }}</label>
                                        <select name="parent" class="form-control form-control-sm">
                                            <option value="">{{ __('admin.all_categories') }}</option>
                                            <option value="root" {{ request('parent') == 'root' ? 'selected' : '' }}>{{ __('admin.root_categories') }}</option>
                                            @if(isset($parentCategories))
                                                @foreach($parentCategories as $parent)
                                                    <option value="{{ $parent->id }}" {{ request('parent') == $parent->id ? 'selected' : '' }}>
                                                        {{ $parent->getName() }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>{{ __('admin.per_page') }}</label>
                                        <select name="per_page" class="form-control form-control-sm">
                                            <option value="15" {{ request('per_page') == '15' ? 'selected' : '' }}>15</option>
                                            <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                                            <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                            <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div class="d-flex">
                                            <button type="submit" class="btn btn-primary btn-sm mr-2">
                                                <i class="fas fa-search"></i> {{ __('admin.search') }}
                                            </button>
                                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">
                                                <i class="fas fa-times"></i> {{ __('admin.clear') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Bulk Actions -->
                    <div class="bulk-actions" id="bulkActions">
                        <div class="d-flex align-items-center">
                            <span class="mr-3">
                                <strong id="selectedCount">0</strong> {{ __('admin.items_selected') }}
                            </span>
                            <div class="btn-group">
                                <button type="button" class="btn btn-success btn-sm" id="bulkActivate">
                                    <i class="fas fa-check"></i> {{ __('admin.activate_selected') }}
                                </button>
                                <button type="button" class="btn btn-warning btn-sm" id="bulkDeactivate">
                                    <i class="fas fa-ban"></i> {{ __('admin.deactivate_selected') }}
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" id="bulkDelete">
                                    <i class="fas fa-trash"></i> {{ __('admin.delete_selected') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        @if($categories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="5%">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="selectAll">
                                                <label class="custom-control-label" for="selectAll"></label>
                                            </div>
                                        </th>
                                        <th width="8%">{{ __('admin.image') }}</th>
                                        <th>{{ __('admin.name') }}</th>
                                        <th width="15%">{{ __('admin.parent') }}</th>
                                        <th width="10%">{{ __('admin.sort_order') }}</th>
                                        <th width="12%">{{ __('admin.products_count') }}</th>
                                        <th width="10%">{{ __('admin.status') }}</th>
                                        <th width="12%">{{ __('admin.created_at') }}</th>
                                        <th width="15%" class="text-center">{{ __('admin.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                    <tr>
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input category-checkbox"
                                                       id="category-{{ $category->id }}" value="{{ $category->id }}">
                                                <label class="custom-control-label" for="category-{{ $category->id }}"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <img src="{{ $category->getImageUrl('thumbnail') ?? asset('images/no-image.png') }}"
                                                 alt="{{ $category->getName() }}"
                                                 class="category-image">
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
                                                <small class="text-muted category-hierarchy">
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
                                        <td class="text-center table-actions">
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
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <div class="text-muted">
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
</section>
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
    <div class="modal-dialog modal-dialog-centered" role="document">
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

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1" role="dialog" aria-labelledby="bulkActionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkActionModalLabel">{{ __('admin.confirm_action') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="bulk-action-text"></p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    {{ __('admin.bulk_action_warning') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    {{ __('admin.cancel') }}
                </button>
                <button type="button" class="btn btn-primary" id="confirmBulkAction">
                    {{ __('admin.confirm') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Checkbox functionality
    const selectAllCheckbox = $('#selectAll');
    const categoryCheckboxes = $('.category-checkbox');
    const bulkActions = $('#bulkActions');
    const selectedCount = $('#selectedCount');

    // Select/Deselect All
    selectAllCheckbox.change(function() {
        const isChecked = $(this).is(':checked');
        categoryCheckboxes.prop('checked', isChecked);
        updateBulkActions();
    });

    // Individual checkbox change
    categoryCheckboxes.change(function() {
        updateBulkActions();
        updateSelectAllState();
    });

    // Update bulk actions visibility
    function updateBulkActions() {
        const selectedItems = categoryCheckboxes.filter(':checked').length;
        selectedCount.text(selectedItems);

        if (selectedItems > 0) {
            bulkActions.addClass('show');
        } else {
            bulkActions.removeClass('show');
        }
    }

    // Update select all checkbox state
    function updateSelectAllState() {
        const totalCheckboxes = categoryCheckboxes.length;
        const checkedCheckboxes = categoryCheckboxes.filter(':checked').length;

        if (checkedCheckboxes === totalCheckboxes) {
            selectAllCheckbox.prop('checked', true);
            selectAllCheckbox.prop('indeterminate', false);
        } else if (checkedCheckboxes > 0) {
            selectAllCheckbox.prop('checked', false);
            selectAllCheckbox.prop('indeterminate', true);
        } else {
            selectAllCheckbox.prop('checked', false);
            selectAllCheckbox.prop('indeterminate', false);
        }
    }

    // Status toggle
    $('.status-toggle').change(function() {
        const categoryId = $(this).data('id');
        const isActive = $(this).is(':checked');
        const toggle = $(this);

        $.ajax({
            url: `/admin/categories/${categoryId}/toggle-status`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $(document).Toasts('create', {
                        class: 'bg-success',
                        title: '{{ __("admin.success") }}',
                        body: response.message,
                        autohide: true,
                        delay: 3000
                    });
                }
            },
            error: function(xhr) {
                // Revert checkbox state
                toggle.prop('checked', !isActive);
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: '{{ __("admin.error") }}',
                    body: '{{ __("admin.error_occurred") }}',
                    autohide: true,
                    delay: 5000
                });
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

    // Bulk Actions
    let currentBulkAction = '';

    $('#bulkActivate').click(function() {
        const selectedIds = getSelectedIds();
        if (selectedIds.length === 0) return;

        currentBulkAction = 'activate';
        $('#bulk-action-text').text(`{{ __('admin.confirm_bulk_activate') }} ${selectedIds.length} {{ __('admin.categories') }}?`);
        $('#bulkActionModal').modal('show');
    });

    $('#bulkDeactivate').click(function() {
        const selectedIds = getSelectedIds();
        if (selectedIds.length === 0) return;

        currentBulkAction = 'deactivate';
        $('#bulk-action-text').text(`{{ __('admin.confirm_bulk_deactivate') }} ${selectedIds.length} {{ __('admin.categories') }}?`);
        $('#bulkActionModal').modal('show');
    });

    $('#bulkDelete').click(function() {
        const selectedIds = getSelectedIds();
        if (selectedIds.length === 0) return;

        currentBulkAction = 'delete';
        $('#bulk-action-text').text(`{{ __('admin.confirm_bulk_delete') }} ${selectedIds.length} {{ __('admin.categories') }}?`);
        $('#bulkActionModal').modal('show');
    });

    // Confirm bulk action
    $('#confirmBulkAction').click(function() {
        const selectedIds = getSelectedIds();
        if (selectedIds.length === 0) return;

        $.ajax({
            url: `/admin/categories/bulk-action`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                action: currentBulkAction,
                ids: selectedIds
            },
            success: function(response) {
                if (response.success) {
                    $(document).Toasts('create', {
                        class: 'bg-success',
                        title: '{{ __("admin.success") }}',
                        body: response.message,
                        autohide: true,
                        delay: 3000
                    });

                    // Reload page after short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
                $('#bulkActionModal').modal('hide');
            },
            error: function(xhr) {
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: '{{ __("admin.error") }}',
                    body: '{{ __("admin.error_occurred") }}',
                    autohide: true,
                    delay: 5000
                });
                $('#bulkActionModal').modal('hide');
            }
        });
    });

    // Get selected category IDs
    function getSelectedIds() {
        return categoryCheckboxes.filter(':checked').map(function() {
            return $(this).val();
        }).get();
    }

    // Search on enter
    $('input[name="search"]').keypress(function(e) {
        if (e.which == 13) {
            $(this).closest('form').submit();
        }
    });

    // Auto-submit filters
    $('select[name="status"], select[name="parent"], select[name="per_page"]').change(function() {
        $(this).closest('form').submit();
    });
});
</script>
@endpush
