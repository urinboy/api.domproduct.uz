<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCostPriceAndUnitToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Tan narxi (cost_price) allaqachon mavjud, lekin o'lchov birliklari yo'q

            // O'lchov birliklari
            $table->string('unit_type', 20)->default('piece')->after('cost_price'); // dona, kg, karobka, yashik
            $table->decimal('unit_value', 8, 3)->default(1.000)->after('unit_type'); // 1 dona, 0.5 kg, 12 dona/karobka

            // Qo'shimcha o'lchov ma'lumotlari
            $table->string('weight_unit', 10)->default('kg')->after('weight'); // kg, g
            $table->string('dimension_unit', 10)->default('cm')->after('height'); // cm, mm, m

            // Indexlar
            $table->index(['unit_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'unit_type',
                'unit_value',
                'weight_unit',
                'dimension_unit'
            ]);
        });
    }
}
