@extends('admin.layouts.app')

@section('title', __('admin.languages'))

@push('styles')
<style>
    .language-flag {
        font-size: 1.5rem;
        margin-right: 0.5rem;
    }
    .language-code {
        font-family: 'Courier New', monospace;
        background: #f8f9fa;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
    }
    .search-filters {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 0.375rem;
        margin-bottom: 1rem;
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
    .status-badge {
        font-size: 0.75rem;
    }
    .default-badge {
        background: linear-gradient(45deg, #ffd700, #ffed4e);
        color: #000;
        font-weight: bold;
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
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ __('admin.languages_management') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('admin.languages') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <!-- Search and Filters -->
        <form method="GET" action="{{ route('admin.languages.index') }}">
            <div class="search-filters">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>{{ __('admin.search') }}</label>
                            <input type="text" name="search" class="form-control"
                                   placeholder="{{ __('admin.search_languages_placeholder') }}"
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ __('admin.status') }}</label>
                            <select name="status" class="form-control">
                                <option value="">{{ __('admin.all_statuses') }}</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ __('admin.default_language') }}</label>
                            <select name="is_default" class="form-control">
                                <option value="">{{ __('admin.all') }}</option>
                                <option value="1" {{ request('is_default') == '1' ? 'selected' : '' }}>{{ __('admin.yes') }}</option>
                                <option value="0" {{ request('is_default') == '0' ? 'selected' : '' }}>{{ __('admin.no') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ __('admin.per_page') }}</label>
                            <select name="per_page" class="form-control">
                                <option value="15" {{ request('per_page') == '15' ? 'selected' : '' }}>15</option>
                                <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-search"></i> {{ __('admin.search') }}
                                </button>
                                <a href="{{ route('admin.languages.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> {{ __('admin.clear') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Bulk Actions -->
        <div class="bulk-actions" id="bulkActions">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong id="selectedCount">0</strong> {{ __('admin.items_selected') }}
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-success" onclick="bulkAction('activate')">
                        <i class="fas fa-check"></i> {{ __('admin.activate_selected') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-warning" onclick="bulkAction('deactivate')">
                        <i class="fas fa-times"></i> {{ __('admin.deactivate_selected') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="bulkAction('delete')">
                        <i class="fas fa-trash"></i> {{ __('admin.delete_selected') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Languages Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('admin.languages_list') }}</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.languages.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> {{ __('admin.add_language') }}
                    </a>
                </div>
            </div>

            <div class="card-body p-0">
                @if($languages->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="select-all">
                                            <label for="select-all" class="custom-control-label"></label>
                                        </div>
                                    </th>
                                    <th>{{ __('admin.flag') }}</th>
                                    <th>{{ __('admin.name') }}</th>
                                    <th>{{ __('admin.code') }}</th>
                                    <th>{{ __('admin.status') }}</th>
                                    <th>{{ __('admin.default') }}</th>
                                    <th>{{ __('admin.sort_order') }}</th>
                                    <th>{{ __('admin.created_at') }}</th>
                                    <th width="200">{{ __('admin.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($languages as $language)
                                    <tr>
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input language-checkbox"
                                                       type="checkbox"
                                                       id="language-{{ $language->id }}"
                                                       value="{{ $language->id }}">
                                                <label class="custom-control-label" for="language-{{ $language->id }}"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="language-flag">{{ $language->flag ?? '🏳️' }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $language->name }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="language-code">{{ $language->code }}</span>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox"
                                                       class="custom-control-input status-toggle"
                                                       id="status-{{ $language->id }}"
                                                       data-id="{{ $language->id }}"
                                                       {{ $language->is_active ? 'checked' : '' }}
                                                       {{ $language->is_default ? 'disabled' : '' }}>
                                                <label class="custom-control-label" for="status-{{ $language->id }}"></label>
                                            </div>
                                        </td>
                                        <td>
                                            @if($language->is_default)
                                                <span class="badge badge-warning default-badge">
                                                    <i class="fas fa-star"></i> {{ __('admin.default') }}
                                                </span>
                                            @else
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-warning set-default-btn"
                                                        data-id="{{ $language->id }}"
                                                        data-name="{{ $language->name }}">
                                                    <i class="fas fa-star"></i> {{ __('admin.set_default') }}
                                                </button>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $language->sort_order }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $language->created_at->format('d.m.Y H:i') }}</span>
                                        </td>
                                        <td class="table-actions">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.languages.show', $language) }}"
                                                   class="btn btn-sm btn-info" title="{{ __('admin.view') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.languages.edit', $language) }}"
                                                   class="btn btn-sm btn-warning" title="{{ __('admin.edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-sm btn-danger delete-btn"
                                                        data-id="{{ $language->id }}"
                                                        data-name="{{ $language->name }}"
                                                        title="{{ __('admin.delete') }}"
                                                        {{ $language->is_default ? 'disabled' : '' }}>
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
                    <div class="card-footer">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="dataTables_info">
                                    {{ __('admin.showing_results', [
                                        'from' => $languages->firstItem(),
                                        'to' => $languages->lastItem(),
                                        'total' => $languages->total()
                                    ]) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                {{ $languages->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-language fa-3x text-muted"></i>
                        </div>
                        <h5 class="text-muted">{{ __('admin.no_languages_found') }}</h5>
                        <p class="text-muted">{{ __('admin.add_first_language') }}</p>
                        <a href="{{ route('admin.languages.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('admin.add_language') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('admin.confirm_delete') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ __('admin.are_you_sure_delete_language') }} "<span id="language-name"></span>"?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ __('admin.delete_language_warning') }}
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

<!-- Set Default Confirmation Modal -->
<div class="modal fade" id="setDefaultModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('admin.set_default_language') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ __('admin.confirm_set_default_language') }} "<span id="default-language-name"></span>"?</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    {{ __('admin.set_default_language_note') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    {{ __('admin.cancel') }}
                </button>
                <button type="button" class="btn btn-warning" id="confirm-set-default">
                    <i class="fas fa-star"></i> {{ __('admin.set_default') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Confirmation Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulk-action-title">{{ __('admin.confirm_action') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="bulk-action-text"></p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ __('admin.bulk_action_warning') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    {{ __('admin.cancel') }}
                </button>
                <button type="button" class="btn btn-danger" id="confirm-bulk-action">
                    {{ __('admin.confirm_action') }}
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
    const selectAllCheckbox = $('#select-all');
    const languageCheckboxes = $('.language-checkbox');
    const bulkActions = $('#bulkActions');
    const selectedCountSpan = $('#selectedCount');

    // Select all functionality
    selectAllCheckbox.change(function() {
        languageCheckboxes.prop('checked', this.checked);
        updateBulkActions();
    });

    // Individual checkbox change
    languageCheckboxes.change(function() {
        updateSelectAll();
        updateBulkActions();
    });

    function updateSelectAll() {
        const totalCheckboxes = languageCheckboxes.length;
        const checkedCheckboxes = languageCheckboxes.filter(':checked').length;

        selectAllCheckbox.prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
        selectAllCheckbox.prop('checked', checkedCheckboxes === totalCheckboxes);
    }

    function updateBulkActions() {
        const selectedCount = languageCheckboxes.filter(':checked').length;
        selectedCountSpan.text(selectedCount);

        if (selectedCount > 0) {
            bulkActions.addClass('show');
        } else {
            bulkActions.removeClass('show');
        }
    }

    // Status toggle
    $('.status-toggle').change(function() {
        const languageId = $(this).data('id');
        const isChecked = $(this).is(':checked');

        $.ajax({
            url: `/admin/languages/${languageId}/toggle-status`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                } else {
                    showToast('error', response.message);
                    // Revert checkbox state
                    $(this).prop('checked', !isChecked);
                }
            },
            error: function() {
                showToast('error', '{{ __('admin.error_occurred') }}');
                // Revert checkbox state
                $(this).prop('checked', !isChecked);
            }
        });
    });

    // Delete functionality
    $('.delete-btn').click(function() {
        const languageId = $(this).data('id');
        const languageName = $(this).data('name');

        $('#language-name').text(languageName);
        $('#delete-form').attr('action', `/admin/languages/${languageId}`);
        $('#deleteModal').modal('show');
    });

    // Set default functionality
    $('.set-default-btn').click(function() {
        const languageId = $(this).data('id');
        const languageName = $(this).data('name');

        $('#default-language-name').text(languageName);
        $('#confirm-set-default').data('id', languageId);
        $('#setDefaultModal').modal('show');
    });

    $('#confirm-set-default').click(function() {
        const languageId = $(this).data('id');

        $.ajax({
            url: `/admin/languages/${languageId}/set-default`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    location.reload();
                } else {
                    showToast('error', response.message);
                }
                $('#setDefaultModal').modal('hide');
            },
            error: function() {
                showToast('error', '{{ __('admin.error_occurred') }}');
                $('#setDefaultModal').modal('hide');
            }
        });
    });

    // Bulk actions
    window.bulkAction = function(action) {
        const selectedIds = getSelectedIds();

        if (selectedIds.length === 0) {
            showToast('warning', '{{ __('admin.please_select_languages') }}');
            return;
        }

        let actionText = '';
        switch (action) {
            case 'activate':
                actionText = `{{ __('admin.confirm_bulk_activate') }} ${selectedIds.length} {{ __('admin.languages') }}?`;
                break;
            case 'deactivate':
                actionText = `{{ __('admin.confirm_bulk_deactivate') }} ${selectedIds.length} {{ __('admin.languages') }}?`;
                break;
            case 'delete':
                actionText = `{{ __('admin.confirm_bulk_delete') }} ${selectedIds.length} {{ __('admin.languages') }}?`;
                break;
        }

        $('#bulk-action-text').text(actionText);
        $('#confirm-bulk-action').data('action', action);
        $('#bulkActionModal').modal('show');
    };

    $('#confirm-bulk-action').click(function() {
        const action = $(this).data('action');
        const selectedIds = getSelectedIds();

        $.ajax({
            url: '{{ route('admin.languages.bulk-action') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ids: selectedIds,
                action: action
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    location.reload();
                } else {
                    showToast('error', response.message);
                }
                $('#bulkActionModal').modal('hide');
            },
            error: function() {
                showToast('error', '{{ __('admin.error_occurred') }}');
                $('#bulkActionModal').modal('hide');
            }
        });
    });

    // Get selected language IDs
    function getSelectedIds() {
        return languageCheckboxes.filter(':checked').map(function() {
            return $(this).val();
        }).get();
    }

    // Toast function
    function showToast(type, message) {
        // You can replace this with your preferred toast library
        alert(message);
    }

    // Auto-submit filters
    $('select[name="status"], select[name="is_default"], select[name="per_page"]').change(function() {
        $(this).closest('form').submit();
    });

    // Search on enter
    $('input[name="search"]').keypress(function(e) {
        if (e.which == 13) {
            $(this).closest('form').submit();
        }
    });
});
</script>
@endpush
