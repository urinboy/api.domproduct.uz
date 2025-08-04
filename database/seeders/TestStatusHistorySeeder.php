<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class TestStatusHistorySeeder extends Seeder
{
    public function run()
    {
        $order = Order::first();

        if ($order) {
            // Test payment status update
            $order->updatePaymentStatus('paid');

            // Test status change
            $order->updateStatus('processing', 'Buyurtma ishlov berilmoqda');

            echo "Test status history created for order #{$order->order_number}\n";
        } else {
            echo "No orders found to create test history\n";
        }
    }
}
