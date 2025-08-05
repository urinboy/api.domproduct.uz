<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Basic order info
            $table->string('order_number')->unique(); // ORD-20250731-0001
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Order status
            $table->enum('status', [
                'pending',      // Kutilmoqda
                'confirmed',    // Tasdiqlangan
                'processing',   // Jarayonda
                'shipped',      // Yuborilgan
                'delivered',    // Yetkazilgan
                'cancelled',    // Bekor qilingan
                'refunded'      // Qaytarilgan
            ])->default('pending');

            // Financial info
            $table->decimal('subtotal', 12, 2); // Mahsulotlar summasi
            $table->decimal('tax_amount', 12, 2)->default(0); // Soliq
            $table->decimal('shipping_cost', 12, 2)->default(0); // Yetkazib berish
            $table->decimal('discount_amount', 12, 2)->default(0); // Chegirma
            $table->decimal('total_amount', 12, 2); // Jami summa

            // Payment info
            $table->string('payment_method')->nullable(); // click, payme, cash
            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed',
                'refunded'
            ])->default('pending');
            $table->string('payment_transaction_id')->nullable();

            // Delivery info
            $table->json('delivery_address'); // Full address JSON
            $table->string('delivery_phone'); // Delivery phone
            $table->text('delivery_notes')->nullable(); // Delivery instructions
            $table->enum('delivery_method', [
                'standard',     // Oddiy yetkazib berish
                'express',      // Tezkor yetkazib berish
                'pickup'        // O'zbekistan orqali olish
            ])->default('standard');

            // Timing
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            // Additional info
            $table->text('customer_notes')->nullable(); // Customer notes
            $table->text('admin_notes')->nullable(); // Internal notes
            $table->string('coupon_code')->nullable(); // Applied coupon

            $table->timestamps();

            // Indexes
            $table->index(['user_id']);
            $table->index(['status']);
            $table->index(['payment_status']);
            $table->index(['order_number']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
