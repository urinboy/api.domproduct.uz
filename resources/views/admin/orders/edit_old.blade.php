@extends('admin.layouts.app')

@section('title', 'Buyurtmani tahrirlash #' . $order->order_number)

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">
                Buyurtmani tahrirlash
            </div>
            <h2 class="page-title">
                #{{ $order->order_number }}
            </h2>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <polyline points="15,18 9,12 15,6"/>
                    </svg>
                    Orqaga
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <form action="{{ route('admin.orders.update', $order) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <!-- Order Status -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Buyurtma holati</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Buyurtma holati</label>
                                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                            <option value="">Tanlang</option>
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Kutilmoqda</option>
                                            <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Tasdiqlangan</option>
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Jarayonda</option>
                                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Yuborilgan</option>
                                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Yetkazilgan</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Bekor qilingan</option>
                                            <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>Qaytarilgan</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">To'lov holati</label>
                                        <select name="payment_status" class="form-select">
                                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Kutilmoqda</option>
                                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>To'langan</option>
                                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Muvaffaqiyatsiz</option>
                                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Qaytarilgan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Kuzatuv raqami</label>
                                        <input type="text" name="tracking_number" class="form-control" value="{{ $order->tracking_number }}" placeholder="Kuzatuv raqamini kiriting">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">To'lov usuli</label>
                                        <select name="payment_method" class="form-select">
                                            <option value="">Tanlang</option>
                                            <option value="cash" {{ $order->payment_method === 'cash' ? 'selected' : '' }}>Naqd pul</option>
                                            <option value="card" {{ $order->payment_method === 'card' ? 'selected' : '' }}>Plastik karta</option>
                                            <option value="uzcard" {{ $order->payment_method === 'uzcard' ? 'selected' : '' }}>UzCard</option>
                                            <option value="humo" {{ $order->payment_method === 'humo' ? 'selected' : '' }}>Humo</option>
                                            <option value="payme" {{ $order->payment_method === 'payme' ? 'selected' : '' }}>Payme</option>
                                            <option value="click" {{ $order->payment_method === 'click' ? 'selected' : '' }}>Click</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Information -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Yetkazib berish ma'lumotlari</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Yetkazib berish usuli</label>
                                        <select name="delivery_method" class="form-select">
                                            <option value="">Tanlang</option>
                                            <option value="pickup" {{ $order->delivery_method === 'pickup' ? 'selected' : '' }}>Olib ketish</option>
                                            <option value="delivery" {{ $order->delivery_method === 'delivery' ? 'selected' : '' }}>Yetkazib berish</option>
                                            <option value="express" {{ $order->delivery_method === 'express' ? 'selected' : '' }}>Tezkor yetkazib berish</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Yetkazib berish narxi</label>
                                        <input type="number" name="delivery_fee" class="form-control" value="{{ $order->delivery_fee }}" min="0" step="0.01">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Yetkazib berish sanasi</label>
                                        <input type="date" name="delivery_date" class="form-control" value="{{ $order->delivery_date ? $order->delivery_date->format('Y-m-d') : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Vaqt oralig'i</label>
                                        <input type="text" name="delivery_time_slot" class="form-control" value="{{ $order->delivery_time_slot }}" placeholder="Masalan: 9:00-12:00">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Special Instructions -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Qo'shimcha ma'lumotlar</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Maxsus ko'rsatmalar</label>
                                <textarea name="special_instructions" class="form-control" rows="3" readonly>{{ $order->special_instructions }}</textarea>
                                <small class="form-hint">Mijoz tomonidan qoldirilgan ko'rsatmalar</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Admin eslatmalari</label>
                                <textarea name="order_notes" class="form-control @error('order_notes') is-invalid @enderror" rows="3" placeholder="Ichki eslatmalar va izohlar">{{ $order->order_notes }}</textarea>
                                @error('order_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-hint">Faqat adminlar ko'radigan eslatmalar</small>
                            </div>

                            <div class="mb-0">
                                <label class="form-label">Holat o'zgarishi haqida izoh</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Holat o'zgarishiga sabab yoki qo'shimcha ma'lumot">{{ old('notes') }}</textarea>
                                <small class="form-hint">Bu izoh holatlar tarixiga qo'shiladi</small>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items (Read Only) -->
                    <div class="card">
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

                                <dt class="col-5">Oxirgi yangilanish:</dt>
                                <dd class="col-7">{{ $order->updated_at->format('d.m.Y H:i') }}</dd>
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

                    <!-- Action Buttons -->
                    <div class="card">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M5 12l5 5l10 -10"/>
                                    </svg>
                                    O'zgarishlarni saqlash
                                </button>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-secondary">
                                    Bekor qilish
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
