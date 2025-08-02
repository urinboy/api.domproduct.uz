<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
        'product_name',
        'product_sku',
        'product_image',
        'product_options',
        'product_attributes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'quantity' => 'integer',
        'product_options' => 'array',
        'product_attributes' => 'array',
    ];

    /**
     * Get the order that owns the item.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product for this order item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get formatted unit price
     */
    public function getFormattedUnitPriceAttribute()
    {
        return number_format($this->unit_price, 2) . ' ' . ($this->order->currency ?? 'UZS');
    }

    /**
     * Get formatted total price
     */
    public function getFormattedTotalPriceAttribute()
    {
        return number_format($this->total_price, 2) . ' ' . ($this->order->currency ?? 'UZS');
    }

    /**
     * Get product image URL
     */
    public function getProductImageUrlAttribute()
    {
        if ($this->product_image) {
            // If it's already a full URL, return as is
            if (filter_var($this->product_image, FILTER_VALIDATE_URL)) {
                return $this->product_image;
            }

            // If it's a relative path, make it absolute
            return url('storage/' . $this->product_image);
        }

        // Fallback to current product image if available
        return $this->product ? $this->product->getPrimaryImageUrl('small') : null;
    }

    /**
     * Get current product price for comparison
     */
    public function getCurrentProductPrice()
    {
        return $this->product ? $this->product->getEffectivePrice() : null;
    }

    /**
     * Check if product price has changed since order
     */
    public function hasPriceChanged()
    {
        $currentPrice = $this->getCurrentProductPrice();

        if (!$currentPrice) {
            return false;
        }

        return abs($currentPrice - $this->unit_price) > 0.01;
    }

    /**
     * Get price difference
     */
    public function getPriceDifference()
    {
        $currentPrice = $this->getCurrentProductPrice();

        if (!$currentPrice) {
            return 0;
        }

        return $currentPrice - $this->unit_price;
    }

    /**
     * Check if product is still available
     */
    public function isProductAvailable()
    {
        return $this->product &&
               $this->product->is_active &&
               $this->product->stock_status !== 'out_of_stock';
    }

    /**
     * Get product details snapshot
     */
    public function getProductSnapshotAttribute()
    {
        return [
            'name' => $this->product_name,
            'sku' => $this->product_sku,
            'image' => $this->product_image_url,
            'options' => $this->product_options,
            'attributes' => $this->product_attributes,
            'unit_price' => $this->unit_price,
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
        ];
    }

    /**
     * Get item summary for display
     */
    public function getSummaryAttribute()
    {
        return [
            'product_name' => $this->product_name,
            'sku' => $this->product_sku,
            'quantity' => $this->quantity,
            'unit_price' => $this->formatted_unit_price,
            'total_price' => $this->formatted_total_price,
            'image' => $this->product_image_url,
            'options' => $this->product_options,
        ];
    }

    /**
     * Calculate line total including any modifications
     */
    public function calculateLineTotal()
    {
        return $this->unit_price * $this->quantity;
    }

    /**
     * Update total price based on unit price and quantity
     */
    public function updateTotal()
    {
        $this->total_price = $this->calculateLineTotal();
        $this->save();

        return $this;
    }

    /**
     * Create order item from cart item
     */
    public static function createFromCartItem(CartItem $cartItem, $orderId)
    {
        return static::create([
            'order_id' => $orderId,
            'product_id' => $cartItem->product_id,
            'quantity' => $cartItem->quantity,
            'unit_price' => $cartItem->unit_price,
            'total_price' => $cartItem->total_price,
            'product_name' => $cartItem->product_name,
            'product_sku' => $cartItem->product_sku,
            'product_image' => $cartItem->product_image,
            'product_options' => $cartItem->product_options,
            'product_attributes' => $cartItem->product ? [
                'category' => $cartItem->product->category->name ?? null,
                'brand' => $cartItem->product->brand ?? null,
                'weight' => $cartItem->product->weight ?? null,
                'dimensions' => $cartItem->product->dimensions ?? null,
            ] : [],
        ]);
    }

    /**
     * Check if item can be returned/refunded
     */
    public function canBeReturned()
    {
        // Check if order allows returns
        if (!$this->order->canBeRefunded()) {
            return false;
        }

        // Add any product-specific return rules here
        // For example, digital products, perishables, etc.

        return true;
    }

    /**
     * Get return eligibility info
     */
    public function getReturnEligibilityAttribute()
    {
        return [
            'eligible' => $this->canBeReturned(),
            'reason' => $this->canBeReturned() ? null : 'Order not eligible for returns',
            'deadline' => $this->order->delivered_at ?
                $this->order->delivered_at->addDays(30) : null, // 30 days return policy
        ];
    }

    /**
     * Scope for items with specific product
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope for items in specific orders
     */
    public function scopeInOrders($query, array $orderIds)
    {
        return $query->whereIn('order_id', $orderIds);
    }

    /**
     * Scope for items with price changes
     */
    public function scopeWithPriceChanges($query)
    {
        return $query->whereHas('product', function ($q) {
            $q->whereRaw('products.price != order_items.unit_price');
        });
    }
}
