<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\ProductImage;
use App\Services\FileUploadService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    protected $fileUploadService;
    protected $notificationService;

    public function __construct(FileUploadService $fileUploadService, NotificationService $notificationService)
    {
        $this->middleware('auth:sanctum');
        $this->fileUploadService = $fileUploadService;
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of products (Admin).
     */
    public function index(Request $request)
    {
        // Check permission
        if (!$request->user()->canManageProducts()) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $query = Product::with(['translations', 'category.translations', 'primaryImage']);

        // Filter by status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
                case 'featured':
                    $query->where('is_featured', true);
                    break;
                case 'out_of_stock':
                    $query->where('stock_status', 'out_of_stock');
                    break;
                case 'low_stock':
                    $query->whereColumn('stock_quantity', '<=', 'min_stock_level');
                    break;
            }
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sku', 'LIKE', "%{$search}%")
                  ->orWhere('barcode', 'LIKE', "%{$search}%")
                  ->orWhereHas('translations', function ($tq) use ($search) {
                      $tq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('short_description', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = min($request->get('per_page', 15), 100);
        $products = $query->paginate($perPage);

        return new ProductCollection($products);
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        // Check permission
        if (!$request->user()->canManageProducts()) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $validator = Validator::make($request->all(), [
            'sku' => 'required|string|max:255|unique:products,sku',
            'barcode' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'track_stock' => 'boolean',
            'stock_status' => 'required|in:in_stock,out_of_stock,on_backorder',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_digital' => 'boolean',
            'sort_order' => 'integer|min:0',
            'published_at' => 'nullable|date',

            // Translations
            'translations' => 'required|array|min:1',
            'translations.*.language' => 'required|string|max:5',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.slug' => 'nullable|string|max:255',
            'translations.*.short_description' => 'nullable|string',
            'translations.*.description' => 'nullable|string',
            'translations.*.specifications' => 'nullable|array',
            'translations.*.features' => 'nullable|array',
            'translations.*.care_instructions' => 'nullable|string',
            'translations.*.warranty_info' => 'nullable|string',
            'translations.*.meta_title' => 'nullable|string|max:255',
            'translations.*.meta_description' => 'nullable|string|max:500',
            'translations.*.meta_keywords' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            DB::beginTransaction();

            // Create product
            $product = Product::create($request->only([
                'sku', 'barcode', 'category_id', 'price', 'sale_price', 'cost_price',
                'stock_quantity', 'min_stock_level', 'track_stock', 'stock_status',
                'weight', 'length', 'width', 'height', 'is_active', 'is_featured',
                'is_digital', 'sort_order', 'published_at'
            ]));

            // Create translations
            foreach ($request->translations as $translationData) {
                $product->translations()->create($translationData);
            }

            DB::commit();

            return new ProductResource($product->load(['translations', 'category.translations']));

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to create product',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Request $request, $id)
    {
        // Check permission
        if (!$request->user()->canManageProducts()) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $product = Product::with(['translations', 'category.translations', 'images'])
            ->findOrFail($id);

        return new ProductResource($product);
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, $id)
    {
        // Check permission
        if (!$request->user()->canManageProducts()) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $product = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'sku' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'barcode' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'track_stock' => 'boolean',
            'stock_status' => 'required|in:in_stock,out_of_stock,on_backorder',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_digital' => 'boolean',
            'sort_order' => 'integer|min:0',
            'published_at' => 'nullable|date',

            // Translations
            'translations' => 'required|array|min:1',
            'translations.*.id' => 'nullable|exists:product_translations,id',
            'translations.*.language' => 'required|string|max:5',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.slug' => 'nullable|string|max:255',
            'translations.*.short_description' => 'nullable|string',
            'translations.*.description' => 'nullable|string',
            'translations.*.specifications' => 'nullable|array',
            'translations.*.features' => 'nullable|array',
            'translations.*.care_instructions' => 'nullable|string',
            'translations.*.warranty_info' => 'nullable|string',
            'translations.*.meta_title' => 'nullable|string|max:255',
            'translations.*.meta_description' => 'nullable|string|max:500',
            'translations.*.meta_keywords' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            DB::beginTransaction();

            // Update product
            $product->update($request->only([
                'sku', 'barcode', 'category_id', 'price', 'sale_price', 'cost_price',
                'stock_quantity', 'min_stock_level', 'track_stock', 'stock_status',
                'weight', 'length', 'width', 'height', 'is_active', 'is_featured',
                'is_digital', 'sort_order', 'published_at'
            ]));

            // Update translations
            $submittedTranslationIds = [];
            foreach ($request->translations as $translationData) {
                if (isset($translationData['id'])) {
                    // Update existing translation
                    $translation = ProductTranslation::where('id', $translationData['id'])
                        ->where('product_id', $product->id)
                        ->firstOrFail();
                    $translation->update($translationData);
                    $submittedTranslationIds[] = $translation->id;
                } else {
                    // Create new translation
                    $translation = $product->translations()->create($translationData);
                    $submittedTranslationIds[] = $translation->id;
                }
            }

            // Delete translations that weren't submitted
            $product->translations()
                ->whereNotIn('id', $submittedTranslationIds)
                ->delete();

            DB::commit();

            return new ProductResource($product->load(['translations', 'category.translations']));

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to update product',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Request $request, $id)
    {
        // Check permission
        if (!$request->user()->canManageProducts()) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        try {
            $product = Product::findOrFail($id);

            // Delete all associated images
            foreach ($product->images as $image) {
                $image->deleteFiles();
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete product',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Upload product image.
     */
    public function uploadImage(Request $request, $id)
    {
        // Check permission
        if (!$request->user()->canManageProducts()) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
            'alt_text' => 'nullable|string|max:255',
            'is_primary' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $product = Product::findOrFail($id);

            // Upload and process image
            $uploadResult = $this->fileUploadService->uploadImage(
                $request->file('image'),
                'products/' . $product->id
            );

            // Create product image record
            $productImage = ProductImage::create([
                'product_id' => $product->id,
                'filename' => $uploadResult['original_name'],
                'path' => $uploadResult['path'],
                'alt_text' => $request->alt_text,
                'sort_order' => $request->get('sort_order', 0),
                'mime_type' => $uploadResult['mime_type'],
                'file_size' => $uploadResult['file_size'],
                'width' => $uploadResult['width'],
                'height' => $uploadResult['height'],
                'is_primary' => $request->boolean('is_primary'),
                'sizes' => $uploadResult['sizes'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'data' => [
                    'id' => $productImage->id,
                    'alt_text' => $productImage->alt_text,
                    'sort_order' => $productImage->sort_order,
                    'is_primary' => $productImage->is_primary,
                    'urls' => $productImage->getAllUrls(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to upload image',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete product image.
     */
    public function deleteImage(Request $request, $id, $imageId)
    {
        // Check permission
        if (!$request->user()->canManageProducts()) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        try {
            $product = Product::findOrFail($id);
            $image = ProductImage::where('product_id', $product->id)
                ->where('id', $imageId)
                ->firstOrFail();

            $image->delete(); // This will trigger the deleteFiles() method

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete image',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Set primary image.
     */
    public function setPrimaryImage(Request $request, $id, $imageId)
    {
        // Check permission
        if (!$request->user()->canManageProducts()) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        try {
            $product = Product::findOrFail($id);
            $image = ProductImage::where('product_id', $product->id)
                ->where('id', $imageId)
                ->firstOrFail();

            $image->setPrimary();

            return response()->json([
                'success' => true,
                'message' => 'Primary image set successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to set primary image',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get product statistics.
     */
    public function statistics(Request $request)
    {
        // Check permission
        if (!$request->user()->canManageProducts()) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'featured_products' => Product::where('is_featured', true)->count(),
            'out_of_stock' => Product::where('stock_status', 'out_of_stock')->count(),
            'low_stock' => Product::whereColumn('stock_quantity', '<=', 'min_stock_level')->count(),
            'published_products' => Product::whereNotNull('published_at')->where('published_at', '<=', now())->count(),
            'total_value' => Product::sum(DB::raw('stock_quantity * price')),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Kam zaxiradagi mahsulotlar ro'yxati
     */
    public function lowStock(Request $request)
    {
        // Check permission
        if (!$request->user()->canManageProducts()) {
            return response()->json(['error' => 'Ruxsat berilmagan'], Response::HTTP_FORBIDDEN);
        }

        try {
            $threshold = $request->threshold ?? 10; // Default 10 ta

            $query = Product::with(['translations', 'category.translations', 'primaryImage'])
                           ->where(function($q) use ($threshold) {
                               $q->where('stock_quantity', '<=', $threshold)
                                 ->orWhereColumn('stock_quantity', '<=', 'min_stock_level');
                           })
                           ->where('is_active', true);

            // Sorting
            $sortBy = $request->sort_by ?? 'stock_quantity';
            $sortOrder = $request->sort_order ?? 'asc';
            $query->orderBy($sortBy, $sortOrder);

            $products = $query->paginate(20);

            $productsData = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->price,
                    'stock_quantity' => $product->stock_quantity,
                    'min_stock_level' => $product->min_stock_level,
                    'stock_status' => $product->stock_status,
                    'category' => $product->category ? $product->category->name : null,
                    'is_active' => $product->is_active,
                    'image' => $product->primaryImage ? $product->primaryImage->image_url : null,
                    'stock_level' => $this->getStockLevel($product),
                    'needs_restock' => $product->stock_quantity <= $product->min_stock_level,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Kam zaxiradagi mahsulotlar ro\'yxati',
                'data' => [
                    'products' => $productsData,
                    'pagination' => [
                        'current_page' => $products->currentPage(),
                        'total_pages' => $products->lastPage(),
                        'total_items' => $products->total(),
                        'per_page' => $products->perPage(),
                    ],
                    'threshold' => $threshold
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kam zaxiradagi mahsulotlarni olishda xatolik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mahsulot zaxirasini yangilash
     */
    public function updateStock(Request $request, $id)
    {
        // Check permission
        if (!$request->user()->canManageProducts()) {
            return response()->json(['error' => 'Ruxsat berilmagan'], Response::HTTP_FORBIDDEN);
        }

        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'note' => 'nullable|string|max:500',
        ]);

        try {
            $product = Product::find($id);
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mahsulot topilmadi'
                ], 404);
            }

            $oldStock = $product->stock_quantity;
            $newStock = $request->stock_quantity;
            $stockChange = $newStock - $oldStock;

            // Zaxirani yangilash
            $product->update([
                'stock_quantity' => $newStock,
                'min_stock_level' => $request->min_stock_level ?? $product->min_stock_level,
                'stock_status' => $this->calculateStockStatus($newStock, $product->min_stock_level),
            ]);

            // Zaxira tarixini saqlash (keyinchalik implement qilamiz)
            // StockHistory::create([...]);

            // Low stock notification yuborish
            if ($product->stock_quantity <= $product->min_stock_level) {
                $this->notificationService->sendLowStockAlert($product);
            }

            return response()->json([
                'success' => true,
                'message' => 'Mahsulot zaxirasi muvaffaqiyatli yangilandi',
                'data' => [
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'old_stock' => $oldStock,
                        'new_stock' => $newStock,
                        'stock_change' => $stockChange,
                        'stock_status' => $product->stock_status,
                        'updated_at' => $product->updated_at,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Zaxirani yangilashda xatolik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ommaviy zaxira yangilash
     */
    public function bulkUpdateStock(Request $request)
    {
        // Check permission
        if (!$request->user()->canManageProducts()) {
            return response()->json(['error' => 'Ruxsat berilmagan'], Response::HTTP_FORBIDDEN);
        }

        $request->validate([
            'updates' => 'required|array|min:1',
            'updates.*.product_id' => 'required|exists:products,id',
            'updates.*.stock_quantity' => 'required|integer|min:0',
            'updates.*.min_stock_level' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $updated = [];
            $errors = [];

            foreach ($request->updates as $update) {
                try {
                    $product = Product::find($update['product_id']);
                    if (!$product) {
                        $errors[] = "Mahsulot topilmadi: ID {$update['product_id']}";
                        continue;
                    }

                    $oldStock = $product->stock_quantity;
                    $newStock = $update['stock_quantity'];

                    $product->update([
                        'stock_quantity' => $newStock,
                        'min_stock_level' => $update['min_stock_level'] ?? $product->min_stock_level,
                        'stock_status' => $this->calculateStockStatus($newStock, $product->min_stock_level),
                    ]);

                    $updated[] = [
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'old_stock' => $oldStock,
                        'new_stock' => $newStock,
                        'stock_change' => $newStock - $oldStock,
                    ];

                } catch (\Exception $e) {
                    $errors[] = "Mahsulot {$update['product_id']}: " . $e->getMessage();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ommaviy zaxira yangilash yakunlandi',
                'data' => [
                    'updated_count' => count($updated),
                    'error_count' => count($errors),
                    'updated_products' => $updated,
                    'errors' => $errors,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Ommaviy yangilashda xatolik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mahsulot analitikasi
     */
    public function analytics(Request $request)
    {
        // Check permission
        if (!$request->user()->canManageProducts()) {
            return response()->json(['error' => 'Ruxsat berilmagan'], Response::HTTP_FORBIDDEN);
        }

        try {
            $dateFrom = $request->date_from ?? now()->subDays(30)->format('Y-m-d');
            $dateTo = $request->date_to ?? now()->format('Y-m-d');

            // Eng ko'p sotiladigan mahsulotlar
            $topSelling = DB::table('order_items')
                           ->join('products', 'order_items.product_id', '=', 'products.id')
                           ->join('orders', 'order_items.order_id', '=', 'orders.id')
                           ->whereDate('orders.created_at', '>=', $dateFrom)
                           ->whereDate('orders.created_at', '<=', $dateTo)
                           ->whereIn('orders.status', ['confirmed', 'processing', 'shipped', 'delivered'])
                           ->selectRaw('
                               products.id,
                               products.name,
                               products.sku,
                               SUM(order_items.quantity) as total_sold,
                               SUM(order_items.total_price) as total_revenue,
                               AVG(order_items.unit_price) as avg_price
                           ')
                           ->groupBy('products.id', 'products.name', 'products.sku')
                           ->orderBy('total_sold', 'desc')
                           ->limit(10)
                           ->get();

            // Kategoriya bo'yicha sotuv
            $categoryStats = DB::table('order_items')
                              ->join('products', 'order_items.product_id', '=', 'products.id')
                              ->join('categories', 'products.category_id', '=', 'categories.id')
                              ->join('orders', 'order_items.order_id', '=', 'orders.id')
                              ->whereDate('orders.created_at', '>=', $dateFrom)
                              ->whereDate('orders.created_at', '<=', $dateTo)
                              ->whereIn('orders.status', ['confirmed', 'processing', 'shipped', 'delivered'])
                              ->selectRaw('
                                  categories.id,
                                  categories.name,
                                  SUM(order_items.quantity) as total_sold,
                                  SUM(order_items.total_price) as total_revenue,
                                  COUNT(DISTINCT order_items.product_id) as products_count
                              ')
                              ->groupBy('categories.id', 'categories.name')
                              ->orderBy('total_revenue', 'desc')
                              ->get();

            // Zaxira holati
            $stockStats = [
                'in_stock' => Product::where('stock_quantity', '>', 0)->count(),
                'out_of_stock' => Product::where('stock_quantity', '=', 0)->count(),
                'low_stock' => Product::whereColumn('stock_quantity', '<=', 'min_stock_level')->count(),
                'total_value' => Product::sum(DB::raw('stock_quantity * price')),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Mahsulot analitikasi muvaffaqiyatli olindi',
                'data' => [
                    'period' => [
                        'date_from' => $dateFrom,
                        'date_to' => $dateTo,
                    ],
                    'top_selling_products' => $topSelling,
                    'category_performance' => $categoryStats,
                    'stock_overview' => $stockStats,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Analitikani olishda xatolik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Zaxira holatini aniqlash
     */
    private function calculateStockStatus($stockQuantity, $minStockLevel = 0)
    {
        if ($stockQuantity <= 0) {
            return 'out_of_stock';
        } elseif ($stockQuantity <= $minStockLevel) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    /**
     * Zaxira darajasini aniqlash
     */
    private function getStockLevel($product)
    {
        if ($product->stock_quantity <= 0) {
            return 'Tugagan';
        } elseif ($product->stock_quantity <= $product->min_stock_level) {
            return 'Kam';
        } elseif ($product->stock_quantity <= ($product->min_stock_level * 2)) {
            return 'O\'rtacha';
        } else {
            return 'Yetarli';
        }
    }
}
