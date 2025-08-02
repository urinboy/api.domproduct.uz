<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageFieldsToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->text('image_original')->nullable()->after('image');
            $table->text('image_thumbnail')->nullable()->after('image_original');
            $table->text('image_small')->nullable()->after('image_thumbnail');
            $table->text('image_medium')->nullable()->after('image_small');
            $table->text('image_large')->nullable()->after('image_medium');
            $table->string('image_path')->nullable()->after('image_large');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'image_original',
                'image_thumbnail',
                'image_small',
                'image_medium',
                'image_large',
                'image_path'
            ]);
        });
    }
}
