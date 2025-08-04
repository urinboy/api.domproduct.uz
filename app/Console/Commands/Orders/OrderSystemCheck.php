<?php

namespace App\Console\Commands\Orders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\User;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class OrderSystemCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:system-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Buyurtmalar tizimini tekshirish va holatini ko\'rsatish';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔍 Buyurtmalar tizimi tekshiruvi boshlandi...');
        $this->newLine();

        // Check database tables
        $this->checkTables();
        $this->newLine();

        // Check models
        $this->checkModels();
        $this->newLine();

        // Check data
        $this->checkData();
        $this->newLine();

        // Check routes
        $this->checkRoutes();
        $this->newLine();

        $this->info('✅ Tekshiruv yakunlandi!');

        return 0;
    }

    private function checkTables()
    {
        $this->info('📋 Ma\'lumotlar bazasi jadvallarini tekshirish:');

        $tables = ['orders', 'order_items', 'order_status_histories'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $this->line("  ✅ {$table} - mavjud");
            } else {
                $this->error("  ❌ {$table} - topilmadi");
            }
        }
    }

    private function checkModels()
    {
        $this->info('🏗️  Modellarni tekshirish:');

        $models = [
            'Order' => Order::class,
            'OrderItem' => OrderItem::class,
            'OrderStatusHistory' => OrderStatusHistory::class,
        ];

        foreach ($models as $name => $class) {
            if (class_exists($class)) {
                $this->line("  ✅ {$name} - mavjud");
            } else {
                $this->error("  ❌ {$name} - topilmadi");
            }
        }
    }

    private function checkData()
    {
        $this->info('📊 Ma\'lumotlarni tekshirish:');

        try {
            $ordersCount = Order::count();
            $itemsCount = OrderItem::count();
            $historyCount = OrderStatusHistory::count();
            $usersCount = User::count();
            $productsCount = Product::count();

            $this->line("  📦 Buyurtmalar: {$ordersCount}");
            $this->line("  🛍️  Buyurtma elementlari: {$itemsCount}");
            $this->line("  📚 Holatlar tarixi: {$historyCount}");
            $this->line("  👥 Foydalanuvchilar: {$usersCount}");
            $this->line("  🎁 Mahsulotlar: {$productsCount}");

            if ($ordersCount > 0) {
                $this->newLine();
                $this->line("  📈 Buyurtmalar holati bo'yicha:");
                $statuses = Order::selectRaw('status, count(*) as count')
                    ->groupBy('status')
                    ->pluck('count', 'status');

                foreach ($statuses as $status => $count) {
                    $statusText = $this->getStatusText($status);
                    $this->line("    - {$statusText}: {$count}");
                }
            }

        } catch (\Exception $e) {
            $this->error("  ❌ Ma'lumotlarni yuklashda xatolik: " . $e->getMessage());
        }
    }

    private function checkRoutes()
    {
        $this->info('🛣️  Marshrutlarni tekshirish:');

        $routes = [
            'admin.orders.index',
            'admin.orders.show',
            'admin.orders.edit',
            'admin.orders.update',
            'admin.orders.export',
            'admin.orders.update-status',
        ];

        foreach ($routes as $route) {
            try {
                if (route($route, ['order' => 1])) {
                    $this->line("  ✅ {$route} - mavjud");
                }
            } catch (\Exception $e) {
                if (route($route)) {
                    $this->line("  ✅ {$route} - mavjud");
                } else {
                    $this->error("  ❌ {$route} - topilmadi");
                }
            }
        }
    }

    private function getStatusText($status)
    {
        switch($status) {
            case 'pending': return 'Kutilmoqda';
            case 'confirmed': return 'Tasdiqlangan';
            case 'processing': return 'Jarayonda';
            case 'shipped': return 'Yuborilgan';
            case 'delivered': return 'Yetkazilgan';
            case 'cancelled': return 'Bekor qilingan';
            case 'refunded': return 'Qaytarilgan';
            default: return $status;
        }
    }
}
