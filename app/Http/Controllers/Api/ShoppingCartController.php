<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShoppingCart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ShoppingCartController extends Controller
{
    /**
     * Get user's shopping cart
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $cart = $this->getUserCart($request);

            if (!$cart) {
                return response()->json([
                    'success' => true,
                    'message' => 'Shopping cart is empty',
                    'data' => [
                        'cart' => null,
                        'items' => [],
                        'summary' => [
                            'items_count' => 0,
                            'total_amount' => 0,
                            'currency' => 'UZS'
                        ]
                    ]
                ]);
            }

            $cart->load(['items.product.translations', 'items.product.images']);

            return response()->json([
                'success' => true,
                'message' => 'Shopping cart retrieved successfully',
                'data' => [
                    'cart' => [
                        'id' => $cart->id,
                        'items_count' => $cart->items_count,
                        'total_amount' => $cart->total_amount,
                        'final_total' => $cart->getFinalTotal(),
                        'coupon_data' => $cart->coupon_data,
                        'expires_at' => $cart->expires_at,
                        'created_at' => $cart->created_at,
                        'updated_at' => $cart->updated_at,
                    ],
                    'items' => $cart->activeItems->map(function ($item) use ($request) {
                        return [
                            'id' => $item->id,
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity,
                            'unit_price' => $item->unit_price,
                            'total_price' => $item->total_price,
                            'product_name' => $item->product_name,
                            'product_sku' => $item->product_sku,
                            'product_image' => $item->getProductThumbnail(),
                            'product_options' => $item->product_options,
                            'is_available' => $item->isProductAvailable(),
                            'max_quantity' => $item->getMaxAvailableQuantity(),
                            'has_price_changed' => $item->hasPriceChanged(),
                            'current_price' => $item->getCurrentProductPrice(),
                            'product' => $item->product ? [
                                'id' => $item->product->id,
                                'name' => $item->product->getTranslation($request->header('Accept-Language', 'uz'))->name ?? $item->product_name,
                                'is_active' => $item->product->is_active,
                                'stock_status' => $item->product->stock_status,
                                'stock_quantity' => $item->product->stock_quantity,
                            ] : null
                        ];
                    }),
                    'summary' => [
                        'items_count' => $cart->items_count,
                        'subtotal' => $cart->total_amount,
                        'discount' => $cart->coupon_data['discount_amount'] ?? 0,
                        'final_total' => $cart->getFinalTotal(),
                        'currency' => 'UZS'
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving shopping cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add product to cart
     */
    public function addItem(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1|max:999',
                'options' => 'sometimes|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $product = Product::with(['translations', 'images'])->findOrFail($request->product_id);

            // Check if product is active and available
            if (!$product->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product is not available'
                ], 422);
            }

            if ($product->stock_status === 'out_of_stock') {
                return response()->json([
                    'success' => false,
                    'message' => 'Product is out of stock'
                ], 422);
            }

            // Check stock quantity if tracking inventory
            if ($product->track_inventory && $product->stock_quantity < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$product->stock_quantity} items available in stock"
                ], 422);
            }

            DB::beginTransaction();

            try {
                $cart = $this->getOrCreateCart($request);
                $cartItem = $cart->addProduct($product, $request->quantity, $request->options ?? []);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Product added to cart successfully',
                    'data' => [
                        'cart_item' => [
                            'id' => $cartItem->id,
                            'product_id' => $cartItem->product_id,
                            'quantity' => $cartItem->quantity,
                            'unit_price' => $cartItem->unit_price,
                            'total_price' => $cartItem->total_price,
                            'product_name' => $cartItem->product_name,
                        ],
                        'cart_summary' => [
                            'items_count' => $cart->fresh()->items_count,
                            'total_amount' => $cart->fresh()->total_amount,
                        ]
                    ]
                ], 201);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding product to cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function updateItem(Request $request, $itemId): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'quantity' => 'required|integer|min:0|max:999'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $cart = $this->getUserCart($request);

            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shopping cart not found'
                ], 404);
            }

            $cartItem = $cart->items()->findOrFail($itemId);

            // Check stock availability for the new quantity
            if ($cartItem->product && $cartItem->product->track_inventory) {
                if ($request->quantity > $cartItem->product->stock_quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => "Only {$cartItem->product->stock_quantity} items available in stock"
                    ], 422);
                }
            }

            DB::beginTransaction();

            try {
                if ($request->quantity == 0) {
                    $cart->removeItem($cartItem);
                    $message = 'Item removed from cart';
                } else {
                    $cart->updateItemQuantity($cartItem, $request->quantity);
                    $message = 'Cart item updated successfully';
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => [
                        'cart_summary' => [
                            'items_count' => $cart->fresh()->items_count,
                            'total_amount' => $cart->fresh()->total_amount,
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
                'message' => 'Error updating cart item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function removeItem(Request $request, $itemId): JsonResponse
    {
        try {
            $cart = $this->getUserCart($request);

            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shopping cart not found'
                ], 404);
            }

            $cartItem = $cart->items()->findOrFail($itemId);

            DB::beginTransaction();

            try {
                $cart->removeItem($cartItem);
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart successfully',
                    'data' => [
                        'cart_summary' => [
                            'items_count' => $cart->fresh()->items_count,
                            'total_amount' => $cart->fresh()->total_amount,
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
                'message' => 'Error removing cart item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear entire cart
     */
    public function clear(Request $request): JsonResponse
    {
        try {
            $cart = $this->getUserCart($request);

            if (!$cart) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart is already empty'
                ]);
            }

            DB::beginTransaction();

            try {
                $cart->clearCart();
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Cart cleared successfully'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Apply coupon to cart
     */
    public function applyCoupon(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'coupon_code' => 'required|string|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $cart = $this->getUserCart($request);

            if (!$cart || $cart->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty'
                ], 422);
            }

            // Simple coupon validation - can be extended with a Coupon model later
            $couponCode = strtoupper($request->coupon_code);
            $discount = 0;
            $discountType = 'fixed';

            // Example coupons - replace with database lookup
            $validCoupons = [
                'SAVE10' => ['type' => 'percentage', 'value' => 10, 'min_amount' => 50000],
                'WELCOME' => ['type' => 'fixed', 'value' => 25000, 'min_amount' => 100000],
                'NEWUSER' => ['type' => 'percentage', 'value' => 15, 'min_amount' => 75000],
            ];

            if (!isset($validCoupons[$couponCode])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid coupon code'
                ], 422);
            }

            $coupon = $validCoupons[$couponCode];

            if ($cart->total_amount < $coupon['min_amount']) {
                return response()->json([
                    'success' => false,
                    'message' => "Minimum order amount for this coupon is " . number_format($coupon['min_amount']) . " UZS"
                ], 422);
            }

            if ($coupon['type'] === 'percentage') {
                $discount = ($cart->total_amount * $coupon['value']) / 100;
            } else {
                $discount = $coupon['value'];
            }

            // Don't allow discount to exceed cart total
            $discount = min($discount, $cart->total_amount);

            DB::beginTransaction();

            try {
                $cart->update([
                    'coupon_data' => [
                        'code' => $couponCode,
                        'type' => $coupon['type'],
                        'value' => $coupon['value'],
                        'discount_amount' => $discount,
                        'applied_at' => now(),
                    ]
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Coupon applied successfully',
                    'data' => [
                        'discount_amount' => $discount,
                        'final_total' => $cart->getFinalTotal(),
                        'cart_summary' => [
                            'subtotal' => $cart->total_amount,
                            'discount' => $discount,
                            'final_total' => $cart->getFinalTotal(),
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
                'message' => 'Error applying coupon',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove coupon from cart
     */
    public function removeCoupon(Request $request): JsonResponse
    {
        try {
            $cart = $this->getUserCart($request);

            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shopping cart not found'
                ], 404);
            }

            DB::beginTransaction();

            try {
                $cart->update(['coupon_data' => null]);
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Coupon removed successfully',
                    'data' => [
                        'cart_summary' => [
                            'subtotal' => $cart->total_amount,
                            'discount' => 0,
                            'final_total' => $cart->total_amount,
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
                'message' => 'Error removing coupon',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cart summary
     */
    public function summary(Request $request): JsonResponse
    {
        try {
            $cart = $this->getUserCart($request);

            if (!$cart) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'items_count' => 0,
                        'subtotal' => 0,
                        'discount' => 0,
                        'final_total' => 0,
                        'currency' => 'UZS'
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'items_count' => $cart->items_count,
                    'subtotal' => $cart->total_amount,
                    'discount' => $cart->coupon_data['discount_amount'] ?? 0,
                    'final_total' => $cart->getFinalTotal(),
                    'currency' => 'UZS',
                    'coupon_code' => $cart->coupon_data['code'] ?? null,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving cart summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's cart or null if not exists
     */
    private function getUserCart(Request $request)
    {
        $user = $request->user();
        $sessionId = $request->header('X-Session-ID');

        // If no session ID provided, generate one
        if (!$sessionId) {
            if (!session()->isStarted()) {
                session()->start();
            }
            $sessionId = session()->getId();
        }

        return ShoppingCart::getCartForUser($user ? $user->id : null, $sessionId);
    }

    /**
     * Get or create cart for user/session
     */
    private function getOrCreateCart(Request $request)
    {
        $user = $request->user();
        $sessionId = $request->header('X-Session-ID');

        // If no session ID provided, generate one
        if (!$sessionId) {
            if (!session()->isStarted()) {
                session()->start();
            }
            $sessionId = session()->getId();
        }

        return ShoppingCart::createOrGetCart($user ? $user->id : null, $sessionId);
    }
}
