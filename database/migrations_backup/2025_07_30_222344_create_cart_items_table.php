<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('cart_id')->constrained('shopping_carts')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // Item details
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 12, 2); // Price at the time of adding
            $table->decimal('total_price', 12, 2); // quantity * unit_price

            // Product details (cached for historical accuracy)
            $table->string('product_name'); // Product name at time of adding
            $table->string('product_sku')->nullable(); // Product SKU
            $table->string('product_image')->nullable(); // Primary image URL

            // Additional options
            $table->json('product_options')->nullable(); // Size, color, etc.
            $table->text('notes')->nullable(); // Customer notes

            $table->timestamps();

            // Indexes
            $table->index(['cart_id']);
            $table->index(['product_id']);
            $table->unique(['cart_id', 'product_id']); // One product per cart
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
}
