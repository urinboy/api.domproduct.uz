<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // User va Order bilan bog'lanish
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            // To'lov ma'lumotlari
            $table->string('transaction_id')->unique(); // Tranzaksiya ID
            $table->enum('payment_method', ['cash', 'card', 'bank_transfer']); // To'lov usuli
            $table->decimal('amount', 15, 2); // To'lov summasi
            $table->string('currency', 3)->default('UZS'); // Valyuta

            // To'lov holati
            $table->enum('status', [
                'pending',      // Kutilmoqda
                'processing',   // Qayta ishlanmoqda
                'completed',    // Yakunlangan
                'failed',       // Muvaffaqiyatsiz
                'cancelled',    // Bekor qilingan
                'refunded'      // Qaytarilgan
            ])->default('pending');

            // Qo'shimcha ma'lumotlar
            $table->json('payment_data')->nullable(); // To'lov bo'yicha qo'shimcha ma'lumotlar
            $table->text('notes')->nullable(); // Izohlar

            // Vaqt belgilari
            $table->timestamp('confirmed_at')->nullable(); // Tasdiqlangan vaqt
            $table->timestamp('failed_at')->nullable(); // Muvaffaqiyatsiz bo'lgan vaqt
            $table->timestamps();

            // Indekslar
            $table->index(['user_id', 'status']);
            $table->index(['order_id']);
            $table->index('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
