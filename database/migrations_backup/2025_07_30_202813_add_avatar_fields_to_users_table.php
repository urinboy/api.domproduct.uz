<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAvatarFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('avatar_original')->nullable()->after('avatar');
            $table->text('avatar_thumbnail')->nullable()->after('avatar_original');
            $table->text('avatar_small')->nullable()->after('avatar_thumbnail');
            $table->text('avatar_medium')->nullable()->after('avatar_small');
            $table->text('avatar_large')->nullable()->after('avatar_medium');
            $table->string('avatar_path')->nullable()->after('avatar_large');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar_original',
                'avatar_thumbnail',
                'avatar_small',
                'avatar_medium',
                'avatar_large',
                'avatar_path'
            ]);
        });
    }
}
