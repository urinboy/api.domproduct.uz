<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderStatusHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Who made the change

            // Status change details
            $table->string('from_status')->nullable(); // Previous status
            $table->string('to_status'); // New status

            // Additional information
            $table->text('notes')->nullable(); // Reason for change
            $table->json('metadata')->nullable(); // Additional data (tracking numbers, etc.)

            // Notification info
            $table->boolean('customer_notified')->default(false); // Was customer notified?
            $table->string('notification_method')->nullable(); // email, sms, etc.

            $table->timestamps();

            // Indexes
            $table->index(['order_id']);
            $table->index(['to_status']);
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
        Schema::dropIfExists('order_status_histories');
    }
}
