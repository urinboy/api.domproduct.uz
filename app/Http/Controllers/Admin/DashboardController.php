<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Notification;
use App\Models\CartItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        // Asosiy statistikalar
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalOrders = Order::count();
        $totalPayments = Payment::count();

        // Bugungi statistikalar
        $todayUsers = User::whereDate('created_at', Carbon::today())->count();
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();
        $todayProducts = Product::whereDate('created_at', Carbon::today())->count();

        // Bu oydagi statistikalar
        $monthlyUsers = User::whereMonth('created_at', Carbon::now()->month)
                           ->whereYear('created_at', Carbon::now()->year)
                           ->count();

        $monthlyOrders = Order::whereMonth('created_at', Carbon::now()->month)
                             ->whereYear('created_at', Carbon::now()->year)
                             ->count();

        // Oxirgi 7 kunlik statistikalar
        $weeklyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $weeklyStats[] = [
                'date' => $date->format('Y-m-d'),
                'date_display' => $date->format('d M'),
                'users' => User::whereDate('created_at', $date)->count(),
                'orders' => Order::whereDate('created_at', $date)->count(),
                'products' => Product::whereDate('created_at', $date)->count(),
            ];
        }

        // Eng ko'p sotilgan mahsulotlar (order items orqali)
                // Top selling products based on order items count
        $topSellingProducts = Product::withCount(['orderItems' => function($query) {
                $query->whereHas('order', function($orderQuery) {
                    $orderQuery->where('created_at', '>=', now()->subMonth());
                });
            }])
            ->with(['category.translations', 'images'])
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();

        // Eng yuqori narxli mahsulotlar
        $expensiveProducts = Product::with(['category'])
                                  ->where('is_active', true)
                                  ->orderBy('price', 'desc')
                                  ->limit(5)
                                  ->get();

        // So'nggi ro'yxatdan o'tgan foydalanuvchilar
        $recentUsers = User::orderBy('created_at', 'desc')
                          ->limit(5)
                          ->get();

        // So'nggi buyurtmalar
        $recentOrders = Order::with(['user', 'orderItems.product'])
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();

        // Kategoriyalar bo'yicha mahsulotlar taqsimoti
        $categoriesWithProducts = Category::withCount('products')
                                        ->orderBy('products_count', 'desc')
                                        ->limit(8)
                                        ->get();

        // Status bo'yicha buyurtmalar
        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
                              ->groupBy('status')
                              ->get()
                              ->pluck('count', 'status');

        // Jami daromad (payments jadvalidan)
        $totalRevenue = Payment::where('status', 'completed')->sum('amount') ?? 0;
        $monthlyRevenue = Payment::where('status', 'completed')
                                ->whereMonth('created_at', Carbon::now()->month)
                                ->whereYear('created_at', Carbon::now()->year)
                                ->sum('amount') ?? 0;

        // Stok holati
        $lowStockProducts = Product::where('track_stock', true)
                                  ->whereColumn('stock_quantity', '<=', 'min_stock_level')
                                  ->count();

        // Faol va nofaol mahsulotlar
        $activeProducts = Product::where('is_active', true)->count();
        $inactiveProducts = Product::where('is_active', false)->count();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalOrders',
            'totalPayments',
            'lowStockProducts',
            'topSellingProducts',
            'weeklyStats',
            'totalRevenue',
            'pendingOrders',
            'recentNotifications'
        ));
    }

    /**
     * API uchun dashboard ma'lumotlari
     */
    public function apiStats()
    {
        $stats = [
            'overview' => [
                'total_users' => User::count(),
                'total_products' => Product::count(),
                'total_categories' => Category::count(),
                'total_orders' => Order::count(),
                'total_payments' => Payment::count(),
            ],
            'today' => [
                'new_users' => User::whereDate('created_at', Carbon::today())->count(),
                'new_orders' => Order::whereDate('created_at', Carbon::today())->count(),
                'new_products' => Product::whereDate('created_at', Carbon::today())->count(),
            ],
            'monthly' => [
                'users' => User::whereMonth('created_at', Carbon::now()->month)->count(),
                'orders' => Order::whereMonth('created_at', Carbon::now()->month)->count(),
                'revenue' => Payment::where('status', 'completed')
                                   ->whereMonth('created_at', Carbon::now()->month)
                                   ->sum('amount') ?? 0,
            ],
            'stock' => [
                'low_stock_products' => Product::where('track_stock', true)
                                              ->whereColumn('stock_quantity', '<=', 'min_stock_level')
                                              ->count(),
                'active_products' => Product::where('is_active', true)->count(),
                'inactive_products' => Product::where('is_active', false)->count(),
            ]
        ];

        return response()->json($stats);
    }
}
