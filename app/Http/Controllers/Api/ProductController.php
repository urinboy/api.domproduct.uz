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

    /**
     * Get featured products
     *
     * @group Public API
     *
     * @queryParam language string The language code for translations. Example: uz
     * @queryParam limit integer Number of products to return (max 20). Example: 6
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Featured Product",
     *       "price": 150000,
     *       "image": "/images/products/featured1.jpg"
     *     }
     *   ]
     * }
     */
    public function featured(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'language' => 'sometimes|string|in:uz,ru,en',
                'limit' => 'sometimes|integer|min:1|max:20',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $language = $request->get('language', 'uz');
            $limit = min($request->get('limit', 6), 20);

            $cacheKey = "featured_products_{$language}_{$limit}";

            $products = Cache::remember($cacheKey, 600, function () use ($language, $limit) {
                return Product::with([
                    'translations' => function($q) use ($language) {
                        $q->where('language', $language);
                    },
                    'images' => function($q) {
                        $q->where('is_primary', true)->limit(1);
                    }
                ])
                ->where('is_active', true)
                ->where('is_featured', true)
                ->limit($limit)
                ->get()
                ->map(function ($product) {
                    $translation = $product->translations->first();
                    $image = $product->images->first();

                    return [
                        'id' => $product->id,
                        'name' => $translation ? $translation->name : null,
                        'price' => $product->price,
                        'image' => $image ? $image->file_path : null,
                        'slug' => $translation ? $translation->slug : null,
                    ];
                });
            });

            return response()->json(['data' => $products]);

        } catch (\Exception $e) {
            Log::error('Featured products error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'error' => 'Internal Server Error',
                'message' => 'Failed to fetch featured products.'
            ], 500);
        }
    }

    /**
     * Get single product details
     *
     * @group Public API
     *
     * @urlParam id integer required The product ID. Example: 1
     * @queryParam language string The language code for translations. Example: uz
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "sku": "PROD001",
     *     "price": 150000,
     *     "stock_quantity": 10,
     *     "translations": [
     *       {
     *         "language": "uz",
     *         "name": "Product Name",
     *         "description": "Product description",
     *         "slug": "product-name"
     *       }
     *     ],
     *     "images": [
     *       {
     *         "id": 1,
     *         "file_path": "/images/products/product1.jpg",
     *         "is_primary": true
     *       }
     *     ],
     *     "category": {
     *       "id": 1,
     *       "name": "Category Name"
     *     }
     *   }
     * }
     *
     * @response 404 {
     *   "error": "Not Found",
     *   "message": "Product not found."
     * }
     */
    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'language' => 'sometimes|string|in:uz,ru,en',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $language = $request->get('language', 'uz');
            $cacheKey = "product_{$id}_{$language}";

            $product = Cache::remember($cacheKey, 300, function () use ($id, $language) {
                return Product::with([
                    'translations' => function($q) use ($language) {
                        $q->where('language', $language);
                    },
                    'images' => function($q) {
                        $q->orderBy('sort_order');
                    },
                    'category.translations' => function($q) use ($language) {
                        $languageId = $this->getLanguageId($language);
                        $q->where('language_id', $languageId);
                    }
                ])->where('is_active', true)->find($id);
            });

            if (!$product) {
                return response()->json([
                    'error' => 'Not Found',
                    'message' => 'Product not found.'
                ], 404);
            }

            return response()->json(['data' => $product]);

        } catch (\Exception $e) {
            Log::error('Product show error', [
                'product_id' => $id,
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'error' => 'Internal Server Error',
                'message' => 'Failed to fetch product details.'
            ], 500);
        }
    }

    /**
     * Get products by category
     *
     * @group Public API
     *
     * @urlParam categoryId integer required The category ID. Example: 1
     * @queryParam language string The language code for translations. Example: uz
     * @queryParam page integer Page number for pagination. Example: 1
     * @queryParam per_page integer Items per page (max 50). Example: 12
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Product Name",
     *       "price": 150000,
     *       "image": "/images/products/product1.jpg"
     *     }
     *   ],
     *   "meta": {
     *     "current_page": 1,
     *     "per_page": 12,
     *     "total": 25,
     *     "last_page": 3
     *   }
     * }
     */
    public function byCategory(Request $request, int $categoryId): JsonResponse
    {
        try {
            $validator = Validator::make(array_merge($request->all(), ['categoryId' => $categoryId]), [
                'categoryId' => 'required|integer|exists:categories,id',
                'language' => 'sometimes|string|in:uz,ru,en',
                'page' => 'sometimes|integer|min:1',
                'per_page' => 'sometimes|integer|min:1|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $language = $request->get('language', 'uz');
            $perPage = min($request->get('per_page', 12), 50);

            $cacheKey = "category_products_{$categoryId}_{$language}_" . md5(serialize($request->all()));

            $products = Cache::remember($cacheKey, 300, function () use ($categoryId, $language, $perPage) {
                return Product::with([
                    'translations' => function($q) use ($language) {
                        $q->where('language', $language);
                    },
                    'images' => function($q) {
                        $q->where('is_primary', true)->limit(1);
                    }
                ])
                ->where('category_id', $categoryId)
                ->where('is_active', true)
                ->paginate($perPage);
            });

            return response()->json([
                'data' => $products->items(),
                'meta' => [
                    'current_page' => $products->currentPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'last_page' => $products->lastPage(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Products by category error', [
                'category_id' => $categoryId,
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'error' => 'Internal Server Error',
                'message' => 'Failed to fetch products by category.'
            ], 500);
        }
    }

    /**
     * Get product by slug
     *
     * @group Public API
     *
     * @urlParam slug string required The product slug. Example: smartphone-samsung
     * @queryParam language string The language code for translations. Example: uz
     */
    public function showBySlug(Request $request, string $slug): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'language' => 'sometimes|string|in:uz,ru,en',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $language = $request->get('language', 'uz');
            $cacheKey = "product_slug_{$slug}_{$language}";

            $product = Cache::remember($cacheKey, 300, function () use ($slug, $language) {
                return Product::with([
                    'translations' => function($q) use ($language) {
                        $q->where('language', $language);
                    },
                    'images' => function($q) {
                        $q->orderBy('sort_order');
                    },
                    'category.translations' => function($q) use ($language) {
                        $languageId = $this->getLanguageId($language);
                        $q->where('language_id', $languageId);
                    }
                ])
                ->whereHas('translations', function($q) use ($slug, $language) {
                    $q->where('slug', $slug)->where('language', $language);
                })
                ->where('is_active', true)
                ->first();
            });

            if (!$product) {
                return response()->json([
                    'error' => 'Not Found',
                    'message' => 'Product not found.'
                ], 404);
            }

            return response()->json(['data' => $product]);

        } catch (\Exception $e) {
            Log::error('Product by slug error', [
                'slug' => $slug,
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'error' => 'Internal Server Error',
                'message' => 'Failed to fetch product by slug.'
            ], 500);
        }
    }

    /**
     * Get related products
     *
     * @group Public API
     *
     * @urlParam id integer required The product ID. Example: 1
     * @queryParam language string The language code for translations. Example: uz
     * @queryParam limit integer Number of related products (max 10). Example: 4
     */
    public function related(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make(array_merge($request->all(), ['id' => $id]), [
                'id' => 'required|integer|exists:products,id',
                'language' => 'sometimes|string|in:uz,ru,en',
                'limit' => 'sometimes|integer|min:1|max:10',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $language = $request->get('language', 'uz');
            $limit = min($request->get('limit', 4), 10);

            $cacheKey = "related_products_{$id}_{$language}_{$limit}";

            $relatedProducts = Cache::remember($cacheKey, 600, function () use ($id, $language, $limit) {
                $product = Product::find($id);
                if (!$product) {
                    return collect([]);
                }

                return Product::with([
                    'translations' => function($q) use ($language) {
                        $q->where('language', $language);
                    },
                    'images' => function($q) {
                        $q->where('is_primary', true)->limit(1);
                    }
                ])
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $id)
                ->where('is_active', true)
                ->limit($limit)
                ->get()
                ->map(function ($product) {
                    $translation = $product->translations->first();
                    $image = $product->images->first();

                    return [
                        'id' => $product->id,
                        'name' => $translation ? $translation->name : null,
                        'price' => $product->price,
                        'image' => $image ? $image->file_path : null,
                        'slug' => $translation ? $translation->slug : null,
                    ];
                });
            });

            return response()->json(['data' => $relatedProducts]);

        } catch (\Exception $e) {
            Log::error('Related products error', [
                'product_id' => $id,
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'error' => 'Internal Server Error',
                'message' => 'Failed to fetch related products.'
            ], 500);
        }
    }

    /**
     * Get language ID by code
     *
     * @param string $languageCode
     * @return int
     */
    private function getLanguageId(string $languageCode): int
    {
        $languageMap = [
            'uz' => 1,
            'ru' => 2,
            'en' => 3,
        ];

        return $languageMap[$languageCode] ?? 1;
    }
}
