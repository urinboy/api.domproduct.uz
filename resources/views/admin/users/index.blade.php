@extends('admin.layouts.app')

@section('title', __('admin.users_management'))

@push('styles')
<style>
    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }
    .user-status {
        font-size: 0.875rem;
    }
    .user-role {
        font-size: 0.75rem;
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
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('admin.users_management') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('admin.users') }}</li>
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
                        <h3 class="card-title">{{ __('admin.users_list') }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> {{ __('admin.add_user') }}
                            </a>
                        </div>
                    </div>

                    <!-- Search Filters -->
                    <div class="search-filters">
                        <form method="GET" action="{{ route('admin.users.index') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ __('admin.search') }}</label>
                                        <input type="text" name="search" class="form-control form-control-sm"
                                               placeholder="{{ __('admin.search_users') }}"
                                               value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>{{ __('admin.role') }}</label>
                                        <select name="role" class="form-control form-control-sm">
                                            <option value="">{{ __('admin.all_roles') }}</option>
                                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>{{ __('admin.admin') }}</option>
                                            <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>{{ __('admin.manager') }}</option>
                                            <option value="employee" {{ request('role') == 'employee' ? 'selected' : '' }}>{{ __('admin.employee') }}</option>
                                            <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>{{ __('admin.customer') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>{{ __('admin.status') }}</label>
                                        <select name="status" class="form-control form-control-sm">
                                            <option value="">{{ __('admin.all_statuses') }}</option>
                                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>{{ __('admin.sort_by') }}</label>
                                        <select name="sort" class="form-control form-control-sm">
                                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>{{ __('admin.created_date') }}</option>
                                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>{{ __('admin.name') }}</option>
                                            <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>{{ __('admin.email') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>{{ __('admin.direction') }}</label>
                                        <select name="direction" class="form-control form-control-sm">
                                            <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>{{ __('admin.descending') }}</option>
                                            <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>{{ __('admin.ascending') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Bulk Actions -->
                    <div class="bulk-actions">
                        <form id="bulk-form" method="POST" action="{{ route('admin.users.bulk') }}">
                            @csrf
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <span id="selected-count">0</span> {{ __('admin.users_selected') }}
                                </div>
                                <div class="col-md-6 text-right">
                                    <select name="action" class="form-control form-control-sm d-inline-block w-auto mr-2">
                                        <option value="">{{ __('admin.choose_action') }}</option>
                                        <option value="activate">{{ __('admin.activate') }}</option>
                                        <option value="deactivate">{{ __('admin.deactivate') }}</option>
                                        <option value="delete">{{ __('admin.delete') }}</option>
                                    </select>
                                    <button type="submit" class="btn btn-warning btn-sm">{{ __('admin.apply') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th>{{ __('admin.avatar') }}</th>
                                    <th>{{ __('admin.name') }}</th>
                                    <th>{{ __('admin.email') }}</th>
                                    <th>{{ __('admin.phone') }}</th>
                                    <th>{{ __('admin.role') }}</th>
                                    <th>{{ __('admin.status') }}</th>
                                    <th>{{ __('admin.created_date') }}</th>
                                    <th>{{ __('admin.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="users[]" value="{{ $user->id }}" class="user-checkbox">
                                    </td>
                                    <td>
                                        <img src="{{ $user->getAvatarUrl('small') }}" alt="{{ $user->full_name }}" class="user-avatar">
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $user->full_name }}</strong>
                                            @if($user->first_name && $user->last_name)
                                                <br><small class="text-muted">{{ $user->name }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        {{ $user->email }}
                                        @if($user->email_verified_at)
                                            <i class="fas fa-check-circle text-success" title="{{ __('admin.email_verified') }}"></i>
                                        @else
                                            <i class="fas fa-times-circle text-danger" title="{{ __('admin.email_not_verified') }}"></i>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $user->phone ?? '-' }}
                                        @if($user->phone_verified)
                                            <i class="fas fa-check-circle text-success" title="{{ __('admin.phone_verified') }}"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'manager' ? 'warning' : 'info') }} user-role">
                                            {{ __('admin.' . $user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->is_active)
                                            <span class="badge badge-success user-status">{{ __('admin.active') }}</span>
                                        @else
                                            <span class="badge badge-secondary user-status">{{ __('admin.inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $user->created_at->format('d.m.Y H:i') }}</small>
                                    </td>
                                    <td class="table-actions">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info btn-sm" title="{{ __('admin.view') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm" title="{{ __('admin.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-{{ $user->is_active ? 'secondary' : 'success' }} btn-sm"
                                                        title="{{ $user->is_active ? __('admin.deactivate') : __('admin.activate') }}">
                                                    <i class="fas fa-{{ $user->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline"
                                                  onsubmit="return confirm('{{ __('admin.confirm_delete_user') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="{{ __('admin.delete') }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">{{ __('admin.no_users_found') }}</p>
                                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> {{ __('admin.add_first_user') }}
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($users->hasPages())
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                {{ __('admin.showing') }} {{ $users->firstItem() }} {{ __('admin.to') }} {{ $users->lastItem() }}
                                {{ __('admin.of') }} {{ $users->total() }} {{ __('admin.results') }}
                            </div>
                            <div>
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Select all checkbox functionality
    $('#select-all').on('change', function() {
        $('.user-checkbox').prop('checked', this.checked);
        updateBulkActions();
    });

    // Individual checkbox functionality
    $('.user-checkbox').on('change', function() {
        updateBulkActions();

        // Update select all checkbox
        const totalCheckboxes = $('.user-checkbox').length;
        const checkedCheckboxes = $('.user-checkbox:checked').length;
        $('#select-all').prop('checked', totalCheckboxes === checkedCheckboxes);
    });

    // Bulk form submission
    $('#bulk-form').on('submit', function(e) {
        const selectedUsers = $('.user-checkbox:checked').length;
        const action = $('select[name="action"]').val();

        if (selectedUsers === 0) {
            e.preventDefault();
            alert('{{ __("admin.please_select_users") }}');
            return false;
        }

        if (!action) {
            e.preventDefault();
            alert('{{ __("admin.please_select_action") }}');
            return false;
        }

        if (action === 'delete') {
            if (!confirm('{{ __("admin.confirm_bulk_delete") }}')) {
                e.preventDefault();
                return false;
            }
        }

        // Add selected user IDs to form
        $('.user-checkbox:checked').each(function() {
            $('#bulk-form').append(`<input type="hidden" name="users[]" value="${$(this).val()}">`);
        });
    });

    function updateBulkActions() {
        const selectedCount = $('.user-checkbox:checked').length;
        $('#selected-count').text(selectedCount);

        if (selectedCount > 0) {
            $('.bulk-actions').addClass('show');
        } else {
            $('.bulk-actions').removeClass('show');
        }
    }
});
</script>
@endpush
