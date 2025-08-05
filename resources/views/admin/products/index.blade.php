@extends('admin.layouts.app')

@section('title', __('admin.products'))

@section('content')
<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('admin.products') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('admin.products') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.products_list') }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> {{ __('admin.add_product') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filter Form -->
                        <form method="GET" action="{{ route('admin.products.index') }}" class="mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control" placeholder="{{ __('admin.search') }}..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="category_id" class="form-control">
                                        <option value="">{{ __('admin.all_categories') }}</option>
                                        @foreach($categories ?? [] as $category)
                                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                @foreach($category->translations as $translation)
                                                    @if($translation->language_id == 1)
                                                        {{ $translation->name }}
                                                    @endif
                                                @endforeach
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="status" class="form-control">
                                        <option value="">{{ __('admin.all_statuses') }}</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fas fa-filter"></i> {{ __('admin.filter') }}
                                    </button>
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-default">
                                        <i class="fas fa-times"></i> {{ __('admin.clear') }}
                                    </a>
                                </div>
                            </div>
                        </form>

                        <!-- Products Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">{{ __('admin.id') }}</th>
                                        <th style="width: 80px;">{{ __('admin.image') }}</th>
                                        <th>{{ __('admin.sku') }}</th>
                                        <th>{{ __('admin.category') }}</th>
                                        <th>{{ __('admin.price') }}</th>
                                        <th>{{ __('admin.stock') }}</th>
                                        <th>{{ __('admin.status') }}</th>
                                        <th style="width: 120px;">{{ __('admin.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products ?? [] as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>
                                            @if($product->images && $product->images->first())
                                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                                     alt="{{ $product->sku }}"
                                                     class="img-thumbnail"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary d-flex align-items-center justify-content-center"
                                                     style="width: 50px; height: 50px; border-radius: 4px;">
                                                    <i class="fas fa-image text-white"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $product->sku }}</strong><br>
                                            <small class="text-muted">{{ $product->barcode ?? __('admin.not_specified') }}</small>
                                        </td>
                                        <td>
                                            @if($product->category)
                                                @foreach($product->category->translations as $translation)
                                                    @if($translation->language_id == 1)
                                                        {{ $translation->name }}
                                                    @endif
                                                @endforeach
                                            @else
                                                <span class="text-muted">{{ __('admin.no_category') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ number_format($product->price) }} UZS</strong>
                                                @if($product->sale_price && $product->sale_price < $product->price)
                                                    <br><small class="text-danger">{{ number_format($product->sale_price) }} UZS</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $product->stock_status == 'in_stock' ? 'success' : ($product->stock_status == 'low_stock' ? 'warning' : 'danger') }}">
                                                {{ $product->stock_quantity ?? 0 }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $product->is_active ? 'success' : 'secondary' }}">
                                                {{ $product->is_active ? __('admin.active') : __('admin.inactive') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.products.show', $product->id) }}"
                                                   class="btn btn-info btn-sm" title="{{ __('admin.view') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                                   class="btn btn-warning btn-sm" title="{{ __('admin.edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.products.destroy', $product->id) }}"
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('{{ __('admin.confirm_delete') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="{{ __('admin.delete') }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-box fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">{{ __('admin.no_products_found') }}</p>
                                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> {{ __('admin.add_first_product') }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if(isset($products) && method_exists($products, 'links'))
                            <div class="d-flex justify-content-center">
                                {{ $products->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-submit form on filter change
    $('select[name="category_id"], select[name="status"]').change(function() {
        $(this).closest('form').submit();
    });
});
</script>
@endpush
