<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin foydalanuvchi yaratish yoki yangilash
        $admin = User::updateOrCreate(
            ['email' => 'admin@domproduct.uz'],
            [
                'name' => 'Administrator',
                'email' => 'admin@domproduct.uz',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified' => true,
                'phone_verified' => true,
                'phone' => '+998901234567',
                'city' => 'Toshkent',
                'email_verified_at' => now(),
            ]
        );

        // Manager foydalanuvchi
        $manager = User::updateOrCreate(
            ['email' => 'manager@test.com'],
            [
                'name' => 'Manager Test',
                'email' => 'manager@test.com',
                'password' => Hash::make('manager123'),
                'role' => 'manager',
                'is_active' => true,
                'email_verified' => true,
                'phone_verified' => true,
                'phone' => '+998902222222',
                'city' => 'Toshkent',
                'email_verified_at' => now(),
            ]
        );

        // Employee foydalanuvchi
        $employee = User::updateOrCreate(
            ['email' => 'employee@test.com'],
            [
                'name' => 'Employee Test',
                'email' => 'employee@test.com',
                'password' => Hash::make('employee123'),
                'role' => 'employee',
                'is_active' => true,
                'email_verified' => true,
                'phone_verified' => true,
                'phone' => '+998903333333',
                'city' => 'Samarqand',
                'email_verified_at' => now(),
            ]
        );

        // Regular user
        $user = User::updateOrCreate(
            ['email' => 'user@test.com'],
            [
                'name' => 'Regular User',
                'email' => 'user@test.com',
                'password' => Hash::make('user123'),
                'role' => 'customer',
                'is_active' => true,
                'email_verified' => true,
                'phone_verified' => true,
                'phone' => '+998904444444',
                'city' => 'Toshkent',
                'email_verified_at' => now(),
            ]
        );

        echo "Admin users created successfully:\n";
        echo "- Admin: admin@domproduct.uz / admin123\n";
        echo "- Manager: manager@test.com / manager123\n";
        echo "- Employee: employee@test.com / employee123\n";
        echo "- Regular User: user@test.com / user123\n";
    }
}
