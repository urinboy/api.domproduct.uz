<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ShoppingCart;
use App\Models\Address;
use App\Models\PaymentMethod;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth:sanctum');
        $this->notificationService = $notificationService;
    }

    /**
     * Get user's orders
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $orders = Order::where('user_id', $user->id)
                ->with([
                    'items.product.translations' => function ($query) {
                        $query->where('language', 'uz');
                    },
                    'items.product.primaryImage',
                    'statusHistory' => function ($query) {
                        $query->latest()->limit(5);
                    }
                ])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'Orders retrieved successfully',
                'data' => [
                    'orders' => $orders->items(),
                    'pagination' => [
                        'current_page' => $orders->currentPage(),
                        'last_page' => $orders->lastPage(),
                        'per_page' => $orders->perPage(),
                        'total' => $orders->total(),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get order details
     */
    public function show(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();

            $order = Order::where('id', $id)
                ->where('user_id', $user->id)
                ->with([
                    'items.product.translations',
                    'items.product.images',
                    'statusHistory' => function ($query) {
                        $query->orderBy('created_at', 'desc');
                    }
                ])
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'message' => 'Order retrieved successfully',
                'data' => [
                    'order' => [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'status' => $order->status,
                        'payment_status' => $order->payment_status,
                        'payment_method' => $order->payment_method,
                        'delivery_method' => $order->delivery_method,
                        'currency' => $order->currency,
                        'subtotal' => $order->subtotal,
                        'discount_amount' => $order->discount_amount,
                        'delivery_fee' => $order->delivery_fee,
                        'tax_amount' => $order->tax_amount,
                        'total_amount' => $order->total_amount,
                        'billing_address' => $order->billing_address,
                        'delivery_address' => $order->delivery_address,
                        'delivery_date' => $order->delivery_date,
                        'delivery_time_slot' => $order->delivery_time_slot,
                        'special_instructions' => $order->special_instructions,
                        'tracking_number' => $order->tracking_number,
                        'created_at' => $order->created_at,
                        'updated_at' => $order->updated_at,
                    ],
                    'items' => $order->items->map(function ($item) use ($request) {
                        return [
                            'id' => $item->id,
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity,
                            'unit_price' => $item->unit_price,
                            'total_price' => $item->total_price,
                            'product_name' => $item->product_name,
                            'product_sku' => $item->product_sku,
                            'product_image' => $item->product_image_url,
                            'product_options' => $item->product_options,
                            'product' => $item->product ? [
                                'id' => $item->product->id,
                                'name' => $item->product->getTranslation($request->header('Accept-Language', 'uz'))->name ?? $item->product_name,
                                'is_active' => $item->product->is_active,
                            ] : null
                        ];
                    }),
                    'status_history' => $order->statusHistory->map(function ($history) {
                        return [
                            'id' => $history->id,
                            'status' => $history->status,
                            'status_display' => $history->status_display,
                            'notes' => $history->notes,
                            'created_at' => $history->created_at,
                            'formatted_date' => $history->formatted_date,
                        ];
                    })
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create order from shopping cart
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'delivery_address_id' => 'sometimes|exists:addresses,id',
                'delivery_address' => 'sometimes|array',
                'delivery_method' => 'required|string|in:standard,express,pickup',
                'delivery_date' => 'sometimes|date|after:today',
                'delivery_time_slot' => 'sometimes|string',
                'payment_method' => 'required|exists:payment_methods,code',
                'special_instructions' => 'sometimes|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();

            // Get user's active cart
            $cart = $user ? $user->getActiveCart() : ShoppingCart::getCartForUser(null, $request->header('X-Session-ID'));

            if (!$cart || $cart->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shopping cart is empty'
                ], 422);
            }

            // Get delivery address
            $deliveryAddress = null;
            if ($request->delivery_address_id) {
                $address = Address::where('id', $request->delivery_address_id)
                    ->where('user_id', $user->id)
                    ->first();
                if ($address) {
                    $deliveryAddress = $address->toOrderArray();
                }
            } elseif ($request->delivery_address) {
                $deliveryAddress = $request->delivery_address;
            }

            // Get payment method
            $paymentMethod = PaymentMethod::where('code', $request->payment_method)
                ->where('is_active', true)
                ->firstOrFail();

            DB::beginTransaction();

            try {
                // Calculate delivery fee
                $deliveryFee = $this->calculateDeliveryFee($request->delivery_method, $deliveryAddress);

                // Calculate tax
                $taxAmount = $this->calculateTax($cart->total_amount);

                // Calculate payment fee
                $paymentFee = $paymentMethod->calculateFee($cart->total_amount + $deliveryFee + $taxAmount);

                // Create order
                $order = Order::create([
                    'user_id' => $user ? $user->id : null,
                    'guest_email' => $user ? null : $request->guest_email,
                    'guest_phone' => $user ? null : $request->guest_phone,
                    'status' => Order::STATUS_PENDING,
                    'payment_status' => Order::PAYMENT_STATUS_PENDING,
                    'payment_method' => $request->payment_method,
                    'delivery_method' => $request->delivery_method,
                    'currency' => 'UZS',
                    'subtotal' => $cart->total_amount,
                    'discount_amount' => $cart->coupon_data['discount_amount'] ?? 0,
                    'delivery_fee' => $deliveryFee,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $cart->total_amount + $deliveryFee + $taxAmount + $paymentFee - ($cart->coupon_data['discount_amount'] ?? 0),
                    'billing_address' => $user && $user->getDefaultBillingAddress() ? $user->getDefaultBillingAddress()->toOrderArray() : null,
                    'delivery_address' => $deliveryAddress,
                    'delivery_date' => $request->delivery_date,
                    'delivery_time_slot' => $request->delivery_time_slot,
                    'special_instructions' => $request->special_instructions,
                ]);

                // Create order items from cart items
                foreach ($cart->activeItems as $cartItem) {
                    $order->items()->create([
                        'product_id' => $cartItem->product_id,
                        'quantity' => $cartItem->quantity,
                        'unit_price' => $cartItem->unit_price,
                        'total_price' => $cartItem->total_price,
                        'product_name' => $cartItem->product_name,
                        'product_sku' => $cartItem->product_sku,
                        'product_image' => $cartItem->product_image,
                        'product_options' => $cartItem->product_options,
                        'product_attributes' => $cartItem->product ? [
                            'category' => $cartItem->product->category->name ?? null,
                            'brand' => $cartItem->product->brand ?? null,
                        ] : [],
                    ]);
                }

                // Clear the cart
                $cart->clearCart();

                // Send order confirmation notification
                $this->notificationService->sendOrderConfirmation($order, $user);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Order created successfully',
                    'data' => [
                        'order' => [
                            'id' => $order->id,
                            'order_number' => $order->order_number,
                            'status' => $order->status,
                            'payment_status' => $order->payment_status,
                            'total_amount' => $order->total_amount,
                            'currency' => $order->currency,
                            'payment_method' => $order->payment_method,
                            'delivery_method' => $order->delivery_method,
                            'created_at' => $order->created_at,
                        ],
                        'next_step' => 'payment'
                    ]
                ], 201);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel order
     */
    public function cancel(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();

            $order = Order::where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            if (!$order->canBeCancelled()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order cannot be cancelled in current status'
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'reason' => 'sometimes|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            try {
                $order->cancel($request->reason ?? 'Cancelled by customer');
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Order cancelled successfully',
                    'data' => [
                        'order' => [
                            'id' => $order->id,
                            'order_number' => $order->order_number,
                            'status' => $order->status,
                            'cancelled_at' => $order->cancelled_at,
                        ]
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error cancelling order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get order status history
     */
    public function statusHistory(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();

            $order = Order::where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            $timeline = $order->statusHistory()
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($history) {
                    return $history->timeline_entry;
                });

            return response()->json([
                'success' => true,
                'message' => 'Order status history retrieved successfully',
                'data' => [
                    'order_number' => $order->order_number,
                    'current_status' => $order->status,
                    'timeline' => $timeline
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Calculate delivery fee
     */
    private function calculateDeliveryFee($deliveryMethod, $deliveryAddress = null): float
    {
        if ($deliveryMethod === 'pickup') {
            return 0;
        }

        // Base delivery fee
        $baseFee = 15000; // 15,000 UZS

        // Express delivery costs more
        if ($deliveryMethod === 'express') {
            $baseFee = 25000; // 25,000 UZS
        }

        // Distance-based calculation would go here
        // For now, return base fee
        return $baseFee;
    }

    /**
     * Calculate tax amount
     */
    private function calculateTax($amount): float
    {
        // No tax for now, but can be configured
        return 0;
    }
}
