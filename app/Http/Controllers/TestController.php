<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\ShoppingCart;

class TestController extends Controller
{
    public function testOrder(Request $request)
    {
        try {
            // Create user
            $user = User::where('email', 'testorder@test.com')->first();
            if (!$user) {
                $user = User::create([
                    'name' => 'Test Order User',
                    'email' => 'testorder@test.com',
                    'password' => bcrypt('password123'),
                    'role' => 'customer',
                    'is_active' => true,
                    'email_verified' => true,
                    'phone_verified' => true,
                ]);
            }

            // Get or create cart
            $cart = ShoppingCart::createOrGetCart($user->id, 'test-session-123');

            // Add products to cart
            $product1 = Product::first();
            $product2 = Product::skip(1)->first();

            if ($product1) {
                $cart->addProduct($product1, 2);
            }
            if ($product2) {
                $cart->addProduct($product2, 1);
            }

            $cart = $cart->fresh();

            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $user->id,
                    'cart_id' => $cart->id,
                    'cart_total' => $cart->total_amount,
                    'items_count' => $cart->items_count,
                    'items' => $cart->items->map(function ($item) {
                        return [
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity,
                            'total_price' => $item->total_price
                        ];
                    })
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
