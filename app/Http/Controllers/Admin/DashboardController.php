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
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        // Basic statistics
        $totalUsers = User::count();
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();

        // Recent entities
        $recentUsers = User::latest()->limit(10)->get();
        $recentOrders = Order::with('user')->latest()->limit(10)->get();

        // Order statistics by status
        $newOrders = Order::where('status', 'new')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        // User statistics by role
        $adminUsers = User::where('role', 'admin')->count();
        $managerUsers = User::where('role', 'manager')->count();
        $customerUsers = User::where('role', 'customer')->count();

        // Monthly data for charts (last 12 months)
        $monthlyLabels = [];
        $monthlyUsers = [];
        $monthlyOrders = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyLabels[] = $month->format('M');

            $monthlyUsers[] = User::whereYear('created_at', $month->year)
                                 ->whereMonth('created_at', $month->month)
                                 ->count();

            $monthlyOrders[] = Order::whereYear('created_at', $month->year)
                                   ->whereMonth('created_at', $month->month)
                                   ->count();
        }

        // Weekly data for line chart (last 7 days)
        $weeklyLabels = [];
        $weeklyUsers = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklyLabels[] = $date->format('D');
            $weeklyUsers[] = User::whereDate('created_at', $date)->count();
        }

        // Revenue statistics (if you have price/amount fields)
        $monthlyRevenue = 0;
        $totalRevenue = 0;

        // Get orders with amount if exists
        if (Schema::hasColumn('orders', 'total_amount')) {
            $monthlyRevenue = Order::whereMonth('created_at', now()->month)
                                  ->whereYear('created_at', now()->year)
                                  ->sum('total_amount');
            $totalRevenue = Order::sum('total_amount');
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalOrders',
            'totalProducts',
            'totalCategories',
            'recentUsers',
            'recentOrders',
            'newOrders',
            'pendingOrders',
            'completedOrders',
            'cancelledOrders',
            'adminUsers',
            'managerUsers',
            'customerUsers',
            'monthlyLabels',
            'monthlyUsers',
            'monthlyOrders',
            'weeklyLabels',
            'weeklyUsers',
            'monthlyRevenue',
            'totalRevenue'
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
