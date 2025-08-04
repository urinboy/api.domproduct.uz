<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhere('guest_email', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->search . '%')
                               ->orWhere('email', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $orders = $query->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'statusHistory']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $order->load(['user', 'items.product']);

        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded',
            'tracking_number' => 'nullable|string|max:255',
            'payment_method' => 'nullable|string|max:50',
            'delivery_method' => 'nullable|string|max:50',
            'delivery_fee' => 'nullable|numeric|min:0',
            'delivery_date' => 'nullable|date',
            'delivery_time_slot' => 'nullable|string|max:100',
            'order_notes' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $order->status;
        $oldPaymentStatus = $order->payment_status;

        // Update order
        $updateData = [
            'status' => $request->status,
            'order_notes' => $request->order_notes,
        ];

        if ($request->filled('payment_status')) {
            $updateData['payment_status'] = $request->payment_status;
        }

        if ($request->filled('tracking_number')) {
            $updateData['tracking_number'] = $request->tracking_number;
        }

        if ($request->filled('payment_method')) {
            $updateData['payment_method'] = $request->payment_method;
        }

        if ($request->filled('delivery_method')) {
            $updateData['delivery_method'] = $request->delivery_method;
        }

        if ($request->filled('delivery_fee')) {
            $updateData['delivery_fee'] = $request->delivery_fee;
            // Recalculate total amount
            $updateData['total_amount'] = $order->subtotal - $order->discount_amount + $request->delivery_fee + $order->tax_amount;
        }

        if ($request->filled('delivery_date')) {
            $updateData['delivery_date'] = $request->delivery_date;
        }

        if ($request->filled('delivery_time_slot')) {
            $updateData['delivery_time_slot'] = $request->delivery_time_slot;
        }

        // Set timestamps based on status
        if ($request->status === 'shipped' && $oldStatus !== 'shipped') {
            $updateData['shipped_at'] = now();
        }

        if ($request->status === 'delivered' && $oldStatus !== 'delivered') {
            $updateData['delivered_at'] = now();
        }

        if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
            $updateData['cancelled_at'] = now();
        }

        if ($request->status === 'refunded' && $oldStatus !== 'refunded') {
            $updateData['refunded_at'] = now();
        }

        if ($request->status === 'processing' && $oldStatus !== 'processing') {
            $updateData['processed_at'] = now();
        }

        $order->update($updateData);

        // Create status history for status change
        if ($oldStatus !== $request->status) {
            $order->statusHistory()->create([
                'from_status' => $oldStatus,
                'to_status' => $request->status,
                'user_id' => auth()->id(),
                'notes' => $request->notes,
            ]);
        }

        // Create status history for payment status change
        if ($request->filled('payment_status') && $oldPaymentStatus !== $request->payment_status) {
            $order->statusHistory()->create([
                'from_status' => 'payment_' . $oldPaymentStatus,
                'to_status' => 'payment_' . $request->payment_status,
                'user_id' => auth()->id(),
                'notes' => 'To\'lov holati o\'zgartirildi: ' . $request->payment_status,
            ]);
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Buyurtma muvaffaqiyatli yangilandi!');
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $order->status;

        $order->update(['status' => $request->status]);

        // Create status history
        $order->statusHistory()->create([
            'from_status' => $oldStatus,
            'to_status' => $request->status,
            'user_id' => auth()->id(),
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Buyurtma holati muvaffaqiyatli yangilandi!'
        ]);
    }

    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        $query = Order::with(['user', 'items.product']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('guest_email', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('first_name', 'like', '%' . $request->search . '%')
                               ->orWhere('last_name', 'like', '%' . $request->search . '%')
                               ->orWhere('email', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $fileName = 'buyurtmalar_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");

            // Headers
            fputcsv($file, [
                'Buyurtma raqami',
                'Mijoz',
                'Email',
                'Telefon',
                'Status',
                'To\'lov holati',
                'To\'lov usuli',
                'Mahsulotlar soni',
                'Jami summa',
                'Yetkazib berish',
                'Sana',
                'Kuzatuv raqami'
            ]);

            foreach ($orders as $order) {
                $customer = $order->user_id
                    ? $order->user->first_name . ' ' . $order->user->last_name
                    : 'Mehmon';

                $email = $order->user_id ? $order->user->email : $order->guest_email;
                $phone = $order->user_id ? $order->user->phone : $order->guest_phone;

                $statusText = 'Kutilmoqda';
                switch($order->status) {
                    case 'pending': $statusText = 'Kutilmoqda'; break;
                    case 'confirmed': $statusText = 'Tasdiqlangan'; break;
                    case 'processing': $statusText = 'Jarayonda'; break;
                    case 'shipped': $statusText = 'Yuborilgan'; break;
                    case 'delivered': $statusText = 'Yetkazilgan'; break;
                    case 'cancelled': $statusText = 'Bekor qilingan'; break;
                    case 'refunded': $statusText = 'Qaytarilgan'; break;
                    default: $statusText = $order->status; break;
                }

                $paymentStatusText = 'Kutilmoqda';
                switch($order->payment_status) {
                    case 'pending': $paymentStatusText = 'Kutilmoqda'; break;
                    case 'paid': $paymentStatusText = 'To\'langan'; break;
                    case 'failed': $paymentStatusText = 'Muvaffaqiyatsiz'; break;
                    case 'refunded': $paymentStatusText = 'Qaytarilgan'; break;
                    default: $paymentStatusText = $order->payment_status; break;
                }

                fputcsv($file, [
                    $order->order_number,
                    $customer,
                    $email,
                    $phone,
                    $statusText,
                    $paymentStatusText,
                    $order->payment_method,
                    $order->items->count(),
                    number_format($order->total_amount, 2),
                    $order->delivery_method,
                    $order->created_at->format('d.m.Y H:i'),
                    $order->tracking_number
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
