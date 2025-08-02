<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTableForProductDelivery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Personal ma'lumotlar
            $table->string('first_name', 100)->nullable()->after('name');
            $table->string('last_name', 100)->nullable()->after('first_name');
            $table->string('phone', 20)->nullable()->unique()->after('email');
            $table->date('birth_date')->nullable()->after('phone');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('birth_date');

            // Manzil ma'lumotlari
            $table->string('address', 500)->nullable()->after('gender');
            $table->string('city', 100)->nullable()->after('address');
            $table->string('district', 100)->nullable()->after('city');
            $table->string('postal_code', 20)->nullable()->after('district');
            $table->decimal('latitude', 10, 8)->nullable()->after('postal_code');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');

            // Foydalanuvchi sozlamalari
            $table->enum('role', ['admin', 'manager', 'employee', 'customer'])->default('customer')->after('longitude');
            $table->string('avatar', 255)->nullable()->after('role');
            $table->foreignId('preferred_language_id')->nullable()->constrained('languages')->onDelete('set null')->after('avatar');
            $table->boolean('email_verified')->default(false)->after('preferred_language_id');
            $table->boolean('phone_verified')->default(false)->after('email_verified');
            $table->boolean('is_active')->default(true)->after('phone_verified');

            // Qo'shimcha ma'lumotlar
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->json('preferences')->nullable()->after('last_login_at'); // JSON formatda sozlamalar
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
                'first_name', 'last_name', 'phone', 'birth_date', 'gender',
                'address', 'city', 'district', 'postal_code', 'latitude', 'longitude',
                'role', 'avatar', 'preferred_language_id', 'email_verified', 'phone_verified',
                'is_active', 'last_login_at', 'preferences'
            ]);
        });
    }
}
