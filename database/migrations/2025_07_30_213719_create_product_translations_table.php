<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_translations', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('language', 5); // 'uz', 'en', 'ru', etc.

            // Translatable content
            $table->string('name'); // Product name
            $table->string('slug')->nullable(); // URL slug
            $table->text('short_description')->nullable(); // Brief description
            $table->longText('description')->nullable(); // Full description
            $table->longText('specifications')->nullable(); // Product specs (JSON or text)

            // SEO fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            // Additional content
            $table->text('features')->nullable(); // Key features (JSON or text)
            $table->text('care_instructions')->nullable(); // Care/usage instructions
            $table->text('warranty_info')->nullable(); // Warranty information

            $table->timestamps();

            // Composite unique key
            $table->unique(['product_id', 'language']);

            // Indexes
            $table->index(['language', 'name']);
            $table->index(['slug', 'language']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_translations');
    }
}
