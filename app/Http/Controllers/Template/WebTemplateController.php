<?php

namespace App\Http\Controllers\Template;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class WebTemplateController extends Controller
{
    public function home()
    {
        $categories = Category::where('is_active', 1)->take(8)->get();
        $products = Product::where('is_active', 1)->take(12)->get();

        return view('template.web.pages.home', compact('categories', 'products'));
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

        $products = $query->paginate(12);
        $categories = Category::where('is_active', 1)->get();
        $brands = collect(); // Bo'sh collection, chunki Brand modeli yo'q
        $recentlyViewed = collect(); // Bo'sh collection, chunki yaqinda ko'rilganlar yo'q

        return view('template.web.pages.products', compact('products', 'categories', 'brands', 'recentlyViewed'));
    }
}
