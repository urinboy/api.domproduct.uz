<?php

namespace App\Http\Controllers\Template;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class MobileTemplateController extends Controller
{
    public function home()
    {
        $categories = Category::where('is_active', 1)->take(6)->get();
        $featuredProducts = Product::where('is_active', 1)->take(8)->get();
        $newProducts = Product::where('is_active', 1)->orderBy('created_at', 'desc')->take(8)->get();

        // Stats
        $totalProducts = Product::where('is_active', 1)->count();
        $totalUsers = \App\Models\User::count();
        $totalOrders = \App\Models\Order::count();

        return view('template.mobile.pages.home', compact(
            'categories',
            'featuredProducts',
            'newProducts',
            'totalProducts',
            'totalUsers',
            'totalOrders'
        ));
    }    public function categories()
    {
        $categories = Category::where('is_active', 1)->with('products')->get();

        return view('template.mobile.pages.categories', compact('categories'));
    }

    public function products(Request $request)
    {
        $query = Product::where('is_active', 1);

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(10);
        $categories = Category::where('is_active', 1)->get();
        $brands = collect(); // Bo'sh collection, chunki Brand modeli yo'q

        return view('template.mobile.pages.products', compact('products', 'categories', 'brands'));
    }

    public function productDetail($id)
    {
        $product = Product::where('is_active', 1)->findOrFail($id);
        $relatedProducts = Product::where('is_active', 1)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('template.mobile.pages.product-detail', compact('product', 'relatedProducts'));
    }

    public function cart()
    {
        return view('template.mobile.pages.cart');
    }

    public function wishlist()
    {
        return view('template.mobile.pages.wishlist');
    }
}
