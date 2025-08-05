<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'barcode',
        'category_id',
        'price',
        'sale_price',
        'cost_price',
        'unit_type',
        'unit_value',
        'stock_quantity',
        'min_stock_level',
        'track_stock',
        'stock_status',
        'weight',
        'weight_unit',
        'length',
        'width',
        'height',
        'dimension_unit',
        'is_active',
        'is_featured',
        'is_digital',
        'sort_order',
        'view_count',
        'rating',
        'review_count',
        'published_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'unit_value' => 'decimal:3',
        'weight' => 'decimal:3',
        'length' => 'decimal:3',
        'width' => 'decimal:3',
        'height' => 'decimal:3',
        'rating' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_digital' => 'boolean',
        'track_stock' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the translations for the product.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ProductTranslation::class);
    }

    /**
     * Get the users who favorited this product.
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(UserFavorite::class);
    }

    /**
     * Get the users who favorited this product (many-to-many).
     */
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_favorites', 'product_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Check if product is favorited by user
     */
    public function isFavoritedBy($userId): bool
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }

    /**
     * Get favorites count
     */
    public function getFavoritesCount(): int
    {
        return $this->favorites()->count();
    }

    /**
     * Get translation for specific language (optimized)
     */
    public function translationForLanguage($language = 'uz')
    {
        return $this->hasOne(ProductTranslation::class)
            ->where('language', $language);
    }

    /**
     * Get the images for the product.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * Get limited images for listing (optimized)
     */
    public function limitedImages(): HasMany
    {
        return $this->hasMany(ProductImage::class)
            ->orderBy('sort_order')
            ->limit(3);
    }

    /**
     * Get the primary image for the product.
     */
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get translation for specific language
     */
    public function getTranslation($language = 'uz')
    {
        $translation = $this->translations()->where('language', $language)->first();

        if (!$translation && $language !== 'uz') {
            $translation = $this->translations()->where('language', 'uz')->first();
        }

        return $translation;
    }

    /**
     * Get the effective price (sale price if available, otherwise regular price)
     */
    public function getEffectivePrice()
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Check if product is on sale
     */
    public function isOnSale(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentage(): float
    {
        if (!$this->isOnSale()) {
            return 0;
        }

        return round((($this->price - $this->sale_price) / $this->price) * 100, 2);
    }

    /**
     * Check if product is in stock
     */
    public function isInStock(): bool
    {
        if (!$this->track_stock) {
            return true;
        }

        return $this->stock_quantity > 0 && $this->stock_status === 'in_stock';
    }

    /**
     * Check if stock is low
     */
    public function isLowStock(): bool
    {
        if (!$this->track_stock) {
            return false;
        }

        return $this->stock_quantity <= $this->min_stock_level;
    }

    /**
     * Get primary image URL
     */
    public function getPrimaryImageUrl($size = 'medium')
    {
        $primaryImage = $this->primaryImage;

        if (!$primaryImage) {
            return null;
        }

        return $primaryImage->getImageUrl($size);
    }

    /**
     * Scope for active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured products
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for in stock products
     */
    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->where('track_stock', false)
              ->orWhere(function ($q2) {
                  $q2->where('track_stock', true)
                     ->where('stock_quantity', '>', 0)
                     ->where('stock_status', 'in_stock');
              });
        });
    }

    /**
     * Scope for published products
     */
    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now());
    }

    /**
     * Scope for by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Increment view count
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    /**
     * Get image URL with fallback to default
     */
    public function getImageUrl($size = 'original')
    {
        // If product has image
        if ($this->image && file_exists(storage_path('app/public/products/' . $this->image))) {
            return asset('storage/products/' . $this->image);
        }

        // Return default image
        return asset('images/default-image.png');
    }

    /**
     * Get product name from translations or fallback to name field
     */
    public function getName($locale = null)
    {
        // Simple fallback to direct attributes
        return $this->name ?? $this->sku ?? 'Product #' . ($this->id ?? 'Unknown');
    }

    /**
     * Get product description with locale fallback
     */
    public function getDescription($locale = null)
    {
        // Simple fallback to direct attributes
        return $this->description ?? 'No description available.';
    }

    /**
     * Get available unit types
     */
    public static function getUnitTypes()
    {
        return [
            'piece' => __('admin.unit_piece'), // dona
            'kg' => __('admin.unit_kg'), // kilogram
            'g' => __('admin.unit_g'), // gram
            'liter' => __('admin.unit_liter'), // litr
            'meter' => __('admin.unit_meter'), // metr
            'box' => __('admin.unit_box'), // quti/karobka
            'pack' => __('admin.unit_pack'), // o'ram
            'bottle' => __('admin.unit_bottle'), // shisha
            'can' => __('admin.unit_can'), // banka
            'bag' => __('admin.unit_bag'), // qop/yashik
        ];
    }

    /**
     * Get unit type label
     */
    public function getUnitTypeLabel()
    {
        $units = self::getUnitTypes();
        return $units[$this->unit_type] ?? $this->unit_type;
    }

    /**
     * Get formatted unit display
     */
    public function getFormattedUnit()
    {
        if ($this->unit_value == 1) {
            return $this->getUnitTypeLabel();
        }

        return $this->unit_value . ' ' . $this->getUnitTypeLabel();
    }

    /**
     * Get weight units
     */
    public static function getWeightUnits()
    {
        return [
            'kg' => __('admin.weight_kg'),
            'g' => __('admin.weight_g'),
            'lb' => __('admin.weight_lb'),
        ];
    }

    /**
     * Get dimension units
     */
    public static function getDimensionUnits()
    {
        return [
            'cm' => __('admin.dimension_cm'),
            'mm' => __('admin.dimension_mm'),
            'm' => __('admin.dimension_m'),
            'in' => __('admin.dimension_in'),
        ];
    }

    /**
     * Get formatted weight
     */
    public function getFormattedWeight()
    {
        if (!$this->weight) {
            return null;
        }

        return $this->weight . ' ' . ($this->weight_unit ?? 'kg');
    }

    /**
     * Get formatted dimensions
     */
    public function getFormattedDimensions()
    {
        if (!$this->length && !$this->width && !$this->height) {
            return null;
        }

        $unit = $this->dimension_unit ?? 'cm';
        $dimensions = [];

        if ($this->length) $dimensions[] = $this->length;
        if ($this->width) $dimensions[] = $this->width;
        if ($this->height) $dimensions[] = $this->height;

        return implode(' × ', $dimensions) . ' ' . $unit;
    }

    /**
     * Calculate profit margin
     */
    public function getProfitMargin()
    {
        if (!$this->cost_price || $this->cost_price <= 0) {
            return null;
        }

        $selling_price = $this->sale_price ?? $this->price;
        if (!$selling_price || $selling_price <= 0) {
            return null;
        }

        return (($selling_price - $this->cost_price) / $this->cost_price) * 100;
    }

    /**
     * Calculate profit amount
     */
    public function getProfitAmount()
    {
        if (!$this->cost_price || $this->cost_price <= 0) {
            return null;
        }

        $selling_price = $this->sale_price ?? $this->price;
        if (!$selling_price || $selling_price <= 0) {
            return null;
        }

        return $selling_price - $this->cost_price;
    }
}
