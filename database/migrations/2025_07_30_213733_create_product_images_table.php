<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();

            // Foreign key
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // Image information
            $table->string('filename'); // Original filename
            $table->string('path'); // Storage path
            $table->string('alt_text')->nullable(); // Alt text for SEO
            $table->integer('sort_order')->default(0); // Display order

            // Image properties
            $table->string('mime_type')->nullable(); // image/jpeg, image/png, etc.
            $table->integer('file_size')->nullable(); // File size in bytes
            $table->integer('width')->nullable(); // Image width
            $table->integer('height')->nullable(); // Image height

            // Image types
            $table->boolean('is_primary')->default(false); // Main product image
            $table->boolean('is_active')->default(true);

            // Image sizes (generated thumbnails)
            $table->json('sizes')->nullable(); // JSON with different size URLs

            $table->timestamps();

            // Indexes
            $table->index(['product_id', 'is_primary']);
            $table->index(['product_id', 'sort_order']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_images');
    }
}
