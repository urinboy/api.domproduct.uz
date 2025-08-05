<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Language;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tillarni olish
        $uzLanguage = Language::where('code', 'uz')->first();

        // Test foydalanuvchilari yaratish
        $users = [
            // 1. ADMIN - Tizim administratori
            [
                'name' => 'System Administrator',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@domproduct.uz',
                'phone' => '+998901234567',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified' => true,
                'phone_verified' => true,
                'is_active' => true,
                'preferred_language_id' => $uzLanguage->id ?? 1,
            ],

            // 2. MANAGER - Menejer/Rahbar
            [
                'name' => 'Product Manager',
                'first_name' => 'Jalol',
                'last_name' => 'Rahimov',
                'email' => 'manager@domproduct.uz',
                'phone' => '+998901234568',
                'password' => Hash::make('manager123'),
                'role' => 'manager',
                'email_verified' => true,
                'phone_verified' => true,
                'is_active' => true,
                'city' => 'Toshkent',
                'district' => 'Yunusobod',
                'preferred_language_id' => $uzLanguage->id ?? 1,
            ],

            // 3. EMPLOYEE - Ishchi/Xodim
            [
                'name' => 'Product Employee',
                'first_name' => 'Sardor',
                'last_name' => 'Karimov',
                'email' => 'employee@domproduct.uz',
                'phone' => '+998901234569',
                'password' => Hash::make('employee123'),
                'role' => 'employee',
                'email_verified' => true,
                'phone_verified' => true,
                'is_active' => true,
                'city' => 'Samarqand',
                'district' => 'Markaz',
                'preferred_language_id' => $uzLanguage->id ?? 1,
            ],

            // 4. CUSTOMER - Oddiy foydalanuvchi (Test)
            [
                'name' => 'Test Customer',
                'first_name' => 'Aziz',
                'last_name' => 'Yusupov',
                'email' => 'customer@domproduct.uz',
                'phone' => '+998901234570',
                'password' => Hash::make('customer123'),
                'role' => 'customer',
                'email_verified' => true,
                'phone_verified' => true,
                'is_active' => true,
                'address' => 'Chilonzor 12-kvartal 25-uy',
                'city' => 'Toshkent',
                'district' => 'Chilonzor',
                'postal_code' => '100115',
                'preferred_language_id' => $uzLanguage->id ?? 1,
            ],

            // 5. CUSTOMER - Oddiy foydalanuvchi 2
            [
                'name' => 'Regular Customer',
                'first_name' => 'Malika',
                'last_name' => 'Abdullayeva',
                'email' => 'malika@example.com',
                'phone' => '+998901234571',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'email_verified' => true,
                'phone_verified' => false,
                'is_active' => true,
                'address' => 'Yashnobod 5-kvartal 10-uy',
                'city' => 'Toshkent',
                'district' => 'Yashnobod',
                'birth_date' => '1995-06-15',
                'gender' => 'female',
                'preferred_language_id' => $uzLanguage->id ?? 1,
            ]
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        echo "Users seeded successfully!\n";
        echo "Created users:\n";
        echo "- Admin: admin@domproduct.uz (password: admin123)\n";
        echo "- Manager: manager@domproduct.uz (password: manager123)\n";
        echo "- Employee: employee@domproduct.uz (password: employee123)\n";
        echo "- Customer: customer@domproduct.uz (password: customer123)\n";
        echo "- Customer 2: malikaabdullayeva@gmail.com (password: password123)\n";
    }
}
