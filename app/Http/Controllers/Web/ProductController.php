<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of products (refactored to use ProductService)
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 20);
        $params = $request->only(['category', 'sort', 'price_min', 'price_max', 'search']);

        $products = $this->service->getListing($params, $perPage);

        // Kategoriyalar
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with('children')
            ->get();

        // Narx oralig'i
        $priceRange = Product::where('is_active', true)->selectRaw('MIN(price) as min, MAX(price) as max')->first();

        return view('web.products.index', compact(
            'products', 'categories', 'priceRange'
        ));
    }

    /**
     * Display the specified product
     */
    public function show($id)
    {
        $product = $this->service->find((int)$id);

        // Ko'rishlar sonini oshirish
        $product->increment('view_count');

        // O'xshash mahsulotlar
        $relatedProducts = Product::where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(8)
            ->get();

        // Kategoriya yo'li
        $breadcrumbs = [];
        $category = $product->category;
        while ($category) {
            array_unshift($breadcrumbs, $category);
            $category = $category->parent;
        }

        return view('web.products.show', compact('product', 'relatedProducts', 'breadcrumbs'));
    }

    /**
     * Add product to favorites (requires authentication)
     */
    // public function addToFavorites(Request $request, $id)
    // {
    //     if (!auth()->check()) {
    //         return response()->json(['message' => 'Tizimga kirishingiz kerak'], 401);
    //     }

    //     $product = Product::findOrFail($id);
    //     $user = auth()->user();

    //     // Sevimlilar jadvalidagi bog'lanishni tekshirish
    //     $exists = $user->favoriteProducts()->where('product_id', $id)->exists();

    //     if ($exists) {
    //         $user->favoriteProducts()->detach($id);
    //         return response()->json(['message' => 'Sevimlilardan olib tashlandi', 'status' => 'removed']);
    //     } else {
    //         $user->favoriteProducts()->attach($id);
    //         return response()->json(['message' => 'Sevimlilarga qo\'shildi', 'status' => 'added']);
    //     }
    // }

    /**
     * Get product quick view data
     */
    public function quickView($id)
    {
        $product = Product::where('is_active', true)->findOrFail($id);

        return response()->json([
            'id' => $product->id,
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'price' => $product->price,
            'sale_price' => $product->sale_price,
            'formatted_price' => number_format($product->price, 0, '.', ' ') . ' so\'m',
            'formatted_sale_price' => $product->sale_price ? number_format($product->sale_price, 0, '.', ' ') . ' so\'m' : null,
            'images' => $product->images ? array_map(function($image) {
                return asset('storage/' . $image);
            }, $product->images) : [asset('images/no-image.jpg')],
            'in_stock' => $product->quantity > 0,
            'quantity' => $product->quantity,
            'unit' => $product->getFormattedUnit(),
            'category' => $product->category ? $product->category->getName() : null,
        ]);
    }

    /**
     * Get product variants (for future use)
     */
    public function getVariants($id)
    {
        $product = Product::findOrFail($id);

        // Bu yerda product variantlari logikasi bo'ladi
        // Hozircha bo'sh array qaytaramiz
        return response()->json([
            'variants' => []
        ]);
    }
}
