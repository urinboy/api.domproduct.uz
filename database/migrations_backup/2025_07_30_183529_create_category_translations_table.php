<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // Qaysi kategoriya
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade'); // Qaysi til
            $table->string('name', 200); // Kategoriya nomi
            $table->text('description')->nullable(); // Tavsif
            $table->string('slug', 250); // URL uchun slug
            $table->string('meta_title', 255)->nullable(); // SEO uchun sarlavha
            $table->string('meta_description', 500)->nullable(); // SEO uchun tavsif
            $table->timestamps();

            // Indekslar va cheklovlar
            $table->unique(['category_id', 'language_id']); // Bir kategoriya uchun bir tilda bitta tarjima
            $table->index('slug'); // URL qidirish uchun
            $table->index(['language_id', 'slug']); // Til va slug bo'yicha qidirish
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_translations');
    }
}
