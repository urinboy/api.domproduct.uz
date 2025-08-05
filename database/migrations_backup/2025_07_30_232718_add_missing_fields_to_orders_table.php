<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('currency', 3)->default('UZS')->after('total_amount');
            $table->json('billing_address')->nullable()->after('currency');
            $table->date('delivery_date')->nullable()->after('delivery_notes');
            $table->string('delivery_time_slot')->nullable()->after('delivery_date');
            $table->text('special_instructions')->nullable()->after('delivery_time_slot');
            $table->json('payment_data')->nullable()->after('payment_transaction_id');
            $table->string('tracking_number')->nullable()->after('payment_data');
            $table->text('order_notes')->nullable()->after('admin_notes');
            $table->timestamp('processed_at')->nullable()->after('cancelled_at');
            $table->timestamp('refunded_at')->nullable()->after('processed_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'currency',
                'billing_address',
                'delivery_date',
                'delivery_time_slot',
                'special_instructions',
                'payment_data',
                'tracking_number',
                'order_notes',
                'processed_at',
                'refunded_at'
            ]);
        });
    }
}
