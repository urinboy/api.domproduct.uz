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
        'stock_quantity',
        'min_stock_level',
        'track_stock',
        'stock_status',
        'weight',
        'length',
        'width',
        'height',
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
}
