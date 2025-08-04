@extends('admin.layouts.app')

@section('title', 'Buyurtma #' . $order->order_number)

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
                <h1 class="m-0">Buyurtma #{{ $order->order_number }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Bosh sahifa</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Buyurtmalar</a></li>
                    <li class="breadcrumb-item active">Buyurtma #{{ $order->order_number }}</li>
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
                        <i class="fas fa-edit"></i> Tahrirlash
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Orqaga
                    </a>
                    <button type="button" class="btn btn-info" onclick="window.print()">
                        <i class="fas fa-print"></i> Chop etish
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
                            <i class="fas fa-info-circle"></i> Buyurtma holati
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">Joriy holat</label>
                                    <div>
                                        @php
                                            switch($order->status) {
                                                case 'pending': $statusClass = 'order-status-pending'; $statusText = 'Kutilmoqda'; break;
                                                case 'confirmed': $statusClass = 'order-status-confirmed'; $statusText = 'Tasdiqlangan'; break;
                                                case 'processing': $statusClass = 'order-status-processing'; $statusText = 'Jarayonda'; break;
                                                case 'shipped': $statusClass = 'order-status-shipped'; $statusText = 'Yuborilgan'; break;
                                                case 'delivered': $statusClass = 'order-status-delivered'; $statusText = 'Yetkazilgan'; break;
                                                case 'cancelled': $statusClass = 'order-status-cancelled'; $statusText = 'Bekor qilingan'; break;
                                                case 'refunded': $statusClass = 'order-status-refunded'; $statusText = 'Qaytarilgan'; break;
                                                default: $statusClass = 'order-status-pending'; $statusText = $order->status; break;
                                            }
                                        @endphp
                                        <span class="order-badge {{ $statusClass }}">{{ $statusText }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">To'lov holati</label>
                                    <div>
                                        @php
                                            switch($order->payment_status) {
                                                case 'pending': $paymentClass = 'payment-status-pending'; $paymentText = 'Kutilmoqda'; break;
                                                case 'paid': $paymentClass = 'payment-status-paid'; $paymentText = 'To\'langan'; break;
                                                case 'failed': $paymentClass = 'payment-status-failed'; $paymentText = 'Muvaffaqiyatsiz'; break;
                                                case 'refunded': $paymentClass = 'payment-status-refunded'; $paymentText = 'Qaytarilgan'; break;
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
                            <i class="fas fa-box"></i> Buyurtma mahsulotlari
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Mahsulot</th>
                                        <th>Miqdor</th>
                                        <th>Narx</th>
                                        <th>Jami</th>
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
                                        <th colspan="3" class="text-right">Jami summa:</th>
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
                            <i class="fas fa-history"></i> Holat tarixi
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
                                                        case 'pending': echo 'Kutilmoqda'; break;
                                                        case 'confirmed': echo 'Tasdiqlangan'; break;
                                                        case 'processing': echo 'Jarayonda'; break;
                                                        case 'shipped': echo 'Yuborilgan'; break;
                                                        case 'delivered': echo 'Yetkazilgan'; break;
                                                        case 'cancelled': echo 'Bekor qilingan'; break;
                                                        case 'refunded': echo 'Qaytarilgan'; break;
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
                            <p class="text-muted">Holat tarixi mavjud emas.</p>
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
                            <i class="fas fa-user"></i> Mijoz ma'lumotlari
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($order->user)
                            <dl class="row">
                                <dt class="col-sm-4">Ism:</dt>
                                <dd class="col-sm-8">{{ $order->user->name }}</dd>

                                <dt class="col-sm-4">Email:</dt>
                                <dd class="col-sm-8">{{ $order->user->email }}</dd>

                                <dt class="col-sm-4">Telefon:</dt>
                                <dd class="col-sm-8">{{ $order->user->phone ?? 'Ko\'rsatilmagan' }}</dd>
                            </dl>
                        @else
                            <p class="text-muted">Mijoz ma'lumotlari topilmadi</p>
                        @endif
                    </div>
                </div>

                <!-- Delivery Information -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-shipping-fast"></i> Yetkazib berish
                        </h3>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-5">Manzil:</dt>
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
                                    Ko'rsatilmagan
                                @endif
                            </dd>

                            {{-- <dt class="col-sm-5">Telefon:</dt>
                            <dd class="col-sm-7">{{ $order->delivery_phone ?? 'Ko\'rsatilmagan' }}</dd> --}}

                            <dt class="col-sm-5">Vaqt:</dt>
                            <dd class="col-sm-7">{{ $order->delivery_time_slot ?? 'Ko\'rsatilmagan' }}</dd>

                            <dt class="col-sm-5">Eslatma:</dt>
                            <dd class="col-sm-7">{{ $order->delivery_notes ?? 'Yo\'q' }}</dd>
                        </dl>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-file-alt"></i> Buyurtma tafsilotlari
                        </h3>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-6">Buyurtma raqami:</dt>
                            <dd class="col-sm-6">#{{ $order->order_number }}</dd>

                            <dt class="col-sm-6">Sana:</dt>
                            <dd class="col-sm-6">{{ $order->created_at->format('d.m.Y H:i') }}</dd>

                            <dt class="col-sm-6">To'lov turi:</dt>
                            <dd class="col-sm-6">
                                @switch($order->payment_method)
                                    @case('cash')
                                        Naqd pul
                                        @break
                                    @case('card')
                                        Plastik karta
                                        @break
                                    @case('online')
                                        Onlayn to'lov
                                        @break
                                    @default
                                        {{ $order->payment_method }}
                                @endswitch
                            </dd>

                            <dt class="col-sm-6">Jami summa:</dt>
                            <dd class="col-sm-6"><strong>{{ number_format($order->total_amount, 0, '.', ' ') }} UZS</strong></dd>
                        </dl>

                        @if($order->notes)
                            <hr>
                            <dt>Qo'shimcha eslatmalar:</dt>
                            <dd class="text-muted">{{ $order->notes }}</dd>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
