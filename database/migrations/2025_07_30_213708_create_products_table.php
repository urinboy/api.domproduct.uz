<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Basic product info
            $table->string('sku')->unique(); // Product SKU
            $table->string('barcode')->nullable(); // Product barcode

            // Category relationship
            $table->foreignId('category_id')->constrained()->onDelete('cascade');

            // Pricing
            $table->decimal('price', 12, 2); // Base price
            $table->decimal('sale_price', 12, 2)->nullable(); // Sale price
            $table->decimal('cost_price', 12, 2)->nullable(); // Cost price

            // Inventory
            $table->integer('stock_quantity')->default(0);
            $table->integer('min_stock_level')->default(0); // Low stock alert
            $table->boolean('track_stock')->default(true);
            $table->enum('stock_status', ['in_stock', 'out_of_stock', 'on_backorder'])->default('in_stock');

            // Physical properties
            $table->decimal('weight', 8, 3)->nullable(); // kg
            $table->decimal('length', 8, 3)->nullable(); // cm
            $table->decimal('width', 8, 3)->nullable(); // cm
            $table->decimal('height', 8, 3)->nullable(); // cm

            // Product status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_digital')->default(false); // Digital product

            // SEO and visibility
            $table->integer('sort_order')->default(0);
            $table->integer('view_count')->default(0);
            $table->decimal('rating', 3, 2)->default(0.00); // Average rating
            $table->integer('review_count')->default(0);

            // Timestamps
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['category_id', 'is_active']);
            $table->index(['sku']);
            $table->index(['price']);
            $table->index(['is_featured', 'is_active']);
            $table->index(['published_at', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
