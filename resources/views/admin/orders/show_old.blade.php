@extends('admin.layouts.master')

@section('title', 'Buyurtma #' . $order->order_number)

@section('content')
<style>
.order-badge                        </div>
                    </div>
                </div> display: inline-block;
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
                                                case 'pending': $paymentClass = 'bg-warning'; $paymentText = 'Kutilmoqda'; break;
                                                case 'paid': $paymentClass = 'bg-success'; $paymentText = 'To\'langan'; break;
                                                case 'failed': $paymentClass = 'bg-danger'; $paymentText = 'Muvaffaqiyatsiz'; break;
                                                case 'refunded': $paymentClass = 'bg-secondary'; $paymentText = 'Qaytarilgan'; break;
                                                default: $paymentClass = 'bg-secondary'; $paymentText = $order->payment_status; break;
                                            }
                                        @endphp
                                        <span class="badge {{ $paymentClass }} badge-lg">{{ $paymentText }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($order->status !== 'delivered' && $order->status !== 'cancelled')
                            <div class="mt-3">
                                <label class="form-label">Holatni o'zgartirish</label>
                                <div class="btn-group" role="group">
                                    @if($order->status === 'pending')
                                        <button type="button" class="btn btn-success btn-sm" onclick="updateStatus('confirmed')">
                                            Tasdiqlash
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="updateStatus('cancelled')">
                                            Bekor qilish
                                        </button>
                                    @elseif($order->status === 'confirmed')
                                        <button type="button" class="btn btn-primary btn-sm" onclick="updateStatus('processing')">
                                            Jarayonga o'tkazish
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="updateStatus('cancelled')">
                                            Bekor qilish
                                        </button>
                                    @elseif($order->status === 'processing')
                                        <button type="button" class="btn btn-indigo btn-sm" onclick="updateStatus('shipped')">
                                            Yuborish
                                        </button>
                                    @elseif($order->status === 'shipped')
                                        <button type="button" class="btn btn-success btn-sm" onclick="updateStatus('delivered')">
                                            Yetkazildi
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Mahsulotlar</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
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
                                            <div class="d-flex py-1 align-items-center">
                                                @if($item->product && $item->product->image)
                                                    <span class="avatar me-2" style="background-image: url({{ asset('storage/' . $item->product->image) }})"></span>
                                                @else
                                                    <span class="avatar me-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                                            <circle cx="8.5" cy="8.5" r="1.5"/>
                                                            <polyline points="21,15 16,10 5,21"/>
                                                        </svg>
                                                    </span>
                                                @endif
                                                <div class="flex-fill">
                                                    <div class="font-weight-medium">
                                                        {{ $item->product ? $item->product->name : 'Mahsulot topilmadi' }}
                                                    </div>
                                                    @if($item->product)
                                                        <div class="text-muted">SKU: {{ $item->product->sku }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $item->quantity }}</span>
                                        </td>
                                        <td>
                                            {{ number_format($item->unit_price, 0, '.', ' ') }} UZS
                                        </td>
                                        <td>
                                            <strong>{{ number_format($item->total_price, 0, '.', ' ') }} UZS</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Oraliq summa:</strong></td>
                                    <td><strong>{{ number_format($order->subtotal, 0, '.', ' ') }} UZS</strong></td>
                                </tr>
                                @if($order->discount_amount > 0)
                                    <tr>
                                        <td colspan="3" class="text-end">Chegirma:</td>
                                        <td class="text-success">-{{ number_format($order->discount_amount, 0, '.', ' ') }} UZS</td>
                                    </tr>
                                @endif
                                @if($order->delivery_fee > 0)
                                    <tr>
                                        <td colspan="3" class="text-end">Yetkazib berish:</td>
                                        <td>{{ number_format($order->delivery_fee, 0, '.', ' ') }} UZS</td>
                                    </tr>
                                @endif
                                <tr class="table-active">
                                    <td colspan="3" class="text-end"><strong>Jami:</strong></td>
                                    <td><strong>{{ number_format($order->total_amount, 0, '.', ' ') }} UZS</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Status History -->
                @if($order->statusHistory && $order->statusHistory->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Holatlar tarixi</h3>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                @foreach($order->statusHistory->sortByDesc('created_at') as $history)
                                    <div class="timeline-item">
                                        <div class="timeline-point timeline-point-primary"></div>
                                        <div class="timeline-content">
                                            <div class="timeline-time">{{ $history->created_at->format('d.m.Y H:i') }}</div>
                                            <div class="timeline-title">
                                                @php
                                                    switch($history->to_status) {
                                                        case 'pending': $statusText = 'Kutilmoqda'; break;
                                                        case 'confirmed': $statusText = 'Tasdiqlangan'; break;
                                                        case 'processing': $statusText = 'Jarayonda'; break;
                                                        case 'shipped': $statusText = 'Yuborilgan'; break;
                                                        case 'delivered': $statusText = 'Yetkazilgan'; break;
                                                        case 'cancelled': $statusText = 'Bekor qilingan'; break;
                                                        case 'refunded': $statusText = 'Qaytarilgan'; break;
                                                        default: $statusText = $history->to_status; break;
                                                    }
                                                @endphp
                                                Holat o'zgartirildi: <strong>{{ $statusText }}</strong>
                                            </div>
                                            @if($history->notes)
                                                <div class="timeline-body text-muted">
                                                    {{ $history->notes }}
                                                </div>
                                            @endif
                                            @if($history->user)
                                                <div class="timeline-body text-muted">
                                                    O'zgartirgan: {{ $history->user->name ?? $history->user->first_name }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Order Details Sidebar -->
            <div class="col-md-4">
                <!-- Customer Information -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Mijoz ma'lumotlari</h3>
                    </div>
                    <div class="card-body">
                        @if($order->user_id)
                            <div class="d-flex align-items-center mb-3">
                                <span class="avatar bg-primary text-white me-3">
                                    {{ strtoupper(substr($order->user->first_name, 0, 1)) }}{{ strtoupper(substr($order->user->last_name, 0, 1)) }}
                                </span>
                                <div>
                                    <div class="font-weight-medium">{{ $order->user->first_name }} {{ $order->user->last_name }}</div>
                                    <div class="text-muted">{{ $order->user->email }}</div>
                                    @if($order->user->phone)
                                        <div class="text-muted">{{ $order->user->phone }}</div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="d-flex align-items-center mb-3">
                                <span class="avatar bg-secondary text-white me-3">M</span>
                                <div>
                                    <div class="font-weight-medium">Mehmon</div>
                                    <div class="text-muted">{{ $order->guest_email }}</div>
                                    @if($order->guest_phone)
                                        <div class="text-muted">{{ $order->guest_phone }}</div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Details -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Buyurtma ma'lumotlari</h3>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-5">Buyurtma raqami:</dt>
                            <dd class="col-7">{{ $order->order_number }}</dd>

                            <dt class="col-5">Sana:</dt>
                            <dd class="col-7">{{ $order->created_at->format('d.m.Y H:i') }}</dd>

                            @if($order->payment_method)
                                <dt class="col-5">To'lov usuli:</dt>
                                <dd class="col-7">{{ $order->payment_method }}</dd>
                            @endif

                            @if($order->delivery_method)
                                <dt class="col-5">Yetkazib berish:</dt>
                                <dd class="col-7">{{ $order->delivery_method }}</dd>
                            @endif

                            @if($order->tracking_number)
                                <dt class="col-5">Kuzatuv raqami:</dt>
                                <dd class="col-7">{{ $order->tracking_number }}</dd>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Delivery Address -->
                @if($order->delivery_address)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Yetkazib berish manzili</h3>
                        </div>
                        <div class="card-body">
                            @php
                                $deliveryAddress = is_string($order->delivery_address) ? json_decode($order->delivery_address, true) : $order->delivery_address;
                            @endphp
                            @if(is_array($deliveryAddress))
                                <address class="mb-0">
                                    @if(isset($deliveryAddress['name']))
                                        <strong>{{ $deliveryAddress['name'] }}</strong><br>
                                    @endif
                                    @if(isset($deliveryAddress['address']))
                                        {{ $deliveryAddress['address'] }}<br>
                                    @endif
                                    @if(isset($deliveryAddress['city']))
                                        {{ $deliveryAddress['city'] }}
                                    @endif
                                    @if(isset($deliveryAddress['postal_code']))
                                        {{ $deliveryAddress['postal_code'] }}
                                    @endif
                                    @if(isset($deliveryAddress['phone']))
                                        <br>Tel: {{ $deliveryAddress['phone'] }}
                                    @endif
                                </address>
                            @else
                                <p class="mb-0">{{ $order->delivery_address }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Notes -->
                @if($order->special_instructions || $order->order_notes)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Qo'shimcha ma'lumotlar</h3>
                        </div>
                        <div class="card-body">
                            @if($order->special_instructions)
                                <div class="mb-3">
                                    <label class="form-label">Maxsus ko'rsatmalar:</label>
                                    <p class="text-muted mb-0">{{ $order->special_instructions }}</p>
                                </div>
                            @endif
                            @if($order->order_notes)
                                <div>
                                    <label class="form-label">Admin eslatmalari:</label>
                                    <p class="text-muted mb-0">{{ $order->order_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(status) {
    if (confirm('Buyurtma holatini o\'zgartirishni xohlaysizmi?')) {
        fetch(`{{ route('admin.orders.update-status', $order) }}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Xatolik yuz berdi');
            }
        });
    }
}
</script>
@endsection
