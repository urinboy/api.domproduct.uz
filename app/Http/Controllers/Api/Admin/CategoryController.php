<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Language;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Get all categories with admin data
     */
    public function index(Request $request)
    {
        try {
            $query = Category::with(['translations.language', 'parent.translations.language'])
                ->withCount('children')
                ->ordered();

            // Filter by status
            if ($request->has('status')) {
                $query->where('is_active', $request->status === 'active');
            }

            // Filter by parent
            if ($request->has('parent_id')) {
                if ($request->parent_id === 'null') {
                    $query->parents();
                } else {
                    $query->where('parent_id', $request->parent_id);
                }
            }

            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->whereHas('translations', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
                });
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'sort_order');
            $sortOrder = $request->get('sort_order', 'asc');

            if ($sortBy === 'name') {
                $query->join('category_translations', 'categories.id', '=', 'category_translations.category_id')
                      ->join('languages', 'category_translations.language_id', '=', 'languages.id')
                      ->where('languages.code', 'uz')
                      ->orderBy('category_translations.name', $sortOrder)
                      ->select('categories.*');
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $categories = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Categories retrieved successfully',
                'data' => new CategoryCollection($categories)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store new category
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'nullable|integer|min:0',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',

            // Translations (at least one required)
            'translations' => 'required|array|min:1',
            'translations.*.language_code' => 'required|string|exists:languages,code',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
            'translations.*.slug' => 'nullable|string|max:255',
            'translations.*.meta_title' => 'nullable|string|max:255',
            'translations.*.meta_description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create category
            $category = Category::create([
                'parent_id' => $request->parent_id,
                'sort_order' => $request->sort_order ?? 0,
                'icon' => $request->icon,
                'is_active' => $request->is_active ?? true,
            ]);

            // Create translations
            foreach ($request->translations as $translationData) {
                $language = Language::where('code', $translationData['language_code'])->first();

                if ($language) {
                    $slug = $translationData['slug'] ?? Str::slug($translationData['name']);

                    // Ensure slug uniqueness within the language
                    $originalSlug = $slug;
                    $counter = 1;
                    while (CategoryTranslation::where('language_id', $language->id)
                                             ->where('slug', $slug)
                                             ->exists()) {
                        $slug = $originalSlug . '-' . $counter;
                        $counter++;
                    }

                    CategoryTranslation::create([
                        'category_id' => $category->id,
                        'language_id' => $language->id,
                        'name' => $translationData['name'],
                        'description' => $translationData['description'] ?? null,
                        'slug' => $slug,
                        'meta_title' => $translationData['meta_title'] ?? null,
                        'meta_description' => $translationData['meta_description'] ?? null,
                    ]);
                }
            }

            DB::commit();

            // Load relationships for response
            $category->load(['translations.language', 'parent.translations.language']);

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'data' => new CategoryResource($category)
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show category
     */
    public function show($id)
    {
        try {
            $category = Category::with(['translations.language', 'parent.translations.language', 'children.translations.language'])
                ->withCount('children')
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Category retrieved successfully',
                'data' => new CategoryResource($category)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update category
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'nullable|integer|min:0',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',

            // Translations
            'translations' => 'sometimes|array|min:1',
            'translations.*.language_code' => 'required|string|exists:languages,code',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
            'translations.*.slug' => 'nullable|string|max:255',
            'translations.*.meta_title' => 'nullable|string|max:255',
            'translations.*.meta_description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $category = Category::findOrFail($id);

            // Prevent circular reference
            if ($request->parent_id && $this->wouldCreateCircularReference($category->id, $request->parent_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot set parent: would create circular reference',
                    'error_code' => 'CIRCULAR_REFERENCE'
                ], 400);
            }

            DB::beginTransaction();

            // Update category basic fields
            $category->update($request->only(['parent_id', 'sort_order', 'icon', 'is_active']));

            // Update translations if provided
            if ($request->has('translations')) {
                foreach ($request->translations as $translationData) {
                    $language = Language::where('code', $translationData['language_code'])->first();

                    if ($language) {
                        $slug = $translationData['slug'] ?? Str::slug($translationData['name']);

                        // Ensure slug uniqueness (excluding current category)
                        $originalSlug = $slug;
                        $counter = 1;
                        while (CategoryTranslation::where('language_id', $language->id)
                                                 ->where('slug', $slug)
                                                 ->where('category_id', '!=', $category->id)
                                                 ->exists()) {
                            $slug = $originalSlug . '-' . $counter;
                            $counter++;
                        }

                        CategoryTranslation::updateOrCreate(
                            [
                                'category_id' => $category->id,
                                'language_id' => $language->id,
                            ],
                            [
                                'name' => $translationData['name'],
                                'description' => $translationData['description'] ?? null,
                                'slug' => $slug,
                                'meta_title' => $translationData['meta_title'] ?? null,
                                'meta_description' => $translationData['meta_description'] ?? null,
                            ]
                        );
                    }
                }
            }

            DB::commit();

            // Load relationships for response
            $category->load(['translations.language', 'parent.translations.language']);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => new CategoryResource($category)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete category
     */
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);

            // Check if category has children
            if ($category->children()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with subcategories',
                    'error_code' => 'HAS_CHILDREN'
                ], 400);
            }

            // TODO: Check if category has products
            // if ($category->products()->exists()) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Cannot delete category with products',
            //         'error_code' => 'HAS_PRODUCTS'
            //     ], 400);
            // }

            DB::beginTransaction();

            // Delete translations
            $category->translations()->delete();

            // Delete category image if exists
            if ($category->image_path) {
                $uploadService = app(FileUploadService::class);
                $uploadService->deleteImage($category->image_path);
            }

            // Delete category
            $category->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload category image
     */
    public function uploadImage(Request $request, $id, FileUploadService $uploadService)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240' // 10MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $category = Category::findOrFail($id);

            // Delete old image if exists
            if ($category->image_path) {
                $uploadService->deleteImage($category->image_path);
            }

            // Upload new image
            $imageData = $uploadService->uploadCategoryImage($request->file('image'), $category->id);

            // Update category image fields
            $category->updateImage($imageData);

            return response()->json([
                'success' => true,
                'message' => 'Category image uploaded successfully',
                'data' => [
                    'image' => $category->getImageUrl('medium'),
                    'image_sizes' => $category->getImageSizes()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Image upload failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete category image
     */
    public function deleteImage($id, FileUploadService $uploadService)
    {
        try {
            $category = Category::findOrFail($id);

            if ($category->image_path) {
                $uploadService->deleteImage($category->image_path);
            }

            // Clear image fields
            $category->update([
                'image' => null,
                'image_original' => null,
                'image_thumbnail' => null,
                'image_small' => null,
                'image_medium' => null,
                'image_large' => null,
                'image_path' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Category image deleted successfully',
                'data' => [
                    'image' => $category->getDefaultImage()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Image deletion failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get category statistics
     */
    public function statistics()
    {
        try {
            $stats = [
                'total_categories' => Category::count(),
                'active_categories' => Category::active()->count(),
                'inactive_categories' => Category::where('is_active', false)->count(),
                'root_categories' => Category::parents()->count(),
                'categories_with_children' => Category::has('children')->count(),
                'categories_with_images' => Category::whereNotNull('image')->count(),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Category statistics retrieved successfully',
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if setting parent would create circular reference
     */
    private function wouldCreateCircularReference($categoryId, $parentId)
    {
        if ($categoryId == $parentId) {
            return true;
        }

        $parent = Category::find($parentId);
        while ($parent) {
            if ($parent->id == $categoryId) {
                return true;
            }
            $parent = $parent->parent;
        }

        return false;
    }
}
