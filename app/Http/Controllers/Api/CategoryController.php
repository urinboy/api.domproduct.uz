<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Get all categories (hierarchical structure)
     */
    public function index(Request $request)
    {
        try {
            $query = Category::active()
                ->with(['translations.language', 'children.translations.language'])
                ->withCount('children')
                ->ordered();

            // Filter by parent ID if specified
            if ($request->has('parent_id')) {
                if ($request->parent_id === 'null' || $request->parent_id === '') {
                    $query->parents(); // Only root categories
                } else {
                    $query->where('parent_id', $request->parent_id);
                }
            } else {
                // Default: only root categories
                $query->parents();
            }

            // Search by name
            if ($request->has('search')) {
                $search = $request->search;
                $query->whereHas('translations', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
                });
            }

            // Get results
            $categories = $query->get();

            return response()->json([
                'success' => true,
                'message' => 'Categories retrieved successfully',
                'data' => CategoryResource::collection($categories)
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
     * Get category tree (all levels)
     */
    public function tree(Request $request)
    {
        try {
            $categories = Category::active()
                ->with(['translations.language', 'children' => function ($query) {
                    $query->active()
                        ->with(['translations.language', 'children.translations.language'])
                        ->withCount('children')
                        ->ordered();
                }])
                ->withCount('children')
                ->parents()
                ->ordered()
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Category tree retrieved successfully',
                'data' => CategoryResource::collection($categories)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve category tree',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single category by ID
     */
    public function show(Request $request, $id)
    {
        try {
            $category = Category::active()
                ->with(['translations.language', 'parent.translations.language', 'children.translations.language'])
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
     * Get category by slug
     */
    public function showBySlug(Request $request, $slug)
    {
        try {
            $languageCode = $request->get('lang', 'uz');

            $category = Category::active()
                ->with(['translations.language', 'parent.translations.language', 'children.translations.language'])
                ->withCount('children')
                ->whereHas('translations', function ($query) use ($slug, $languageCode) {
                    $query->where('slug', $slug)
                          ->whereHas('language', function ($q) use ($languageCode) {
                              $q->where('code', $languageCode);
                          });
                })
                ->firstOrFail();

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
     * Get category breadcrumbs
     */
    public function breadcrumbs(Request $request, $id)
    {
        try {
            $category = Category::active()
                ->with('translations.language')
                ->findOrFail($id);

            $breadcrumbs = [];
            $current = $category;

            // Build breadcrumbs by traversing up the parent chain
            while ($current) {
                array_unshift($breadcrumbs, new CategoryResource($current));
                $current = $current->parent;
            }

            return response()->json([
                'success' => true,
                'message' => 'Category breadcrumbs retrieved successfully',
                'data' => $breadcrumbs
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve breadcrumbs',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
