<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            [
                'name' => 'O\'zbek',
                'code' => 'uz',
                'flag' => '🇺🇿',
                'is_active' => true,
                'is_default' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Русский',
                'code' => 'ru',
                'flag' => '🇷🇺',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 3
            ],
            [
                'name' => 'English',
                'code' => 'en',
                'flag' => '🇺🇸',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 2
            ]
        ];

        foreach ($languages as $language) {
            Language::updateOrCreate(
                ['code' => $language['code']],
                $language
            );
        }
    }
}
