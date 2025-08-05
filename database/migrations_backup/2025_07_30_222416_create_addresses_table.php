<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            // User relationship
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Address details
            $table->string('title'); // "Uy", "Ish", "Ota-ona uyi"
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('email')->nullable();

            // Location details
            $table->string('country')->default('Uzbekistan');
            $table->string('region'); // Viloyat
            $table->string('city'); // Shahar/tuman
            $table->string('district')->nullable(); // Mahalla
            $table->string('street_address'); // Ko'cha manzil
            $table->string('apartment')->nullable(); // Kvartira/uy raqami
            $table->string('postal_code')->nullable(); // Pochta indeksi

            // Coordinates (for delivery optimization)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Address settings
            $table->boolean('is_default')->default(false); // Default address
            $table->boolean('is_active')->default(true); // Active address
            $table->enum('type', ['home', 'work', 'other'])->default('home');

            // Additional info
            $table->text('delivery_instructions')->nullable(); // Delivery notes
            $table->text('landmark')->nullable(); // Mo'ljal

            $table->timestamps();

            // Indexes
            $table->index(['user_id']);
            $table->index(['is_default']);
            $table->index(['is_active']);
            $table->index(['city']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
