@extends('admin.layouts.app')

@section('title', 'Buyurtmalar boshqaruvi')

@push('styles')
<style>
    .order-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
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

    .stats-card {
        border-radius: 0.5rem;
        transition: transform 0.2s;
    }
    .stats-card:hover {
        transform: translateY(-2px);
    }
    .table-actions {
        white-space: nowrap;
    }
    .order-search-filters {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 0.375rem;
        margin-bottom: 1rem;
    }
</style>
@endpush

@extends('admin.layouts.app')

@section('title', 'Buyurtmalar')

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

.table-actions .btn-group {
    display: inline-flex;
}

.filter-section {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    margin-bottom: 1rem;
}
</style>
<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Buyurtmalar boshqaruvi</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Bosh sahifa</a></li>
                    <li class="breadcrumb-item active">Buyurtmalar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row mb-3">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info stats-card">
                    <div class="inner">
                        <h3>{{ \App\Models\Order::count() }}</h3>
                        <p>Jami buyurtmalar</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning stats-card">
                    <div class="inner">
                        <h3>{{ \App\Models\Order::where('status', 'pending')->count() }}</h3>
                        <p>Kutilmoqda</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success stats-card">
                    <div class="inner">
                        <h3>{{ \App\Models\Order::where('status', 'delivered')->count() }}</h3>
                        <p>Yetkazilgan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-gradient-purple stats-card">
                    <div class="inner">
                        <h3>{{ number_format(\App\Models\Order::where('payment_status', 'paid')->sum('total_amount'), 0, '.', ' ') }}</h3>
                        <p>Jami daromad (UZS)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Buyurtmalar ro'yxati</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.orders.export') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-download"></i> CSV ga eksport
                            </a>
                        </div>
                    </div>

                    <!-- Filter Form -->
                    <div class="card-body order-search-filters">
                        <form method="GET" action="{{ route('admin.orders.index') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">Barchasi</option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Kutilmoqda</option>
                                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Tasdiqlangan</option>
                                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Jarayonda</option>
                                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Yuborilgan</option>
                                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Yetkazilgan</option>
                                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Bekor qilingan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>To'lov holati</label>
                                        <select name="payment_status" class="form-control">
                                            <option value="">Barchasi</option>
                                            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Kutilmoqda</option>
                                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>To'langan</option>
                                            <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Muvaffaqiyatsiz</option>
                                            <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Qaytarilgan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Qidiruv</label>
                                        <input type="text" name="search" class="form-control" placeholder="Buyurtma raqami, mijoz nomi..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fas fa-search"></i> Qidirish
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-times"></i> Tozalash
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Orders Table -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Buyurtma</th>
                                        <th>Mijoz</th>
                                        <th>Status</th>
                                        <th>To'lov</th>
                                        <th>Summa</th>
                                        <th>Sana</th>
                                        <th class="table-actions">Amallar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $index => $order)
                                        <tr>
                                            <td>{{ $orders->firstItem() + $index }}</td>
                                            <td>
                                                <div>
                                                    <strong>#{{ $order->order_number }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $order->items->count() }} mahsulot</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($order->user_id)
                                                    <div>
                                                        <strong>{{ $order->user->first_name }} {{ $order->user->last_name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $order->user->email }}</small>
                                                    </div>
                                                @else
                                                    <div>
                                                        <strong>Mehmon</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $order->guest_email }}</small>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    switch($order->status) {
                                                        case 'pending': $statusClass = 'order-badge order-status-pending'; $statusText = 'Kutilmoqda'; break;
                                                        case 'confirmed': $statusClass = 'order-badge order-status-confirmed'; $statusText = 'Tasdiqlangan'; break;
                                                        case 'processing': $statusClass = 'order-badge order-status-processing'; $statusText = 'Jarayonda'; break;
                                                        case 'shipped': $statusClass = 'order-badge order-status-shipped'; $statusText = 'Yuborilgan'; break;
                                                        case 'delivered': $statusClass = 'order-badge order-status-delivered'; $statusText = 'Yetkazilgan'; break;
                                                        case 'cancelled': $statusClass = 'order-badge order-status-cancelled'; $statusText = 'Bekor qilingan'; break;
                                                        case 'refunded': $statusClass = 'order-badge order-status-refunded'; $statusText = 'Qaytarilgan'; break;
                                                        default: $statusClass = 'order-badge order-status-pending'; $statusText = $order->status; break;
                                                    }
                                                @endphp
                                                <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    switch($order->payment_status) {
                                                        case 'pending': $paymentClass = 'order-badge payment-status-pending'; $paymentText = 'Kutilmoqda'; break;
                                                        case 'paid': $paymentClass = 'order-badge payment-status-paid'; $paymentText = 'To\'langan'; break;
                                                        case 'failed': $paymentClass = 'order-badge payment-status-failed'; $paymentText = 'Muvaffaqiyatsiz'; break;
                                                        case 'refunded': $paymentClass = 'order-badge payment-status-refunded'; $paymentText = 'Qaytarilgan'; break;
                                                        default: $paymentClass = 'order-badge payment-status-pending'; $paymentText = $order->payment_status; break;
                                                    }
                                                @endphp
                                                <span class="{{ $paymentClass }}">{{ $paymentText }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ number_format($order->total_amount, 0, '.', ' ') }} UZS</strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $order->created_at->format('d.m.Y H:i') }}</small>
                                            </td>
                                            <td class="table-actions">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-primary" title="Ko'rish">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-outline-secondary" title="Tahrirlash">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button type="button" class="btn btn-outline-success dropdown-toggle" data-toggle="dropdown" title="Tez amallar">
                                                            <i class="fas fa-cog"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            @if($order->status === 'pending')
                                                                <a class="dropdown-item text-success" href="#" onclick="updateStatus({{ $order->id }}, 'confirmed')">
                                                                    <i class="fas fa-check"></i> Tasdiqlash
                                                                </a>
                                                            @endif
                                                            @if(in_array($order->status, ['confirmed', 'processing']))
                                                                <a class="dropdown-item text-primary" href="#" onclick="updateStatus({{ $order->id }}, 'shipped')">
                                                                    <i class="fas fa-shipping-fast"></i> Yuborish
                                                                </a>
                                                            @endif
                                                            @if($order->status === 'shipped')
                                                                <a class="dropdown-item text-success" href="#" onclick="updateStatus({{ $order->id }}, 'delivered')">
                                                                    <i class="fas fa-check-circle"></i> Yetkazildi
                                                                </a>
                                                            @endif
                                                            @if(in_array($order->status, ['pending', 'confirmed']))
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item text-danger" href="#" onclick="updateStatus({{ $order->id }}, 'cancelled')">
                                                                    <i class="fas fa-times"></i> Bekor qilish
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="text-center">
                                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">Buyurtmalar topilmadi</h5>
                                                    <p class="text-muted">Hech qanday buyurtma topilmadi. Filtrlarni o'zgartirib ko'ring.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if($orders->hasPages())
                        <div class="card-footer">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        {{ $orders->firstItem() }} dan {{ $orders->lastItem() }} gacha, jami {{ $orders->total() }} ta natija
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <nav aria-label="Sahifalar">
                                        {{ $orders->appends(request()->query())->links() }}
                                    </nav>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function updateStatus(orderId, status) {
    if (confirm('Buyurtma holatini o\'zgartirishni xohlaysizmi?')) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: `/admin/orders/${orderId}/status`,
            method: 'PATCH',
            data: {
                status: status
            },
            success: function(data) {
                if (data.success) {
                    toastr.success(data.message);
                    location.reload();
                } else {
                    toastr.error('Xatolik yuz berdi');
                }
            },
            error: function() {
                toastr.error('Xatolik yuz berdi');
            }
        });
    }
}
</script>
@endsection
