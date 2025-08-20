<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    /**
     * Get product listing with filters and sorting
     *
     * @param array $params
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getListing(array $params = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Product::where('is_active', true);

        // Category filter (include children)
        if (!empty($params['category'])) {
            $categoryId = (int) $params['category'];
            $category = Category::find($categoryId);
            if ($category) {
                $categoryIds = [$categoryId];
                $childIds = Category::where('parent_id', $categoryId)->pluck('id')->toArray();
                $categoryIds = array_merge($categoryIds, $childIds);
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Price filters
        if (isset($params['price_min'])) {
            $query->where('price', '>=', (float) $params['price_min']);
        }
        if (isset($params['price_max'])) {
            $query->where('price', '<=', (float) $params['price_max']);
        }

        // Search (basic)
        if (!empty($params['search'])) {
            $term = trim($params['search']);
            $query->where(function ($q) use ($term) {
                $q->where('sku', 'like', "%{$term}%")
                  ->orWhere('name', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%");
            });
        }

        // Sorting
        $sort = $params['sort'] ?? 'newest';
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query->paginate($perPage);
    }

    /**
     * Find product by id (only active)
     */
    public function find(int $id): Product
    {
        return Product::where('is_active', true)->findOrFail($id);
    }
}
