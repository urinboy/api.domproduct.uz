<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // Item details
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2); // Price at time of order
            $table->decimal('total_price', 12, 2); // quantity * unit_price

            // Product snapshot (for historical accuracy)
            $table->string('product_name'); // Product name at time of order
            $table->string('product_sku'); // Product SKU
            $table->string('product_image')->nullable(); // Primary image URL
            $table->json('product_details')->nullable(); // Full product data snapshot

            // Options and customizations
            $table->json('product_options')->nullable(); // Size, color, etc.
            $table->text('item_notes')->nullable(); // Special instructions

            // Status tracking
            $table->enum('status', [
                'pending',
                'confirmed',
                'out_of_stock',
                'cancelled'
            ])->default('pending');

            $table->timestamps();

            // Indexes
            $table->index(['order_id']);
            $table->index(['product_id']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
