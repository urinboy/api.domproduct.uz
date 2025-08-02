<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

/**
 * @group Product Management
 * 
 * APIs for managing products with comprehensive documentation and validation
 */
class ProductController extends Controller
{
    /**
     * Get products list with filtering and pagination
     * 
     * @group Public API
     * 
     * @queryParam language string The language code for translations (uz, ru, en). Example: uz
     * @queryParam category_id integer Filter by category ID. Example: 1
     * @queryParam is_featured boolean Filter featured products. Example: 1
     * @queryParam search string Search in product names and descriptions. Example: phone
     * @queryParam min_price number Minimum price filter. Example: 100000
     * @queryParam max_price number Maximum price filter. Example: 500000
     * @queryParam sort_by string Sort field (name, price, created_at). Example: price
     * @queryParam sort_order string Sort direction (asc, desc). Example: desc
     * @queryParam page integer Page number for pagination. Example: 1
     * @queryParam per_page integer Items per page (max 50). Example: 10
     * 
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "sku": "PROD001",
     *       "price": 150000,
     *       "is_active": true,
     *       "is_featured": true,
     *       "stock_status": "in_stock",
     *       "category": {
     *         "id": 1,
     *         "name": "Electronics",
     *         "slug": "electronics"
     *       },
     *       "translations": [
     *         {
     *           "language": "uz",
     *           "name": "Smartphone",
     *           "description": "Zamonaviy smartphone",
     *           "slug": "smartphone"
     *         }
     *       ],
     *       "images": [
     *         {
     *           "id": 1,
     *           "file_path": "/images/products/phone1.jpg",
     *           "is_primary": true
     *         }
     *       ]
     *     }
     *   ],
     *   "meta": {
     *     "current_page": 1,
     *     "per_page": 10,
     *     "total": 25,
     *     "last_page": 3
     *   }
     * }
     * 
     * @response 422 {
     *   "error": "Validation Error",
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "per_page": ["The per page must not be greater than 50."]
     *   }
     * }
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Input validation
            $validator = Validator::make($request->all(), [
                'language' => 'sometimes|string|in:uz,ru,en',
                'category_id' => 'sometimes|integer|exists:categories,id',
                'is_featured' => 'sometimes|boolean',
                'search' => 'sometimes|string|max:255',
                'min_price' => 'sometimes|numeric|min:0',
                'max_price' => 'sometimes|numeric|min:0',
                'sort_by' => 'sometimes|string|in:name,price,created_at',
                'sort_order' => 'sometimes|string|in:asc,desc',
                'page' => 'sometimes|integer|min:1',
                'per_page' => 'sometimes|integer|min:1|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation Error',
                    'message' => 'The given data was invalid.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $language = $request->get('language', 'uz');
            $perPage = min($request->get('per_page', 10), 50);
            $cacheKey = 'products_' . md5(serialize($request->all()));

            // Cache for 5 minutes
            $products = Cache::remember($cacheKey, 300, function () use ($request, $language, $perPage) {
                $query = Product::with([
                    'translations' => function($q) use ($language) {
                        $q->where('language', $language);
                    },
                    'images' => function($q) {
                        $q->orderBy('sort_order')->limit(3);
                    },
                    'category.translations' => function($q) use ($language) {
                        $languageId = $this->getLanguageId($language);
                        $q->where('language_id', $languageId);
                    }
                ])->where('is_active', true);

                // Apply filters
                if ($request->has('category_id')) {
                    $query->where('category_id', $request->category_id);
                }

                if ($request->has('is_featured')) {
                    $query->where('is_featured', $request->boolean('is_featured'));
                }

                if ($request->has('search')) {
                    $searchTerm = $request->search;
                    $query->whereHas('translations', function($q) use ($searchTerm) {
                        $q->where('name', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('description', 'LIKE', "%{$searchTerm}%");
                    });
                }

                if ($request->has('min_price')) {
                    $query->where('price', '>=', $request->min_price);
                }

                if ($request->has('max_price')) {
                    $query->where('price', '<=', $request->max_price);
                }

                // Apply sorting
                $sortBy = $request->get('sort_by', 'created_at');
                $sortOrder = $request->get('sort_order', 'desc');
                
                if ($sortBy === 'name') {
                    $query->join('product_translations', function($join) use ($language) {
                        $join->on('products.id', '=', 'product_translations.product_id')
                             ->where('product_translations.language', '=', $language);
                    })->orderBy('product_translations.name', $sortOrder);
                } else {
                    $query->orderBy($sortBy, $sortOrder);
                }

                return $query->paginate($perPage);
            });

            return response()->json([
                'data' => $products->items(),
                'meta' => [
                    'current_page' => $products->currentPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'last_page' => $products->lastPage(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Product index error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'error' => 'Internal Server Error',
                'message' => 'Something went wrong while fetching products.'
            ], 500);
        }
    }
                $query->where('category_id', $category->id);
            }
        }

        // Search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $language = $request->header('Accept-Language', 'uz');

            $query->whereHas('translations', function ($q) use ($search, $language) {
                $q->where('language', $language)
                  ->where(function ($q2) use ($search) {
                      $q2->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('short_description', 'LIKE', "%{$search}%")
                         ->orWhere('description', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by featured
        if ($request->boolean('featured')) {
            $query->featured();
        }

        // Filter by in stock
        if ($request->boolean('in_stock')) {
            $query->inStock();
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        switch ($sortBy) {
            case 'price':
                $query->orderBy('price', $sortOrder);
                break;
            case 'name':
                $language = $request->header('Accept-Language', 'uz');
                $query->join('product_translations', function ($join) use ($language) {
                    $join->on('products.id', '=', 'product_translations.product_id')
                         ->where('product_translations.language', $language);
                })->orderBy('product_translations.name', $sortOrder);
                break;
            case 'rating':
                $query->orderBy('rating', $sortOrder);
                break;
            case 'popularity':
                $query->orderBy('view_count', $sortOrder);
                break;
            default:
                $query->orderBy('created_at', $sortOrder);
        }

        $perPage = min($request->get('per_page', 15), 50); // Max 50 items per page
        $products = $query->paginate($perPage);

        return new ProductCollection($products);
    }

    /**
     * Display the specified product.
     */
    public function show(Request $request, $id)
    {
        $product = Product::with(['translations', 'category.translations', 'images'])
            ->active()
            ->published()
            ->findOrFail($id);

        // Increment view count
        $product->incrementViewCount();

        return new ProductResource($product);
    }

