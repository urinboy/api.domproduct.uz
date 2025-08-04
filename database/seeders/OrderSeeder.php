<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get some users and products
        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->warn('Avval foydalanuvchilar va mahsulotlar yaratilishi kerak!');
            return;
        }

        $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
        $paymentStatuses = ['pending', 'paid', 'failed'];
        $paymentMethods = ['cash', 'card', 'uzcard', 'humo', 'payme', 'click'];
        $deliveryMethods = ['pickup', 'standard', 'express'];

        // Create 50 test orders
        for ($i = 1; $i <= 50; $i++) {
            $user = $users->random();
            $status = $statuses[array_rand($statuses)];
            $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];

            $order = Order::create([
                'order_number' => 'ORD-' . date('Y') . '-' . str_pad($i + time(), 8, '0', STR_PAD_LEFT),
                'user_id' => rand(0, 1) ? $user->id : null,
                'guest_email' => rand(0, 1) ? null : 'guest' . $i . '@example.com',
                'guest_phone' => rand(0, 1) ? null : '+998901234567',
                'status' => $status,
                'payment_status' => $paymentStatus,
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'delivery_method' => $deliveryMethods[array_rand($deliveryMethods)],
                'currency' => 'UZS',
                'subtotal' => 0, // Will be calculated below
                'discount_amount' => rand(0, 50000),
                'delivery_fee' => rand(10000, 50000),
                'tax_amount' => 0,
                'total_amount' => 0, // Will be calculated below
                'billing_address' => json_encode([
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'address' => 'Test address ' . $i,
                    'city' => 'Toshkent',
                    'postal_code' => '100000',
                    'phone' => '+998901234567'
                ]),
                'delivery_address' => json_encode([
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'address' => 'Yetkazib berish manzili ' . $i,
                    'city' => 'Toshkent',
                    'postal_code' => '100000',
                    'phone' => '+998901234567'
                ]),
                'delivery_date' => now()->addDays(rand(1, 7)),
                'delivery_time_slot' => '9:00-12:00',
                'special_instructions' => rand(0, 1) ? 'Maxsus ko\'rsatma ' . $i : null,
                'tracking_number' => rand(0, 1) ? 'TRACK-' . Str::random(10) : null,
                'order_notes' => rand(0, 1) ? 'Admin eslatmasi ' . $i : null,
                'processed_at' => in_array($status, ['processing', 'shipped', 'delivered']) ? now()->subDays(rand(1, 5)) : null,
                'shipped_at' => in_array($status, ['shipped', 'delivered']) ? now()->subDays(rand(1, 3)) : null,
                'delivered_at' => $status === 'delivered' ? now()->subDays(rand(0, 2)) : null,
                'cancelled_at' => $status === 'cancelled' ? now()->subDays(rand(0, 1)) : null,
                'created_at' => now()->subDays(rand(0, 30)),
            ]);

            // Add order items
            $itemCount = rand(1, 5);
            $subtotal = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $price = $product->price;
                $total = $price * $quantity;
                $subtotal += $total;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $price,
                    'total_price' => $total,
                    'product_name' => $product->getName(),
                    'product_sku' => $product->sku ?? 'SKU-' . $product->id,
                    'product_image' => $product->main_image,
                ]);
            }

            // Update order totals
            $totalAmount = $subtotal - $order->discount_amount + $order->delivery_fee + $order->tax_amount;
            $order->update([
                'subtotal' => $subtotal,
                'total_amount' => $totalAmount,
            ]);

            // Create status history
            $order->statusHistory()->create([
                'to_status' => $status,
                'user_id' => 1, // Assuming admin user with ID 1
                'notes' => 'Boshlang\'ich holat',
                'created_at' => $order->created_at,
            ]);
        }

        $this->command->info('50 ta test buyurtma muvaffaqiyatli yaratildi!');
    }
}
