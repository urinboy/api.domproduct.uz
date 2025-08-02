<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Mavjud to'lov usullarini olish
     */
    public function getPaymentMethods()
    {
        try {
            $methods = [
                [
                    'id' => 'cash',
                    'name' => 'Naqd to\'lov',
                    'description' => 'Mahsulot yetkazib berilganda naqd to\'lov',
                    'icon' => 'cash',
                    'is_active' => true,
                    'min_amount' => 0,
                    'max_amount' => 10000000, // 10 million UZS
                ],
                [
                    'id' => 'card',
                    'name' => 'Plastik karta',
                    'description' => 'Visa, MasterCard, UzCard orqali to\'lov',
                    'icon' => 'credit-card',
                    'is_active' => true,
                    'min_amount' => 10000,
                    'max_amount' => 50000000, // 50 million UZS
                ],
                [
                    'id' => 'bank_transfer',
                    'name' => 'Bank o\'tkazmasi',
                    'description' => 'Bank orqali pul o\'tkazish',
                    'icon' => 'bank',
                    'is_active' => true,
                    'min_amount' => 50000,
                    'max_amount' => 100000000, // 100 million UZS
                ]
            ];

            return response()->json([
                'success' => true,
                'message' => 'To\'lov usullari muvaffaqiyatli olindi',
                'data' => [
                    'payment_methods' => $methods
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'To\'lov usullarini olishda xatolik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * To'lovni boshlash
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|in:cash,card,bank_transfer',
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Buyurtmani tekshirish
            $order = Order::where('id', $request->order_id)
                          ->where('user_id', auth()->id())
                          ->where('status', 'pending')
                          ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Buyurtma topilmadi yoki allaqachon to\'langan'
                ], 404);
            }

            // To'lov summasini tekshirish
            if ($request->amount != $order->total_amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'To\'lov summasi buyurtma summasiga mos kelmaydi'
                ], 400);
            }

            // To'lovni yaratish
            $payment = Payment::create([
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'amount' => $request->amount,
                'currency' => 'UZS',
                'status' => $this->getPaymentStatus($request->payment_method),
                'transaction_id' => $this->generateTransactionId(),
                'payment_data' => json_encode([
                    'method' => $request->payment_method,
                    'processed_at' => now(),
                    'ip_address' => $request->ip(),
                ])
            ]);

            // Buyurtma statusini yangilash
            if ($request->payment_method === 'cash') {
                // Naqd to'lov - buyurtma tayyorlanmoqda
                $order->update(['status' => 'confirmed']);
            } else {
                // Karta/bank - to'lov kutilmoqda
                $order->update(['status' => 'awaiting_payment']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'To\'lov muvaffaqiyatli boshlandi',
                'data' => [
                    'payment' => [
                        'id' => $payment->id,
                        'transaction_id' => $payment->transaction_id,
                        'amount' => $payment->amount,
                        'currency' => $payment->currency,
                        'method' => $payment->payment_method,
                        'status' => $payment->status,
                        'created_at' => $payment->created_at,
                    ],
                    'order' => [
                        'id' => $order->id,
                        'status' => $order->status,
                        'total_amount' => $order->total_amount,
                    ],
                    'next_steps' => $this->getNextSteps($request->payment_method)
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'To\'lovni amalga oshirishda xatolik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * To'lov tarixini olish
     */
    public function getPaymentHistory(Request $request)
    {
        try {
            $payments = Payment::with('order')
                              ->where('user_id', auth()->id())
                              ->orderBy('created_at', 'desc')
                              ->paginate(20);

            $paymentsData = $payments->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'transaction_id' => $payment->transaction_id,
                    'order_id' => $payment->order_id,
                    'amount' => $payment->amount,
                    'currency' => $payment->currency,
                    'method' => $payment->payment_method,
                    'status' => $payment->status,
                    'created_at' => $payment->created_at,
                    'order' => [
                        'order_number' => $payment->order->order_number,
                        'status' => $payment->order->status,
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'To\'lov tarixi muvaffaqiyatli olindi',
                'data' => [
                    'payments' => $paymentsData,
                    'pagination' => [
                        'current_page' => $payments->currentPage(),
                        'total_pages' => $payments->lastPage(),
                        'total_items' => $payments->total(),
                        'per_page' => $payments->perPage(),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'To\'lov tarixini olishda xatolik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * To'lov holatini tekshirish
     */
    public function checkPaymentStatus($paymentId)
    {
        try {
            $payment = Payment::with('order')
                             ->where('id', $paymentId)
                             ->where('user_id', auth()->id())
                             ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'To\'lov topilmadi'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'To\'lov holati muvaffaqiyatli olindi',
                'data' => [
                    'payment' => [
                        'id' => $payment->id,
                        'transaction_id' => $payment->transaction_id,
                        'amount' => $payment->amount,
                        'currency' => $payment->currency,
                        'method' => $payment->payment_method,
                        'status' => $payment->status,
                        'created_at' => $payment->created_at,
                        'updated_at' => $payment->updated_at,
                    ],
                    'order' => [
                        'id' => $payment->order->id,
                        'order_number' => $payment->order->order_number,
                        'status' => $payment->order->status,
                        'total_amount' => $payment->order->total_amount,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'To\'lov holatini tekshirishda xatolik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * To'lovni tasdiqlash (admin uchun)
     */
    public function confirmPayment($paymentId)
    {
        try {
            $payment = Payment::with('order')->find($paymentId);

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'To\'lov topilmadi'
                ], 404);
            }

            if ($payment->status === 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'To\'lov allaqachon tasdiqlangan'
                ], 400);
            }

            DB::beginTransaction();

            // To'lovni tasdiqlash
            $payment->update([
                'status' => 'completed',
                'confirmed_at' => now(),
            ]);

            // Buyurtma statusini yangilash
            $payment->order->update(['status' => 'confirmed']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'To\'lov muvaffaqiyatli tasdiqlandi',
                'data' => [
                    'payment' => [
                        'id' => $payment->id,
                        'status' => $payment->status,
                        'confirmed_at' => $payment->confirmed_at,
                    ],
                    'order' => [
                        'id' => $payment->order->id,
                        'status' => $payment->order->status,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'To\'lovni tasdiqlashda xatolik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * To'lov usuli bo'yicha status olish
     */
    private function getPaymentStatus($method)
    {
        switch ($method) {
            case 'cash':
                return 'pending'; // Naqd to'lov - yetkazib berganda
            case 'card':
            case 'bank_transfer':
                return 'processing'; // Karta/bank - qayta ishlanmoqda
            default:
                return 'pending';
        }
    }

    /**
     * Tranzaksiya ID yaratish
     */
    private function generateTransactionId()
    {
        return 'TXN-' . date('Ymd') . '-' . Str::upper(Str::random(8));
    }

    /**
     * Keyingi qadamlar haqida ma'lumot
     */
    private function getNextSteps($method)
    {
        switch ($method) {
            case 'cash':
                return [
                    'message' => 'Buyurtmangiz tasdiqlandi. Kuryer yetkazib berganda naqd to\'lang.',
                    'estimated_delivery' => '1-3 ish kuni ichida'
                ];
            case 'card':
                return [
                    'message' => 'Karta ma\'lumotlarini kiriting va to\'lovni tasdiqlang.',
                    'action_required' => 'card_details'
                ];
            case 'bank_transfer':
                return [
                    'message' => 'Bank rekvizitlari yuborildi. O\'tkazmani amalga oshiring.',
                    'action_required' => 'bank_transfer'
                ];
            default:
                return ['message' => 'To\'lov jarayoni boshlanadi.'];
        }
    }
}
