<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Language;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create languages
        $uzbek = Language::create([
            'code' => 'uz',
            'name' => 'O\'zbekcha',
            'is_default' => true,
            'is_active' => true
        ]);

        $russian = Language::create([
            'code' => 'ru',
            'name' => 'Русский',
            'is_default' => false,
            'is_active' => true
        ]);

        // Create test user
        User::create([
            'name' => 'Test User',
            'email' => 'test@domproduct.uz',
            'phone' => '+998901234567',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create categories with translations
        $categories = [
            [
                'icon' => '🍎',
                'name_uz' => 'Mevalar',
                'name_ru' => 'Фрукты',
                'description_uz' => 'Yangi va mazali mevalar',
                'description_ru' => 'Свежие и вкусные фрукты',
            ],
            [
                'icon' => '🥬',
                'name_uz' => 'Sabzavotlar',
                'name_ru' => 'Овощи',
                'description_uz' => 'Toza va sog\'lom sabzavotlar',
                'description_ru' => 'Чистые и здоровые овощи',
            ],
            [
                'icon' => '🥩',
                'name_uz' => 'Go\'sht mahsulotlari',
                'name_ru' => 'Мясные продукты',
                'description_uz' => 'Yangi go\'sht va kolbasa mahsulotlari',
                'description_ru' => 'Свежее мясо и колбасные изделия',
            ],
            [
                'icon' => '🥛',
                'name_uz' => 'Sut mahsulotlari',
                'name_ru' => 'Молочные продукты',
                'description_uz' => 'Sut, tvorog, qaymoq va boshqalar',
                'description_ru' => 'Молоко, творог, сметана и другие',
            ],
            [
                'icon' => '🍞',
                'name_uz' => 'Non va g\'alla',
                'name_ru' => 'Хлеб и крупы',
                'description_uz' => 'Yangi non va g\'alla mahsulotlari',
                'description_ru' => 'Свежий хлеб и крупы',
            ],
            [
                'icon' => '🥤',
                'name_uz' => 'Ichimliklar',
                'name_ru' => 'Напитки',
                'description_uz' => 'Turli xil ichimliklar',
                'description_ru' => 'Различные напитки',
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create([
                'icon' => $categoryData['icon'],
                'is_active' => true,
                'sort_order' => Category::count() + 1,
            ]);

            // Add translations
            $category->translations()->create([
                'language_id' => $uzbek->id,
                'name' => $categoryData['name_uz'],
                'description' => $categoryData['description_uz'],
                'slug' => \Str::slug($categoryData['name_uz']),
            ]);

            $category->translations()->create([
                'language_id' => $russian->id,
                'name' => $categoryData['name_ru'],
                'description' => $categoryData['description_ru'],
                'slug' => \Str::slug($categoryData['name_ru']),
            ]);
        }

        // Create products
        $products = [
            // Mevalar kategoriyasi uchun
            [
                'category_id' => 1,
                'sku' => 'APPLE001',
                'price' => 15000,
                'sale_price' => 12000,
                'stock_quantity' => 100,
                'name_uz' => 'Olma qizil',
                'name_ru' => 'Яблоко красное',
                'description_uz' => 'Toza va mazali qizil olma',
                'description_ru' => 'Чистое и вкусное красное яблоко',
            ],
            [
                'category_id' => 1,
                'sku' => 'BANANA001',
                'price' => 18000,
                'stock_quantity' => 50,
                'name_uz' => 'Banan',
                'name_ru' => 'Банан',
                'description_uz' => 'Import banani, yumshoq va shirin',
                'description_ru' => 'Импортный банан, мягкий и сладкий',
            ],
            [
                'category_id' => 1,
                'sku' => 'ORANGE001',
                'price' => 20000,
                'sale_price' => 16000,
                'stock_quantity' => 75,
                'name_uz' => 'Apelsin',
                'name_ru' => 'Апельсин',
                'description_uz' => 'Vitamin C ga boy apelsin',
                'description_ru' => 'Апельсин богатый витамином C',
            ],

            // Sabzavotlar kategoriyasi uchun
            [
                'category_id' => 2,
                'sku' => 'TOMATO001',
                'price' => 12000,
                'stock_quantity' => 200,
                'name_uz' => 'Pomidor',
                'name_ru' => 'Помидор',
                'description_uz' => 'Yangi pomidor, salat uchun ideal',
                'description_ru' => 'Свежий помидор, идеален для салата',
            ],
            [
                'category_id' => 2,
                'sku' => 'POTATO001',
                'price' => 8000,
                'stock_quantity' => 500,
                'name_uz' => 'Kartoshka',
                'name_ru' => 'Картофель',
                'description_uz' => 'Mahalliy kartoshka, yumshoq',
                'description_ru' => 'Местный картофель, мягкий',
            ],
            [
                'category_id' => 2,
                'sku' => 'CARROT001',
                'price' => 10000,
                'sale_price' => 8500,
                'stock_quantity' => 150,
                'name_uz' => 'Sabzi',
                'name_ru' => 'Морковь',
                'description_uz' => 'Vitamin A ga boy sabzi',
                'description_ru' => 'Морковь богатая витамином A',
            ],

            // Sut mahsulotlari uchun
            [
                'category_id' => 4,
                'sku' => 'MILK001',
                'price' => 9000,
                'stock_quantity' => 80,
                'name_uz' => 'Sut 1L',
                'name_ru' => 'Молоко 1Л',
                'description_uz' => 'Toza sigir suti, 3.2% yog\'li',
                'description_ru' => 'Чистое коровье молоко, 3.2% жирности',
            ],
            [
                'category_id' => 4,
                'sku' => 'CHEESE001',
                'price' => 45000,
                'sale_price' => 38000,
                'stock_quantity' => 30,
                'name_uz' => 'Pishloq',
                'name_ru' => 'Сыр',
                'description_uz' => 'Mazali golland pishlog\'i',
                'description_ru' => 'Вкусный голландский сыр',
            ],

            // Non va g'alla uchun
            [
                'category_id' => 5,
                'sku' => 'BREAD001',
                'price' => 5000,
                'stock_quantity' => 120,
                'name_uz' => 'Non oq',
                'name_ru' => 'Хлеб белый',
                'description_uz' => 'Yangi pishirilgan oq non',
                'description_ru' => 'Свежеиспеченный белый хлеб',
            ],
            [
                'category_id' => 5,
                'sku' => 'RICE001',
                'price' => 14000,
                'stock_quantity' => 200,
                'name_uz' => 'Guruch 1kg',
                'name_ru' => 'Рис 1кг',
                'description_uz' => 'Sifatli guruch, uzun donli',
                'description_ru' => 'Качественный рис, длиннозерный',
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::create([
                'category_id' => $productData['category_id'],
                'sku' => $productData['sku'],
                'price' => $productData['price'],
                'sale_price' => $productData['sale_price'] ?? null,
                'stock_quantity' => $productData['stock_quantity'],
                'is_active' => true,
                'is_featured' => rand(0, 1) == 1, // Random featured products
                'view_count' => rand(10, 500), // Random view counts
                'published_at' => now(),
            ]);

            // Add translations
            $product->translations()->create([
                'language' => 'uz',
                'name' => $productData['name_uz'],
                'description' => $productData['description_uz'],
                'slug' => \Str::slug($productData['name_uz']),
            ]);

            $product->translations()->create([
                'language' => 'ru',
                'name' => $productData['name_ru'],
                'description' => $productData['description_ru'],
                'slug' => \Str::slug($productData['name_ru']),
            ]);
        }

        $this->command->info('Test ma\'lumotlari muvaffaqiyatli qo\'shildi!');
        $this->command->info('- Kategoriyalar: ' . Category::count());
        $this->command->info('- Mahsulotlar: ' . Product::count());
        $this->command->info('- Tillar: ' . Language::count());
    }
}
