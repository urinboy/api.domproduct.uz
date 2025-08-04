@extends('admin.layouts.app')

@section('title', __('admin.product_details'))

@section('content')
<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('admin.product_details') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">{{ __('admin.products') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('admin.product_details') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Product Info -->
            <div class="col-md-8">
                <!-- Basic Information -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.basic_information') }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> {{ __('admin.edit') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="30%">{{ __('admin.sku') }}:</th>
                                        <td><code>{{ $product->sku }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('admin.barcode') }}:</th>
                                        <td>{{ $product->barcode ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('admin.category') }}:</th>
                                        <td>
                                            @if($product->category)
                                                @foreach($product->category->translations as $translation)
                                                    @if($translation->language_id == 1)
                                                        <span class="badge badge-info">{{ $translation->name }}</span>
                                                    @endif
                                                @endforeach
                                            @else
                                                <span class="text-muted">{{ __('admin.no_category') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('admin.status') }}:</th>
                                        <td>
                                            @if($product->status == 'active')
                                                <span class="badge badge-success">{{ __('admin.active') }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ __('admin.inactive') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="30%">{{ __('admin.price') }}:</th>
                                        <td>
                                            <strong class="text-primary">{{ number_format($product->price, 0, '.', ' ') }} UZS</strong>
                                        </td>
                                    </tr>
                                    @if($product->sale_price)
                                    <tr>
                                        <th>{{ __('admin.sale_price') }}:</th>
                                        <td>
                                            <strong class="text-success">{{ number_format($product->sale_price, 0, '.', ' ') }} UZS</strong>
                                            <small class="text-muted">
                                                (-{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%)
                                            </small>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>{{ __('admin.stock_quantity') }}:</th>
                                        <td>
                                            <span class="badge
                                                @if($product->stock_quantity > 10) badge-success
                                                @elseif($product->stock_quantity > 0) badge-warning
                                                @else badge-danger
                                                @endif
                                            ">
                                                {{ $product->stock_quantity }} {{ __('admin.items') }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('admin.stock_status') }}:</th>
                                        <td>
                                            @switch($product->stock_status)
                                                @case('in_stock')
                                                    <span class="badge badge-success">{{ __('admin.in_stock') }}</span>
                                                    @break
                                                @case('low_stock')
                                                    <span class="badge badge-warning">{{ __('admin.low_stock') }}</span>
                                                    @break
                                                @case('out_of_stock')
                                                    <span class="badge badge-danger">{{ __('admin.out_of_stock') }}</span>
                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if($product->is_featured)
                        <div class="alert alert-info">
                            <i class="fas fa-star"></i> {{ __('admin.this_is_featured_product') }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Translations -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.translations') }}</h3>
                    </div>
                    <div class="card-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" id="translationTabs" role="tablist">
                            @foreach($languages ?? [['id' => 1, 'code' => 'uz', 'name' => 'O\'zbek'], ['id' => 2, 'code' => 'en', 'name' => 'English'], ['id' => 3, 'code' => 'ru', 'name' => 'Русский']] as $index => $language)
                            @php
                                $translation = $product->translations->where('language_id', $language['id'])->first();
                            @endphp
                            @if($translation)
                            <li class="nav-item">
                                <a class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                   id="tab-{{ $language['code'] }}"
                                   data-toggle="tab"
                                   href="#translation-{{ $language['code'] }}"
                                   role="tab">
                                    {{ $language['name'] }}
                                </a>
                            </li>
                            @endif
                            @endforeach
                        </ul>

                        <!-- Tab content -->
                        <div class="tab-content border border-top-0 p-3">
                            @foreach($languages ?? [['id' => 1, 'code' => 'uz', 'name' => 'O\'zbek'], ['id' => 2, 'code' => 'en', 'name' => 'English'], ['id' => 3, 'code' => 'ru', 'name' => 'Русский']] as $index => $language)
                            @php
                                $translation = $product->translations->where('language_id', $language['id'])->first();
                            @endphp
                            @if($translation)
                            <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"
                                 id="translation-{{ $language['code'] }}"
                                 role="tabpanel">
                                <h4>{{ $translation->name }}</h4>
                                @if($translation->description)
                                    <p class="text-muted">{{ $translation->description }}</p>
                                @else
                                    <p class="text-muted font-italic">{{ __('admin.no_description') }}</p>
                                @endif
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Product Images -->
                @if($product->images && count($product->images) > 0)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.product_images') }} ({{ count($product->images) }})</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($product->images as $image)
                            <div class="col-md-3 mb-3">
                                <div class="card {{ $image->is_main ? 'border-primary' : '' }}">
                                    @if($image->is_main)
                                        <div class="ribbon-wrapper ribbon-lg">
                                            <div class="ribbon bg-primary">{{ __('admin.main') }}</div>
                                        </div>
                                    @endif
                                    <img src="{{ Storage::url($image->path) }}"
                                         class="card-img-top"
                                         style="height: 200px; object-fit: cover; cursor: pointer;"
                                         data-toggle="modal"
                                         data-target="#imageModal"
                                         data-image="{{ Storage::url($image->path) }}">
                                    <div class="card-footer p-2 text-center">
                                        <small class="text-muted">
                                            {{ __('admin.uploaded') }}: {{ $image->created_at->format('d.m.Y') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- Quick Stats -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.quick_stats') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-info"><i class="fas fa-eye"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('admin.views') }}</span>
                                <span class="info-box-number">{{ $product->views ?? 0 }}</span>
                            </div>
                        </div>

                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success"><i class="fas fa-shopping-cart"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('admin.total_sold') }}</span>
                                <span class="info-box-number">{{ $product->total_sold ?? 0 }}</span>
                            </div>
                        </div>

                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-calendar"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('admin.created') }}</span>
                                <span class="info-box-number">{{ $product->created_at->format('d.m.Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.actions') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="btn-group-vertical w-100">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> {{ __('admin.edit_product') }}
                            </a>

                            @if($product->status == 'active')
                                <button type="button" class="btn btn-warning" onclick="toggleStatus('inactive')">
                                    <i class="fas fa-eye-slash"></i> {{ __('admin.deactivate') }}
                                </button>
                            @else
                                <button type="button" class="btn btn-success" onclick="toggleStatus('active')">
                                    <i class="fas fa-eye"></i> {{ __('admin.activate') }}
                                </button>
                            @endif

                            <button type="button" class="btn btn-info" onclick="duplicateProduct()">
                                <i class="fas fa-copy"></i> {{ __('admin.duplicate') }}
                            </button>

                            <form action="{{ route('admin.products.destroy', $product->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('{{ __('admin.confirm_delete') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-trash"></i> {{ __('admin.delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.recent_activity') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="time-label">
                                <span class="bg-green">{{ $product->created_at->format('d M Y') }}</span>
                            </div>
                            <div>
                                <i class="fas fa-plus bg-blue"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header">{{ __('admin.product_created') }}</h3>
                                    <div class="timeline-body">
                                        {{ __('admin.product_was_created_at') }} {{ $product->created_at->format('H:i') }}
                                    </div>
                                </div>
                            </div>

                            @if($product->updated_at != $product->created_at)
                            <div class="time-label">
                                <span class="bg-yellow">{{ $product->updated_at->format('d M Y') }}</span>
                            </div>
                            <div>
                                <i class="fas fa-edit bg-yellow"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header">{{ __('admin.product_updated') }}</h3>
                                    <div class="timeline-body">
                                        {{ __('admin.product_was_updated_at') }} {{ $product->updated_at->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('admin.product_image') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Image modal
    $('#imageModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const imageSrc = button.data('image');
        const modal = $(this);
        modal.find('#modalImage').attr('src', imageSrc);
    });
});

// Toggle product status
function toggleStatus(status) {
    if (confirm('{{ __("admin.confirm_status_change") }}')) {
        $.ajax({
            url: '{{ route("admin.products.update", $product->id) }}',
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function(response) {
                location.reload();
            },
            error: function() {
                alert('{{ __("admin.error_occurred") }}');
            }
        });
    }
}

// Duplicate product
function duplicateProduct() {
    if (confirm('{{ __("admin.confirm_duplicate") }}')) {
        $.ajax({
            url: '{{ route("admin.products.store") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                duplicate_from: {{ $product->id }}
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = '{{ route("admin.products.edit", "") }}/' + response.product_id;
                }
            },
            error: function() {
                alert('{{ __("admin.error_occurred") }}');
            }
        });
    }
}
</script>
@endpush

@push('styles')
<style>
.ribbon-wrapper {
    position: relative;
    overflow: hidden;
}

.ribbon {
    position: absolute;
    top: 10px;
    right: -30px;
    width: 90px;
    padding: 5px 0;
    background: #007bff;
    color: white;
    text-align: center;
    transform: rotate(45deg);
    font-size: 12px;
    font-weight: bold;
}

.timeline {
    position: relative;
    margin: 0 0 30px 0;
    padding: 0;
    list-style: none;
}

.timeline:before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    width: 4px;
    background: #ddd;
    left: 31px;
    margin: 0;
    border-radius: 2px;
}

.timeline > div {
    position: relative;
    margin: 0 0 15px 0;
    clear: both;
}

.timeline > div > .timeline-item {
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    border-radius: 3px;
    margin-top: 0;
    background: #fff;
    color: #444;
    margin-left: 60px;
    margin-right: 15px;
    padding: 0;
    position: relative;
}

.timeline > div > .fas {
    width: 30px;
    height: 30px;
    font-size: 15px;
    line-height: 30px;
    position: absolute;
    color: #666;
    background: #d2d6de;
    border-radius: 50%;
    text-align: center;
    left: 18px;
    top: 0;
}

.timeline > .time-label > span {
    font-weight: 600;
    color: #fff;
    font-size: 16px;
    padding: 5px 10px;
    display: inline-block;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    border-radius: 4px;
}

.timeline-header {
    margin: 0;
    color: #555;
    border-bottom: 1px solid #f4f4f4;
    padding: 10px;
    font-size: 16px;
    line-height: 1.1;
}

.timeline-body {
    padding: 10px;
    font-size: 14px;
}
</style>
@endpush
