<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Kategoriyalarni olish
        $categories = Category::all();

        if ($categories->isEmpty()) {
            return;
        }

        // Test mahsulotlar yaratish
        $products = [
            // Sabzavotlar
            [
                'name' => json_encode([
                    'uz' => 'Pomidor',
                    'en' => 'Tomato',
                    'ru' => 'Помидор'
                ]),
                'description' => json_encode([
                    'uz' => 'Yangi va mazali pomidor',
                    'en' => 'Fresh and delicious tomato',
                    'ru' => 'Свежий и вкусный помидор'
                ]),
                'category_id' => $categories->first()->id,
                'price' => 8000,
                'sale_price' => 7000,
                'cost_price' => 5000,
                'quantity' => 100,
                'unit' => 'kg',
                'sku' => 'VEG-001',
                'status' => 'active',
                'views' => 156
            ],
            [
                'name' => json_encode([
                    'uz' => 'Bodring',
                    'en' => 'Cucumber',
                    'ru' => 'Огурец'
                ]),
                'description' => json_encode([
                    'uz' => 'Yangi va sochiq bodring',
                    'en' => 'Fresh and crispy cucumber',
                    'ru' => 'Свежий и хрустящий огурец'
                ]),
                'category_id' => $categories->first()->id,
                'price' => 6000,
                'cost_price' => 4000,
                'quantity' => 80,
                'unit' => 'kg',
                'sku' => 'VEG-002',
                'status' => 'active',
                'views' => 234
            ],
            // Mevalar
            [
                'name' => json_encode([
                    'uz' => 'Olma',
                    'en' => 'Apple',
                    'ru' => 'Яблоко'
                ]),
                'description' => json_encode([
                    'uz' => 'Shirin va mazali olma',
                    'en' => 'Sweet and delicious apple',
                    'ru' => 'Сладкое и вкусное яблоко'
                ]),
                'category_id' => $categories->count() > 1 ? $categories[1]->id : $categories->first()->id,
                'price' => 12000,
                'sale_price' => 10000,
                'cost_price' => 8000,
                'quantity' => 50,
                'unit' => 'kg',
                'sku' => 'FRT-001',
                'status' => 'active',
                'views' => 89
            ],
            [
                'name' => json_encode([
                    'uz' => 'Banan',
                    'en' => 'Banana',
                    'ru' => 'Банан'
                ]),
                'description' => json_encode([
                    'uz' => 'Shirin va foydali banan',
                    'en' => 'Sweet and healthy banana',
                    'ru' => 'Сладкий и полезный банан'
                ]),
                'category_id' => $categories->count() > 1 ? $categories[1]->id : $categories->first()->id,
                'price' => 15000,
                'cost_price' => 12000,
                'quantity' => 30,
                'unit' => 'kg',
                'sku' => 'FRT-002',
                'status' => 'active',
                'views' => 345
            ],
            // Sut mahsulotlari
            [
                'name' => json_encode([
                    'uz' => 'Sut',
                    'en' => 'Milk',
                    'ru' => 'Молоко'
                ]),
                'description' => json_encode([
                    'uz' => 'Yangi sigir suti',
                    'en' => 'Fresh cow milk',
                    'ru' => 'Свежее коровье молоко'
                ]),
                'category_id' => $categories->count() > 2 ? $categories[2]->id : $categories->first()->id,
                'price' => 9000,
                'cost_price' => 7000,
                'quantity' => 25,
                'unit' => 'l',
                'sku' => 'MLK-001',
                'status' => 'active',
                'views' => 123
            ],
            [
                'name' => json_encode([
                    'uz' => 'Tvorog',
                    'en' => 'Cottage Cheese',
                    'ru' => 'Творог'
                ]),
                'description' => json_encode([
                    'uz' => 'Yangi va mazali tvorog',
                    'en' => 'Fresh and delicious cottage cheese',
                    'ru' => 'Свежий и вкусный творог'
                ]),
                'category_id' => $categories->count() > 2 ? $categories[2]->id : $categories->first()->id,
                'price' => 18000,
                'sale_price' => 16000,
                'cost_price' => 14000,
                'quantity' => 15,
                'unit' => 'kg',
                'sku' => 'MLK-002',
                'status' => 'active',
                'views' => 67
            ]
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}
