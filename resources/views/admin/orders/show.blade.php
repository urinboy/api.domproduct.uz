@extends('admin.layouts.app')

@section('title', __('admin.orders_module.view_order') . ' #' . $order->order_number)

@section('content')
<style>
.order-badge {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.375rem;
}

.order-status-pending { background-color: #ffc107; color: #000; }
.order-status-confirmed { background-color: #17a2b8; color: #fff; }
.order-status-processing { background-color: #007bff; color: #fff; }
.order-status-shipped { background-color: #6f42c1; color: #fff; }
.order-status-delivered { background-color: #28a745; color: #fff; }
.order-status-cancelled { background-color: #dc3545; color: #fff; }
.order-status-refunded { background-color: #6c757d; color: #fff; }

.payment-status-pending { background-color: #ffc107; color: #000; }
.payment-status-paid { background-color: #28a745; color: #fff; }
.payment-status-failed { background-color: #dc3545; color: #fff; }
.payment-status-refunded { background-color: #6c757d; color: #fff; }

.timeline-item {
    border-left: 2px solid #dee2e6;
    padding-left: 1rem;
    margin-bottom: 1rem;
    position: relative;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: -5px;
    top: 5px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #007bff;
}

.timeline-item.timeline-success:before { background-color: #28a745; }
.timeline-item.timeline-warning:before { background-color: #ffc107; }
.timeline-item.timeline-danger:before { background-color: #dc3545; }
</style>

<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('admin.orders_module.view_order') }} #{{ $order->order_number }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">{{ __('admin.orders') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('admin.orders_module.view_order') }} #{{ $order->order_number }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Action Buttons -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> {{ __('admin.orders_module.edit_order') }}
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('admin.orders_module.back_to_list') }}
                    </a>
                    <button type="button" class="btn btn-info" onclick="window.print()">
                        <i class="fas fa-print"></i> {{ __('admin.orders_module.print_order') }}
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Order Information -->
            <div class="col-md-8">
                <!-- Order Status -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle"></i> {{ __('admin.orders_module.order_information') }}
                        </h3>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">{{ __('admin.orders_module.current_status') }}</label>
                                    <div>
                                        @php
                                            switch($order->status) {
                                                case 'pending': $statusClass = 'order-status-pending'; $statusText = __('admin.orders_module.status_pending'); break;
                                                case 'confirmed': $statusClass = 'order-status-confirmed'; $statusText = __('admin.orders_module.status_confirmed'); break;
                                                case 'processing': $statusClass = 'order-status-processing'; $statusText = __('admin.orders_module.status_processing'); break;
                                                case 'shipped': $statusClass = 'order-status-shipped'; $statusText = __('admin.orders_module.status_shipped'); break;
                                                case 'delivered': $statusClass = 'order-status-delivered'; $statusText = __('admin.orders_module.status_delivered'); break;
                                                case 'cancelled': $statusClass = 'order-status-cancelled'; $statusText = __('admin.orders_module.status_cancelled'); break;
                                                case 'refunded': $statusClass = 'order-status-refunded'; $statusText = __('admin.orders_module.status_refunded'); break;
                                                default: $statusClass = 'order-status-pending'; $statusText = $order->status; break;
                                            }
                                        @endphp
                                        <span class="order-badge {{ $statusClass }}">{{ $statusText }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">{{ __('admin.orders_module.payment_status') }}</label>
                                    <div>
                                        @php
                                            switch($order->payment_status) {
                                                case 'pending': $paymentClass = 'payment-status-pending'; $paymentText = __('admin.orders_module.payment_pending'); break;
                                                case 'paid': $paymentClass = 'payment-status-paid'; $paymentText = __('admin.orders_module.paid'); break;
                                                case 'failed': $paymentClass = 'payment-status-failed'; $paymentText = __('admin.orders_module.payment_failed'); break;
                                                case 'refunded': $paymentClass = 'payment-status-refunded'; $paymentText = __('admin.orders_module.payment_refunded'); break;
                                                default: $paymentClass = 'payment-status-pending'; $paymentText = $order->payment_status; break;
                                            }
                                        @endphp
                                        <span class="order-badge {{ $paymentClass }}">{{ $paymentText }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-box"></i> {{ __('admin.orders_module.order_items') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('admin.orders_module.product_name') }}</th>
                                        <th>{{ __('admin.orders_module.quantity') }}</th>
                                        <th>{{ __('admin.orders_module.unit_price') }}</th>
                                        <th>{{ __('admin.orders_module.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->product && $item->product->image)
                                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" class="rounded mr-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <strong>{{ $item->product_name }}</strong>
                                                        @if($item->product)
                                                            <br><small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->unit_price, 0, '.', ' ') }} UZS</td>
                                            <td><strong>{{ number_format($item->total_price, 0, '.', ' ') }} UZS</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-active">
                                        <th colspan="3" class="text-right">{{ __('admin.orders_module.grand_total') }}:</th>
                                        <th><strong>{{ number_format($order->total_amount, 0, '.', ' ') }} UZS</strong></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Status History -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history"></i> {{ __('admin.orders_module.status_timeline') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($order->statusHistory && $order->statusHistory->count() > 0)
                            @foreach($order->statusHistory->sortByDesc('created_at') as $history)
                                @php
                                    $timelineClass = 'timeline-item';
                                    switch($history->status) {
                                        case 'delivered': $timelineClass .= ' timeline-success'; break;
                                        case 'cancelled': $timelineClass .= ' timeline-danger'; break;
                                        case 'pending': $timelineClass .= ' timeline-warning'; break;
                                    }
                                @endphp
                                <div class="{{ $timelineClass }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>
                                                @php
                                                    switch($history->status) {
                                                        case 'pending': echo __('admin.orders_module.status_pending'); break;
                                                        case 'confirmed': echo __('admin.orders_module.status_confirmed'); break;
                                                        case 'processing': echo __('admin.orders_module.status_processing'); break;
                                                        case 'shipped': echo __('admin.orders_module.status_shipped'); break;
                                                        case 'delivered': echo __('admin.orders_module.status_delivered'); break;
                                                        case 'cancelled': echo __('admin.orders_module.status_cancelled'); break;
                                                        case 'refunded': echo __('admin.orders_module.status_refunded'); break;
                                                        default: echo $history->status; break;
                                                    }
                                                @endphp
                                            </strong>
                                            @if($history->notes)
                                                <p class="text-muted mb-0">{{ $history->notes }}</p>
                                            @endif
                                        </div>
                                        <small class="text-muted">{{ $history->created_at->format('d.m.Y H:i') }}</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">{{ __('admin.orders_module.status_timeline') }} {{ __('admin.orders_module.not_specified') }}.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Customer & Order Details -->
            <div class="col-md-4">
                <!-- Customer Information -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user"></i> {{ __('admin.orders_module.customer_information') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($order->user)
                            <dl class="row">
                                <dt class="col-sm-4">{{ __('admin.name') }}:</dt>
                                <dd class="col-sm-8">{{ $order->user->name }}</dd>

                                <dt class="col-sm-4">{{ __('admin.email') }}:</dt>
                                <dd class="col-sm-8">{{ $order->user->email }}</dd>

                                <dt class="col-sm-4">{{ __('admin.phone') }}:</dt>
                                <dd class="col-sm-8">{{ $order->user->phone ?? __('admin.orders_module.not_specified') }}</dd>
                            </dl>
                        @else
                            <p class="text-muted">{{ __('admin.orders_module.customer_information') }} {{ __('admin.orders_module.not_specified') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Delivery Information -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-shipping-fast"></i> {{ __('admin.orders_module.delivery_information') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-5">{{ __('admin.address') }}:</dt>
                            <dd class="col-sm-7">
                                @if($order->delivery_address)
                                    @php
                                        $deliveryAddress = is_string($order->delivery_address) ? json_decode($order->delivery_address, true) : $order->delivery_address;
                                    @endphp
                                    @if(is_array($deliveryAddress))
                                        <strong>{{ $deliveryAddress['name'] ?? '' }}</strong><br>
                                        {{ $deliveryAddress['address'] ?? '' }}<br>
                                        {{ $deliveryAddress['city'] ?? '' }} {{ $deliveryAddress['postal_code'] ?? '' }}<br>
                                        <small>Tel: {{ $deliveryAddress['phone'] ?? '' }}</small>
                                    @else
                                        {{ $order->delivery_address }}
                                    @endif
                                @else
                                    {{ __('admin.orders_module.not_specified') }}
                                @endif
                            </dd>

                            {{-- <dt class="col-sm-5">{{ __('admin.phone') }}:</dt>
                            <dd class="col-sm-7">{{ $order->delivery_phone ?? __('admin.orders_module.not_specified') }}</dd> --}}

                            <dt class="col-sm-5">{{ __('admin.orders_module.delivery_time') }}:</dt>
                            <dd class="col-sm-7">{{ $order->delivery_time_slot ?? __('admin.orders_module.not_specified') }}</dd>

                            <dt class="col-sm-5">{{ __('admin.orders_module.delivery_notes') }}:</dt>
                            <dd class="col-sm-7">{{ $order->delivery_notes ?? __('admin.orders_module.no_notes') }}</dd>
                        </dl>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-file-alt"></i> {{ __('admin.orders_module.order_details') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-6">{{ __('admin.orders_module.order_number') }}:</dt>
                            <dd class="col-sm-6">#{{ $order->order_number }}</dd>

                            <dt class="col-sm-6">{{ __('admin.orders_module.order_date') }}:</dt>
                            <dd class="col-sm-6">{{ $order->created_at->format('d.m.Y H:i') }}</dd>

                            <dt class="col-sm-6">{{ __('admin.orders_module.payment_method') }}:</dt>
                            <dd class="col-sm-6">
                                @switch($order->payment_method)
                                    @case('cash')
                                        {{ __('admin.orders_module.cash') }}
                                        @break
                                    @case('card')
                                        {{ __('admin.orders_module.card') }}
                                        @break
                                    @case('online')
                                        {{ __('admin.orders_module.online') }}
                                        @break
                                    @default
                                        {{ $order->payment_method }}
                                @endswitch
                            </dd>

                            <dt class="col-sm-6">{{ __('admin.orders_module.total_amount') }}:</dt>
                            <dd class="col-sm-6"><strong>{{ number_format($order->total_amount, 0, '.', ' ') }} UZS</strong></dd>
                        </dl>

                        @if($order->notes)
                            <hr>
                            <dt>{{ __('admin.orders_module.additional_notes') }}:</dt>
                            <dd class="text-muted">{{ $order->notes }}</dd>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
