<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
        'product_name',
        'product_sku',
        'product_image',
        'product_options',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'product_options' => 'array',
        'quantity' => 'integer',
    ];

    /**
     * Get the cart that owns the item.
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(ShoppingCart::class, 'cart_id');
    }

    /**
     * Get the product for this cart item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($cartItem) {
            $cartItem->cart->updateCartTotals();
        });

        static::updated(function ($cartItem) {
            $cartItem->cart->updateCartTotals();
        });

        static::deleted(function ($cartItem) {
            $cartItem->cart->updateCartTotals();
        });
    }

    /**
     * Update the total price when quantity or unit price changes
     */
    public function updateTotalPrice()
    {
        $this->total_price = $this->unit_price * $this->quantity;
        $this->save();
    }

    /**
     * Get formatted unit price
     */
    public function getFormattedUnitPriceAttribute()
    {
        return number_format($this->unit_price, 2) . ' UZS';
    }

    /**
     * Get formatted total price
     */
    public function getFormattedTotalPriceAttribute()
    {
        return number_format($this->total_price, 2) . ' UZS';
    }

    /**
     * Check if the product is still available
     */
    public function isProductAvailable()
    {
        return $this->product &&
               $this->product->is_active &&
               $this->product->stock_status !== 'out_of_stock';
    }

    /**
     * Check if the requested quantity is available
     */
    public function isQuantityAvailable()
    {
        if (!$this->product) {
            return false;
        }

        if ($this->product->track_inventory) {
            return $this->product->stock_quantity >= $this->quantity;
        }

        return true; // If not tracking inventory, assume available
    }

    /**
     * Get the maximum available quantity for this product
     */
    public function getMaxAvailableQuantity()
    {
        if (!$this->product || !$this->product->track_inventory) {
            return 999; // Default max if not tracking
        }

        return $this->product->stock_quantity;
    }

    /**
     * Get product thumbnail image
     */
    public function getProductThumbnail()
    {
        if ($this->product_image) {
            return $this->product_image;
        }

        return $this->product ? $this->product->getPrimaryImageUrl('small') : null;
    }

    /**
     * Get current product price (for comparison with saved price)
     */
    public function getCurrentProductPrice()
    {
        return $this->product ? $this->product->getEffectivePrice() : $this->unit_price;
    }

    /**
     * Check if price has changed since adding to cart
     */
    public function hasPriceChanged()
    {
        $currentPrice = $this->getCurrentProductPrice();
        return abs($currentPrice - $this->unit_price) > 0.01;
    }

    /**
     * Get price difference if changed
     */
    public function getPriceDifference()
    {
        if (!$this->hasPriceChanged()) {
            return 0;
        }

        return $this->getCurrentProductPrice() - $this->unit_price;
    }

    /**
     * Update price to current product price
     */
    public function updateToCurrentPrice()
    {
        $currentPrice = $this->getCurrentProductPrice();

        $this->update([
            'unit_price' => $currentPrice,
            'total_price' => $currentPrice * $this->quantity,
        ]);

        return $this;
    }

    /**
     * Convert to order item array
     */
    public function toOrderItemArray()
    {
        return [
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'total_price' => $this->total_price,
            'product_name' => $this->product_name,
            'product_sku' => $this->product_sku,
            'product_image' => $this->product_image,
            'product_options' => $this->product_options,
        ];
    }

    /**
     * Scope for items with available products
     */
    public function scopeAvailable($query)
    {
        return $query->whereHas('product', function ($q) {
            $q->where('is_active', true)
              ->where('stock_status', '!=', 'out_of_stock');
        });
    }

    /**
     * Scope for items with unavailable products
     */
    public function scopeUnavailable($query)
    {
        return $query->whereDoesntHave('product')
                    ->orWhereHas('product', function ($q) {
                        $q->where('is_active', false)
                          ->orWhere('stock_status', 'out_of_stock');
                    });
    }
}
