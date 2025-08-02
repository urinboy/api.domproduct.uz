<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100); // Til nomi (O'zbek, English, Русский)
            $table->string('code', 5)->unique(); // Til kodi (uz, en, ru)
            $table->string('flag', 50)->nullable(); // Bayroq emoji yoki icon
            $table->boolean('is_active')->default(true); // Faol/nofaol
            $table->boolean('is_default')->default(false); // Asosiy til
            $table->integer('sort_order')->default(0); // Tartiblash uchun
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('languages');
    }
}
