<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();

            // Method details
            $table->string('name'); // "Click", "Payme", "Uzcard", "Cash"
            $table->string('code')->unique(); // "click", "payme", "uzcard", "cash"
            $table->string('display_name'); // Display name in different languages
            $table->text('description')->nullable(); // Method description

            // Method configuration
            $table->json('config')->nullable(); // Payment gateway config
            $table->decimal('min_amount', 12, 2)->default(0); // Minimum amount
            $table->decimal('max_amount', 12, 2)->nullable(); // Maximum amount
            $table->decimal('fee_percentage', 5, 2)->default(0); // Fee percentage
            $table->decimal('fee_fixed', 12, 2)->default(0); // Fixed fee

            // Method settings
            $table->boolean('is_active')->default(true); // Is method available
            $table->boolean('is_online')->default(true); // Online/offline payment
            $table->integer('sort_order')->default(0); // Display order

            // Integration details
            $table->string('gateway_url')->nullable(); // Payment gateway URL
            $table->string('webhook_url')->nullable(); // Webhook callback URL
            $table->json('supported_currencies')->nullable(); // Supported currencies

            // Visual
            $table->string('icon_url')->nullable(); // Payment method icon
            $table->string('logo_url')->nullable(); // Payment method logo
            $table->string('color_scheme')->nullable(); // Brand colors

            $table->timestamps();

            // Indexes
            $table->index(['is_active']);
            $table->index(['is_online']);
            $table->index(['sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
}
