<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppingCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_carts', function (Blueprint $table) {
            $table->id();

            // User relationship
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');

            // Cart metadata
            $table->string('session_id')->nullable(); // For guest users
            $table->decimal('total_amount', 12, 2)->default(0); // Cached total
            $table->integer('items_count')->default(0); // Cached count

            // Additional fields
            $table->json('coupon_data')->nullable(); // Applied coupons
            $table->timestamp('expires_at')->nullable(); // Cart expiration

            $table->timestamps();

            // Indexes
            $table->index(['user_id']);
            $table->index(['session_id']);
            $table->index(['expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shopping_carts');
    }
}
