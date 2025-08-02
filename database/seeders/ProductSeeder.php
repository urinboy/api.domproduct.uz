<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductTranslation;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Pomidor
        $tomato = Product::create([
            'sku' => 'TOM-001',
            'category_id' => 1, // Sabzavotlar
            'price' => 15000.00,
            'sale_price' => 12000.00,
            'stock_quantity' => 100,
            'min_stock_level' => 10,
            'weight' => 0.5,
            'is_active' => true,
            'is_featured' => true,
            'published_at' => now(),
            'sort_order' => 1,
        ]);

        $tomato->translations()->createMany([
            [
                'language' => 'uz',
                'name' => 'Yangi pomidor',
                'slug' => 'yangi-pomidor',
                'short_description' => 'Eng yangi va mazali pomidor',
                'description' => 'Bu juda sifatli va mazali pomidor. Mahalliy fermerlar tomonidan etishtirilgan. Vitaminlarga boy va tabiiy.',
                'specifications' => ['origin' => 'Mahalliy', 'organic' => true],
                'features' => ['Fresh', 'Organic', 'Local'],
                'meta_title' => 'Yangi pomidor - sifatli va mazali',
                'meta_description' => 'Eng yangi va mazali pomidor, mahalliy fermerlar tomonidan etishtirilgan'
            ],
            [
                'language' => 'en',
                'name' => 'Fresh Tomato',
                'slug' => 'fresh-tomato',
                'short_description' => 'Fresh and delicious tomato',
                'description' => 'This is a high quality and delicious tomato. Grown by local farmers. Rich in vitamins and natural.',
                'specifications' => ['origin' => 'Local', 'organic' => true],
                'features' => ['Fresh', 'Organic', 'Local'],
                'meta_title' => 'Fresh Tomato - Quality and Delicious',
                'meta_description' => 'Fresh and delicious tomato, grown by local farmers'
            ],
            [
                'language' => 'ru',
                'name' => 'Свежие помидоры',
                'slug' => 'svezhie-pomidory',
                'short_description' => 'Свежие и вкусные помидоры',
                'description' => 'Это высококачественные и вкусные помидоры. Выращены местными фермерами. Богаты витаминами и натуральные.',
                'specifications' => ['origin' => 'Местные', 'organic' => true],
                'features' => ['Свежие', 'Органические', 'Местные'],
                'meta_title' => 'Свежие помидоры - качественные и вкусные',
                'meta_description' => 'Свежие и вкусные помидоры, выращенные местными фермерами'
            ]
        ]);

        // Bodring
        $cucumber = Product::create([
            'sku' => 'CUC-001',
            'category_id' => 1, // Sabzavotlar
            'price' => 8000.00,
            'sale_price' => null,
            'stock_quantity' => 80,
            'min_stock_level' => 15,
            'weight' => 0.3,
            'is_active' => true,
            'is_featured' => false,
            'published_at' => now(),
            'sort_order' => 2,
        ]);

        $cucumber->translations()->createMany([
            [
                'language' => 'uz',
                'name' => 'Yangi bodring',
                'slug' => 'yangi-bodring',
                'short_description' => 'Toza va yangi bodring',
                'description' => 'Mahalliy etishtirilgan toza bodring. Salat va turli taomlar uchun juda mos.',
                'specifications' => ['origin' => 'Mahalliy', 'organic' => true],
                'features' => ['Fresh', 'Crispy', 'Local'],
                'meta_title' => 'Yangi bodring - toza va mazali',
                'meta_description' => 'Mahalliy etishtirilgan toza bodring, salat uchun juda mos'
            ],
            [
                'language' => 'en',
                'name' => 'Fresh Cucumber',
                'slug' => 'fresh-cucumber',
                'short_description' => 'Clean and fresh cucumber',
                'description' => 'Locally grown clean cucumber. Perfect for salads and various dishes.',
                'specifications' => ['origin' => 'Local', 'organic' => true],
                'features' => ['Fresh', 'Crispy', 'Local'],
                'meta_title' => 'Fresh Cucumber - clean and tasty',
                'meta_description' => 'Locally grown clean cucumber, perfect for salads'
            ]
        ]);

        echo "Products created successfully!\n";
    }
}
