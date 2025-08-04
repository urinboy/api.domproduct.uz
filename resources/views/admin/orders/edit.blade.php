@extends('admin.layouts.app')

@section('title', __('admin.orders_module.edit_order') . ' #' . $order->order_number)

@section('content')
<style>
.order-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    font-weight: 500;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
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
</style>

<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('admin.orders_module.edit_order') }} #{{ $order->order_number }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">{{ __('admin.orders') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.show', $order) }}">{{ __('admin.orders_module.view_order') }} #{{ $order->order_number }}</a></li>
                    <li class="breadcrumb-item active">{{ __('admin.orders_module.edit') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.orders.update', $order) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <!-- Order Status -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-edit"></i> {{ __('admin.orders_module.order_information') }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status" class="form-label">{{ __('admin.orders_module.status') }} <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                            <option value="">{{ __('admin.select') }}</option>
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>{{ __('admin.orders_module.pending') }}</option>
                                            <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>{{ __('admin.orders_module.confirmed') }}</option>
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>{{ __('admin.orders_module.preparing') }}</option>
                                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>{{ __('admin.orders_module.out_for_delivery') }}</option>
                                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>{{ __('admin.orders_module.delivered') }}</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>{{ __('admin.orders_module.cancelled') }}</option>
                                            <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>{{ __('admin.orders_module.refunded') }}</option>
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment_status" class="form-label">{{ __('admin.orders_module.payment_status') }} <span class="text-danger">*</span></label>
                                        <select name="payment_status" id="payment_status" class="form-control @error('payment_status') is-invalid @enderror" required>
                                            <option value="">{{ __('admin.select') }}</option>
                                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>{{ __('admin.orders_module.payment_pending') }}</option>
                                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>{{ __('admin.orders_module.paid') }}</option>
                                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>{{ __('admin.orders_module.payment_failed') }}</option>
                                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>{{ __('admin.orders_module.payment_refunded') }}</option>
                                        </select>
                                        @error('payment_status')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="status_notes" class="form-label">{{ __('admin.orders_module.status_change_notes') }}</label>
                                <textarea name="status_notes" id="status_notes" class="form-control @error('status_notes') is-invalid @enderror" rows="3" placeholder="{{ __('admin.orders_module.change_status_note') }}"></textarea>
                                @error('status_notes')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="delivery_phone" class="form-label">{{ __('admin.orders_module.delivery_phone') }}</label>
                                        <input type="text" name="delivery_phone" id="delivery_phone" class="form-control @error('delivery_phone') is-invalid @enderror" value="{{ old('delivery_phone', $order->delivery_phone) }}" placeholder="+998901234567">
                                        @error('delivery_phone')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="delivery_time_slot" class="form-label">{{ __('admin.orders_module.delivery_time') }}</label>
                                        <input type="text" name="delivery_time_slot" id="delivery_time_slot" class="form-control @error('delivery_time_slot') is-invalid @enderror" value="{{ old('delivery_time_slot', $order->delivery_time_slot) }}" placeholder="{{ __('admin.orders_module.delivery_time_placeholder') }}">
                                        @error('delivery_time_slot')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="delivery_address" class="form-label">{{ __('admin.orders_module.delivery_address') }}</label>
                                @php
                                    $deliveryAddressText = '';
                                    if ($order->delivery_address) {
                                        if (is_string($order->delivery_address)) {
                                            $decoded = json_decode($order->delivery_address, true);
                                            if ($decoded && is_array($decoded)) {
                                                $deliveryAddressText = $decoded['address'] ?? $decoded['name'] ?? $order->delivery_address;
                                            } else {
                                                $deliveryAddressText = $order->delivery_address;
                                            }
                                        } else {
                                            $deliveryAddressText = $order->delivery_address;
                                        }
                                    }
                                @endphp
                                <textarea name="delivery_address" id="delivery_address" class="form-control @error('delivery_address') is-invalid @enderror" rows="3">{{ old('delivery_address', $deliveryAddressText) }}</textarea>
                                @error('delivery_address')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="delivery_notes" class="form-label">{{ __('admin.orders_module.delivery_notes') }}</label>
                                <textarea name="delivery_notes" id="delivery_notes" class="form-control @error('delivery_notes') is-invalid @enderror" rows="3">{{ old('delivery_notes', $order->delivery_notes) }}</textarea>
                                @error('delivery_notes')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Order Notes -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-sticky-note"></i> {{ __('admin.orders_module.additional_notes') }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="notes" class="form-label">{{ __('admin.orders_module.order_about_notes') }}</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="4">{{ old('notes', $order->notes) }}</textarea>
                                @error('notes')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Information Sidebar -->
                <div class="col-md-4">
                    <!-- Current Status -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle"></i> {{ __('admin.orders_module.current_information') }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-6">{{ __('admin.orders_module.order') }}:</dt>
                                <dd class="col-sm-6">#{{ $order->order_number }}</dd>

                                <dt class="col-sm-6">{{ __('admin.orders_module.status') }}:</dt>
                                <dd class="col-sm-6">
                                    @php
                                        switch($order->status) {
                                            case 'pending': $statusClass = 'order-status-pending'; $statusText = __('admin.orders_module.pending'); break;
                                            case 'confirmed': $statusClass = 'order-status-confirmed'; $statusText = __('admin.orders_module.confirmed'); break;
                                            case 'processing': $statusClass = 'order-status-processing'; $statusText = __('admin.orders_module.preparing'); break;
                                            case 'shipped': $statusClass = 'order-status-shipped'; $statusText = __('admin.orders_module.out_for_delivery'); break;
                                            case 'delivered': $statusClass = 'order-status-delivered'; $statusText = __('admin.orders_module.delivered'); break;
                                            case 'cancelled': $statusClass = 'order-status-cancelled'; $statusText = __('admin.orders_module.cancelled'); break;
                                            case 'refunded': $statusClass = 'order-status-refunded'; $statusText = __('admin.orders_module.refunded'); break;
                                            default: $statusClass = 'order-status-pending'; $statusText = $order->status; break;
                                        }
                                    @endphp
                                    <span class="order-badge {{ $statusClass }}">{{ $statusText }}</span>
                                </dd>

                                <dt class="col-sm-6">{{ __('admin.orders_module.payment_status') }}:</dt>
                                <dd class="col-sm-6">
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
                                </dd>

                                <dt class="col-sm-6">{{ __('admin.orders_module.amount') }}:</dt>
                                <dd class="col-sm-6"><strong>{{ number_format($order->total_amount, 0, '.', ' ') }} UZS</strong></dd>

                                <dt class="col-sm-6">{{ __('admin.orders_module.date') }}:</dt>
                                <dd class="col-sm-6">{{ $order->created_at->format('d.m.Y H:i') }}</dd>
                            </dl>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user"></i> {{ __('admin.orders_module.customer') }}
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($order->user)
                                <dl class="row">
                                    <dt class="col-sm-4">{{ __('admin.orders_module.name') }}:</dt>
                                    <dd class="col-sm-8">{{ $order->user->name }}</dd>

                                    <dt class="col-sm-4">Email:</dt>
                                    <dd class="col-sm-8">{{ $order->user->email }}</dd>

                                    <dt class="col-sm-4">{{ __('admin.orders_module.phone') }}:</dt>
                                    <dd class="col-sm-8">{{ $order->user->phone ?? __('admin.orders_module.not_specified') }}</dd>
                                </dl>
                            @else
                                <p class="text-muted">{{ __('admin.orders_module.customer_not_found') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Order Items Summary -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-box"></i> {{ __('admin.orders_module.products') }} ({{ $order->items ? $order->items->count() : 0 }})
                            </h3>
                        </div>
                        <div class="card-body">
                            @foreach($order->items as $item)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <small><strong>{{ $item->product_name }}</strong></small>
                                        <br><small class="text-muted">{{ $item->quantity }} x {{ number_format($item->unit_price, 0, '.', ' ') }} UZS</small>
                                    </div>
                                    <small class="font-weight-bold">{{ number_format($item->total_price, 0, '.', ' ') }} UZS</small>
                                </div>
                                @if(!$loop->last)<hr class="my-2">@endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ __('admin.orders_module.save_changes') }}
                                </button>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> {{ __('admin.orders_module.cancel') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
