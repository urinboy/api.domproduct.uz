<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Barcha buyurtmalarni olish (Admin)
     */
    public function index(Request $request)
    {
        try {
            $query = Order::with(['user', 'orderItems.product', 'payments'])
                          ->orderBy('created_at', 'desc');

            // Filtrlash
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            if ($request->has('user_id') && $request->user_id) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->has('payment_status') && $request->payment_status) {
                $query->whereHas('payments', function($q) use ($request) {
                    $q->where('status', $request->payment_status);
                });
            }

            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Qidiruv
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                      ->orWhere('guest_email', 'like', "%{$search}%")
                      ->orWhere('guest_phone', 'like', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }

            $orders = $query->paginate(20);

            $ordersData = $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'status_name' => $this->getStatusName($order->status),
                    'total_amount' => $order->total_amount,
                    'currency' => $order->currency,
                    'delivery_method' => $order->delivery_method,
                    'delivery_fee' => $order->delivery_fee,
                    'payment_method' => $order->payment_method,
                    'created_at' => $order->created_at,
                    'user' => $order->user ? [
                        'id' => $order->user->id,
                        'name' => $order->user->name,
                        'email' => $order->user->email,
                    ] : null,
                    'guest_info' => $order->user ? null : [
                        'name' => $order->guest_name,
                        'email' => $order->guest_email,
                        'phone' => $order->guest_phone,
                    ],
                    'items_count' => $order->orderItems->count(),
                    'payment_status' => $order->payments->first() ? $order->payments->first()->status : 'no_payment',
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Buyurtmalar ro\'yxati muvaffaqiyatli olindi',
                'data' => [
                    'orders' => $ordersData,
                    'pagination' => [
                        'current_page' => $orders->currentPage(),
                        'total_pages' => $orders->lastPage(),
                        'total_items' => $orders->total(),
                        'per_page' => $orders->perPage(),
                    ],
                    'filters' => [
                        'status' => $request->status,
                        'payment_status' => $request->payment_status,
                        'date_from' => $request->date_from,
                        'date_to' => $request->date_to,
                        'search' => $request->search,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Buyurtmalarni olishda xatolik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buyurtma tafsilotlarini olish (Admin)
     */
    public function show($id)
    {
        try {
            $order = Order::with([
                'user',
                'orderItems.product.translations',
                'payments',
                'statusHistories' => function($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ])->find($id);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Buyurtma topilmadi'
                ], 404);
            }

            $orderData = [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'status_name' => $this->getStatusName($order->status),
                'total_amount' => $order->total_amount,
                'subtotal' => $order->subtotal,
                'delivery_fee' => $order->delivery_fee,
                'discount_amount' => $order->discount_amount,
                'currency' => $order->currency,
                'delivery_method' => $order->delivery_method,
                'payment_method' => $order->payment_method,
                'notes' => $order->notes,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,

                // Mijoz ma'lumotlari
                'customer' => $order->user ? [
                    'id' => $order->user->id,
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                    'phone' => $order->user->phone,
                    'type' => 'registered'
                ] : [
                    'name' => $order->guest_name,
                    'email' => $order->guest_email,
                    'phone' => $order->guest_phone,
                    'type' => 'guest'
                ],

                // Yetkazib berish ma'lumotlari
                'delivery' => [
                    'method' => $order->delivery_method,
                    'fee' => $order->delivery_fee,
                    'address' => $order->delivery_address,
                    'phone' => $order->delivery_phone,
                    'estimated_date' => $this->getEstimatedDeliveryDate($order)
                ],

                // Buyurtma elementlari
                'items' => $order->orderItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product_name,
                        'product_sku' => $item->product_sku,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total_price' => $item->total_price,
                        'product_attributes' => $item->product_attributes,
                        'product' => $item->product ? [
                            'id' => $item->product->id,
                            'current_price' => $item->product->price,
                            'stock_quantity' => $item->product->stock_quantity,
                        ] : null
                    ];
                }),

                // To'lov ma'lumotlari
                'payments' => $order->payments->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'transaction_id' => $payment->transaction_id,
                        'method' => $payment->payment_method,
                        'amount' => $payment->amount,
                        'status' => $payment->status,
                        'status_name' => $this->getPaymentStatusName($payment->status),
                        'created_at' => $payment->created_at,
                        'confirmed_at' => $payment->confirmed_at,
                    ];
                }),

                // Status tarixi
                'status_history' => $order->statusHistories->map(function ($history) {
                    return [
                        'id' => $history->id,
                        'old_status' => $history->old_status,
                        'new_status' => $history->new_status,
                        'old_status_name' => $this->getStatusName($history->old_status),
                        'new_status_name' => $this->getStatusName($history->new_status),
                        'comment' => $history->comment,
                        'changed_by' => $history->changed_by,
                        'created_at' => $history->created_at,
                    ];
                })
            ];

            return response()->json([
                'success' => true,
                'message' => 'Buyurtma tafsilotlari muvaffaqiyatli olindi',
                'data' => [
                    'order' => $orderData
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Buyurtma tafsilotlarini olishda xatolik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buyurtma statusini yangilash (Admin)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'comment' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $order = Order::find($id);
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Buyurtma topilmadi'
                ], 404);
            }

            $oldStatus = $order->status;
            $newStatus = $request->status;

            // Agar status o'zgargan bo'lsa
            if ($oldStatus !== $newStatus) {
                // Status tarixini saqlash
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'comment' => $request->comment,
                    'changed_by' => auth()->id(),
                ]);

                // Buyurtma statusini yangilash
                $order->update([
                    'status' => $newStatus,
                    'updated_at' => now()
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Buyurtma statusi muvaffaqiyatli yangilandi',
                'data' => [
                    'order' => [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'old_status' => $oldStatus,
                        'new_status' => $newStatus,
                        'old_status_name' => $this->getStatusName($oldStatus),
                        'new_status_name' => $this->getStatusName($newStatus),
                        'updated_at' => $order->updated_at,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Buyurtma statusini yangilashda xatolik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buyurtma statistikasi (Admin)
     */
    public function statistics(Request $request)
    {
        try {
            $dateFrom = $request->date_from ?? now()->subDays(30)->format('Y-m-d');
            $dateTo = $request->date_to ?? now()->format('Y-m-d');

            // Umumiy statistika
            $totalOrders = Order::whereDate('created_at', '>=', $dateFrom)
                               ->whereDate('created_at', '<=', $dateTo)
                               ->count();

            $totalRevenue = Order::whereDate('created_at', '>=', $dateFrom)
                                ->whereDate('created_at', '<=', $dateTo)
                                ->whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])
                                ->sum('total_amount');

            // Status bo'yicha statistika
            $statusStats = Order::selectRaw('status, COUNT(*) as count')
                               ->whereDate('created_at', '>=', $dateFrom)
                               ->whereDate('created_at', '<=', $dateTo)
                               ->groupBy('status')
                               ->get()
                               ->mapWithKeys(function ($item) {
                                   return [$item->status => [
                                       'count' => $item->count,
                                       'name' => $this->getStatusName($item->status)
                                   ]];
                               });

            // To'lov usuli bo'yicha statistika
            $paymentMethodStats = Order::selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as total_amount')
                                      ->whereDate('created_at', '>=', $dateFrom)
                                      ->whereDate('created_at', '<=', $dateTo)
                                      ->groupBy('payment_method')
                                      ->get();

            // Kunlik statistika
            $dailyStats = Order::selectRaw('DATE(created_at) as date, COUNT(*) as orders_count, SUM(total_amount) as revenue')
                              ->whereDate('created_at', '>=', $dateFrom)
                              ->whereDate('created_at', '<=', $dateTo)
                              ->groupBy('date')
                              ->orderBy('date', 'desc')
                              ->get();

            return response()->json([
                'success' => true,
                'message' => 'Buyurtma statistikasi muvaffaqiyatli olindi',
                'data' => [
                    'period' => [
                        'date_from' => $dateFrom,
                        'date_to' => $dateTo,
                    ],
                    'summary' => [
                        'total_orders' => $totalOrders,
                        'total_revenue' => $totalRevenue,
                        'average_order_value' => $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0,
                        'currency' => 'UZS'
                    ],
                    'status_breakdown' => $statusStats,
                    'payment_methods' => $paymentMethodStats,
                    'daily_stats' => $dailyStats
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Statistikani olishda xatolik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Status nomini olish
     */
    private function getStatusName($status)
    {
        $statuses = [
            'pending' => 'Kutilmoqda',
            'confirmed' => 'Tasdiqlangan',
            'processing' => 'Tayyorlanmoqda',
            'shipped' => 'Yetkazib berilmoqda',
            'delivered' => 'Yetkazib berilgan',
            'cancelled' => 'Bekor qilingan',
        ];

        return $statuses[$status] ?? $status;
    }

    /**
     * To'lov status nomini olish
     */
    private function getPaymentStatusName($status)
    {
        $statuses = [
            'pending' => 'Kutilmoqda',
            'processing' => 'Qayta ishlanmoqda',
            'completed' => 'Yakunlangan',
            'failed' => 'Muvaffaqiyatsiz',
            'cancelled' => 'Bekor qilingan',
            'refunded' => 'Qaytarilgan',
        ];

        return $statuses[$status] ?? $status;
    }

    /**
     * Taxminiy yetkazib berish sanasini olish
     */
    private function getEstimatedDeliveryDate($order)
    {
        $days = $order->delivery_method === 'express' ? 1 : 3;
        return $order->created_at->addDays($days)->format('Y-m-d');
    }
}
