<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade'); // Ota kategoriya
            $table->integer('sort_order')->default(0); // Tartiblash uchun raqam
            $table->string('icon', 100)->nullable(); // Emoji yoki icon nomi
            $table->string('image', 255)->nullable(); // Rasm fayl yo'li
            $table->boolean('is_active')->default(true); // Faol/nofaol holati
            $table->timestamps();

            // Indekslar
            $table->index(['parent_id', 'is_active']); // Ota kategoriya va faol holatiga qarab qidirish
            $table->index('sort_order'); // Tartiblash uchun
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
