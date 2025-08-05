<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'translations']);

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(15);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $languages = \App\Models\Language::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.products.create', compact('categories', 'languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Get default language
        $defaultLanguage = \App\Models\Language::where('is_default', true)->first();

        $request->validate([
            'sku' => 'required|string|max:255|unique:products',
            'barcode' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'unit_type' => 'required|string|in:' . implode(',', array_keys(\App\Models\Product::getUnitTypes())),
            'unit_value' => 'nullable|numeric|min:0.001',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'translations' => 'required|array',
            'translations.' . $defaultLanguage->id . '.name' => 'required|string|max:255',
            'translations.*.name' => 'nullable|string|max:255',
            'translations.*.slug' => 'nullable|string|max:255',
            'translations.*.description' => 'nullable|string|max:65535',
        ]);

        $product = Product::create([
            'sku' => $request->sku,
            'barcode' => $request->barcode,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'cost_price' => $request->cost_price,
            'unit_type' => $request->unit_type,
            'unit_value' => $request->unit_value ?? 1,
            'stock_quantity' => $request->stock_quantity,
            'category_id' => $request->category_id,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Store translations
        foreach ($request->translations as $languageId => $translation) {
            if (!empty($translation['name'])) {
                $slug = $translation['slug'] ?? \Str::slug($translation['name']);

                $product->translations()->create([
                    'language_id' => $languageId,
                    'name' => $translation['name'],
                    'slug' => $slug,
                    'description' => $translation['description'] ?? null,
                ]);
            }
        }

        if ($request->hasFile('images')) {
            $this->uploadImages($request, $product);
        }

        return redirect()->route('admin.products.index')
            ->with('success', __('admin.product_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'translations', 'images']);

        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $languages = \App\Models\Language::where('is_active', true)->orderBy('sort_order')->get();
        $product->load('translations');

        return view('admin.products.edit', compact('product', 'categories', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Get default language
        $defaultLanguage = \App\Models\Language::where('is_default', true)->first();

        $request->validate([
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'unit_type' => 'required|string|in:' . implode(',', array_keys(\App\Models\Product::getUnitTypes())),
            'unit_value' => 'nullable|numeric|min:0.001',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'translations' => 'required|array',
            'translations.' . $defaultLanguage->id . '.name' => 'required|string|max:255',
            'translations.*.name' => 'nullable|string|max:255',
            'translations.*.slug' => 'nullable|string|max:255',
            'translations.*.description' => 'nullable|string|max:65535',
        ]);

        $product->update([
            'sku' => $request->sku,
            'barcode' => $request->barcode,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'cost_price' => $request->cost_price,
            'unit_type' => $request->unit_type,
            'unit_value' => $request->unit_value ?? 1,
            'stock_quantity' => $request->stock_quantity,
            'category_id' => $request->category_id,
            'is_active' => $request->boolean('is_active'),
        ]);

        // Update translations
        foreach ($request->translations as $languageId => $translation) {
            if (!empty($translation['name'])) {
                $slug = $translation['slug'] ?? \Str::slug($translation['name']);

                $product->translations()->updateOrCreate(
                    ['language_id' => $languageId],
                    [
                        'name' => $translation['name'],
                        'slug' => $slug,
                        'description' => $translation['description'] ?? null,
                    ]
                );
            }
        }

        if ($request->hasFile('images')) {
            $this->uploadImages($request, $product);
        }

        return redirect()->route('admin.products.index')
            ->with('success', __('admin.product_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', __('admin.product_deleted_successfully'));
    }

    /**
     * Show low stock products
     */
    public function lowStock()
    {
        $products = Product::where('stock_quantity', '<=', 10)
            ->with(['category'])
            ->paginate(15);

        return view('admin.products.low-stock', compact('products'));
    }

    /**
     * Upload product images
     */
    public function uploadImages(Request $request, Product $product)
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('products', $filename, 'public');

                $product->images()->create([
                    'image_path' => $path,
                    'image_url' => asset('storage/' . $path),
                    'is_primary' => $product->images()->count() === 0,
                ]);
            }
        }

        return response()->json(['success' => true]);
    }
}
