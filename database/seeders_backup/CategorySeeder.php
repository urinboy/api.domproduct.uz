<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Language;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = Language::all();

        // Kategoriyalar
        $categories = [
            [
                'icon' => '🥬',
                'sort_order' => 1,
                'translations' => [
                    'uz' => ['name' => 'Sabzavotlar', 'description' => 'Turli xil yangi sabzavotlar'],
                    'ru' => ['name' => 'Овощи', 'description' => 'Различные свежие овощи'],
                    'en' => ['name' => 'Vegetables', 'description' => 'Various fresh vegetables']
                ]
            ],
            [
                'icon' => '🍎',
                'sort_order' => 2,
                'translations' => [
                    'uz' => ['name' => 'Mevalar', 'description' => 'Mazali va foydali mevalar'],
                    'ru' => ['name' => 'Фрукты', 'description' => 'Вкусные и полезные фрукты'],
                    'en' => ['name' => 'Fruits', 'description' => 'Delicious and healthy fruits']
                ]
            ],
            [
                'icon' => '🥛',
                'sort_order' => 3,
                'translations' => [
                    'uz' => ['name' => 'Sut mahsulotlari', 'description' => 'Yangi sut mahsulotlari'],
                    'ru' => ['name' => 'Молочные продукты', 'description' => 'Свежие молочные продукты'],
                    'en' => ['name' => 'Dairy Products', 'description' => 'Fresh dairy products']
                ]
            ]
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create([
                'icon' => $categoryData['icon'],
                'sort_order' => $categoryData['sort_order'],
                'is_active' => true,
                'status' => 'active'
            ]);

            foreach ($languages as $language) {
                if (isset($categoryData['translations'][$language->code])) {
                    CategoryTranslation::create([
                        'category_id' => $category->id,
                        'language_id' => $language->id,
                        'name' => $categoryData['translations'][$language->code]['name'],
                        'description' => $categoryData['translations'][$language->code]['description'],
                        'slug' => \Str::slug($categoryData['translations'][$language->code]['name'])
                    ]);
                }
            }
        }
    }
}
