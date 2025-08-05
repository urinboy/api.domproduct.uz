@extends('admin.layouts.app')

@section('title', __('admin.view_language'))

@push('styles')
<style>
    .info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 0.5rem;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    .info-card h2 {
        margin-bottom: 1rem;
        font-weight: 600;
    }
    .flag-display {
        font-size: 3rem;
        margin-right: 1rem;
    }
    .language-badge {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
        border-radius: 0.25rem;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
        display: inline-block;
    }
    .stats-card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    .stats-card:hover {
        transform: translateY(-2px);
    }
    .timeline-item {
        border-left: 3px solid #007bff;
        padding-left: 1rem;
        margin-bottom: 1rem;
    }
    .action-button {
        margin: 0.25rem;
        border-radius: 0.375rem;
    }
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('admin.view_language') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.languages.index') }}">{{ __('admin.languages') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('admin.view_language') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Language Header Card -->
        <div class="info-card">
            <div class="d-flex align-items-center">
                <div class="flag-display">
                    {{ $language->flag ?? '🏳️' }}
                </div>
                <div class="flex-grow-1">
                    <h2>{{ $language->name }} ({{ $language->code }})</h2>
                    <div>
                        <span class="language-badge {{ $language->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $language->is_active ? __('admin.active') : __('admin.inactive') }}
                        </span>
                        @if($language->is_default)
                            <span class="language-badge bg-warning">
                                <i class="fas fa-star"></i> {{ __('admin.default') }}
                            </span>
                        @endif
                        <span class="language-badge bg-info">
                            {{ __('admin.sort_order') }}: {{ $language->sort_order ?? 0 }}
                        </span>
                    </div>
                </div>
                <div>
                    <a href="{{ route('admin.languages.edit', $language) }}" class="btn btn-light action-button">
                        <i class="fas fa-edit"></i> {{ __('admin.edit') }}
                    </a>
                    <a href="{{ route('admin.languages.index') }}" class="btn btn-outline-light action-button">
                        <i class="fas fa-arrow-left"></i> {{ __('admin.back') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <!-- Language Statistics -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <div class="row">
                                    <div class="col-4">
                                        <i class="fas fa-language fa-3x text-primary"></i>
                                    </div>
                                    <div class="col-8">
                                        <div class="numbers">
                                            <h3 class="text-primary">{{ $language->name }}</h3>
                                            <p class="text-muted">{{ __('admin.language_name') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <div class="row">
                                    <div class="col-4">
                                        <i class="fas fa-code fa-3x text-success"></i>
                                    </div>
                                    <div class="col-8">
                                        <div class="numbers">
                                            <h3 class="text-success">{{ $language->code }}</h3>
                                            <p class="text-muted">{{ __('admin.language_code') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Translations Statistics -->
                @if(isset($translationsStats) && count($translationsStats) > 0)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar"></i> {{ __('admin.translations_statistics') }}
                            </h3>
                        </div>
                        <div class="card-body">
                            @if(array_sum(array_column($translationsStats, 'count')) > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('admin.model') }}</th>
                                                <th class="text-center">{{ __('admin.count') }}</th>
                                                <th>{{ __('admin.last_updated') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($translationsStats as $stat)
                                                <tr>
                                                    <td>
                                                        <i class="fas fa-cube text-primary"></i>
                                                        {{ $stat['model'] }}
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-info badge-lg">{{ $stat['count'] }}</span>
                                                    </td>
                                                    <td>
                                                        @if($stat['last_updated'])
                                                            <i class="fas fa-clock text-muted"></i>
                                                            {{ $stat['last_updated']->format('d.m.Y H:i') }}
                                                        @else
                                                            <span class="text-muted">{{ __('admin.never') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">{{ __('admin.no_translations_found') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bolt"></i> {{ __('admin.quick_actions') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(!$language->is_active)
                            <form action="{{ route('admin.languages.toggle-status', $language) }}" method="POST" class="mb-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fas fa-toggle-on"></i> {{ __('admin.activate') }}
                                </button>
                            </form>
                        @else
                            @if(!$language->is_default)
                                <form action="{{ route('admin.languages.toggle-status', $language) }}" method="POST" class="mb-2">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-warning btn-block">
                                        <i class="fas fa-toggle-off"></i> {{ __('admin.deactivate') }}
                                    </button>
                                </form>
                            @endif
                        @endif

                        @if(!$language->is_default)
                            <form action="{{ route('admin.languages.set-default', $language) }}" method="POST" class="mb-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-info btn-block">
                                    <i class="fas fa-star"></i> {{ __('admin.set_as_default') }}
                                </button>
                            </form>
                        @endif

                        <hr>

                        <a href="{{ route('admin.languages.create') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-plus"></i> {{ __('admin.add_new_language') }}
                        </a>

                        @if(!$language->is_default)
                            <button type="button" class="btn btn-danger btn-block mt-2" data-toggle="modal" data-target="#deleteModal">
                                <i class="fas fa-trash"></i> {{ __('admin.delete') }}
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Timeline -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history"></i> {{ __('admin.timeline') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="timeline-item">
                            <div class="d-flex justify-content-between">
                                <strong>{{ __('admin.language_created') }}</strong>
                                <small class="text-muted">{{ $language->created_at->format('d.m.Y') }}</small>
                            </div>
                            <p class="text-muted mb-0">
                                <i class="fas fa-clock"></i> {{ $language->created_at->format('H:i') }}
                            </p>
                        </div>

                        @if($language->updated_at->ne($language->created_at))
                            <div class="timeline-item">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ __('admin.language_updated') }}</strong>
                                    <small class="text-muted">{{ $language->updated_at->format('d.m.Y') }}</small>
                                </div>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-clock"></i> {{ $language->updated_at->format('H:i') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- System Information -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-server"></i> {{ __('admin.system_info') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-4"><strong>ID:</strong></div>
                            <div class="col-8">{{ $language->id }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><strong>{{ __('admin.created_at') }}:</strong></div>
                            <div class="col-8">{{ $language->created_at->format('d.m.Y H:i:s') }}</div>
                        </div>
                        <div class="row">
                            <div class="col-4"><strong>{{ __('admin.updated_at') }}:</strong></div>
                            <div class="col-8">{{ $language->updated_at->format('d.m.Y H:i:s') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>    </div>
</section>

<!-- Delete Modal -->
@if(!$language->is_default)
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
                    <p>{{ __('admin.are_you_sure_delete_language') }}</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>{{ $language->flag ?? '🏳️' }} {{ $language->name }} ({{ $language->code }})</strong>
                    </div>
                    @if(isset($translationsStats) && array_sum(array_column($translationsStats, 'count')) > 0)
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ __('admin.this_language_has_translations') }}
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('admin.cancel') }}</button>
                    <form action="{{ route('admin.languages.destroy', $language) }}" method="POST" class="d-inline">
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
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh page after actions
    if (window.location.hash === '#updated') {
        setTimeout(function() {
            window.location.hash = '';
        }, 100);
    }
});
</script>
@endpush

@endsection