    /**
     * Display the specified product by slug.
     */
    public function showBySlug(Request $request, $slug)
    {
        $language = $request->header('Accept-Language', 'uz');

        $product = Product::with(['translations', 'category.translations', 'images'])
            ->active()
            ->published()
            ->whereHas('translations', function ($query) use ($slug, $language) {
                $query->where('slug', $slug)->where('language', $language);
            })
            ->firstOrFail();

        // Increment view count
        $product->incrementViewCount();

        return new ProductResource($product);
    }

    /**
     * Get related products.
     */
    public function related(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $relatedProducts = Product::with(['translations', 'category.translations', 'primaryImage'])
            ->active()
            ->published()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->limit($request->get('limit', 8))
            ->get();

        return ProductResource::collection($relatedProducts);
    }

    /**
     * Get featured products.
     */
    public function featured(Request $request)
    {
        $products = Product::with(['translations', 'category.translations', 'primaryImage'])
            ->active()
            ->published()
            ->featured()
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->limit($request->get('limit', 10))
            ->get();

        return ProductResource::collection($products);
    }

    /**
     * Get products by category.
     */
    public function byCategory(Request $request, $categoryId)
    {
        $category = Category::active()->findOrFail($categoryId);

        $query = Product::with(['translations', 'category.translations', 'primaryImage'])
            ->active()
            ->published()
            ->where('category_id', $categoryId);

        // Include subcategory products if requested
        if ($request->boolean('include_subcategories')) {
            $subcategoryIds = $category->children()->pluck('id')->toArray();
            if (!empty($subcategoryIds)) {
                $query->orWhereIn('category_id', $subcategoryIds);
            }
        }

        $perPage = min($request->get('per_page', 15), 50);
        $products = $query->paginate($perPage);

        return new ProductCollection($products);
    }
}
