<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateAddressesTypeEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Enum ni o'zgartirish uchun ustunni boshqatdan yaratish kerak
        DB::statement("ALTER TABLE addresses MODIFY type ENUM('home', 'work', 'other', 'billing', 'shipping', 'both') DEFAULT 'home'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE addresses MODIFY type ENUM('home', 'work', 'other') DEFAULT 'home'");
    }
}
