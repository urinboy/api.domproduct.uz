<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShoppingCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'total_amount',
        'items_count',
        'coupon_data',
        'expires_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'coupon_data' => 'array',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the cart.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the cart items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }

    /**
     * Get active cart items.
     */
    public function activeItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'cart_id')
            ->whereHas('product', function ($query) {
                $query->where('is_active', true)
                      ->where('stock_status', '!=', 'out_of_stock');
            });
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($cart) {
            $cart->updateCartTotals();
        });
    }

    /**
     * Add product to cart
     */
    public function addProduct($product, $quantity = 1, $options = [])
    {
        $existingItem = $this->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            return $this->updateItemQuantity($existingItem, $existingItem->quantity + $quantity);
        }

        $unitPrice = $product->getEffectivePrice();

        $cartItem = $this->items()->create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $unitPrice * $quantity,
            'product_name' => $product->getTranslation('uz')->name ?? ($product->getTranslation()->name ?? $product->name),
            'product_sku' => $product->sku,
            'product_image' => $product->getPrimaryImageUrl('small'),
            'product_options' => $options,
        ]);

        $this->updateCartTotals();

        return $cartItem;
    }

    /**
     * Update item quantity
     */
    public function updateItemQuantity($cartItem, $newQuantity)
    {
        if ($newQuantity <= 0) {
            return $this->removeItem($cartItem);
        }

        $cartItem->update([
            'quantity' => $newQuantity,
            'total_price' => $cartItem->unit_price * $newQuantity,
        ]);

        $this->updateCartTotals();

        return $cartItem;
    }

    /**
     * Remove item from cart
     */
    public function removeItem($cartItem)
    {
        $cartItem->delete();
        $this->updateCartTotals();

        return true;
    }

    /**
     * Clear all items from cart
     */
    public function clearCart()
    {
        $this->items()->delete();
        $this->updateCartTotals();

        return true;
    }

    /**
     * Update cart totals
     */
    public function updateCartTotals()
    {
        $items = $this->activeItems()->get();

        $this->update([
            'total_amount' => $items->sum('total_price'),
            'items_count' => $items->sum('quantity'),
        ]);
    }

    /**
     * Get cart total with discounts
     */
    public function getFinalTotal()
    {
        $subtotal = $this->total_amount;

        // Apply coupon discount if exists
        if ($this->coupon_data && isset($this->coupon_data['discount_amount'])) {
            $subtotal -= $this->coupon_data['discount_amount'];
        }

        return max(0, $subtotal);
    }

    /**
     * Check if cart is expired
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if cart is empty
     */
    public function isEmpty()
    {
        return $this->items_count == 0;
    }

    /**
     * Get cart for specific user or session
     */
    public static function getCartForUser($userId = null, $sessionId = null)
    {
        $query = static::query();

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        } else {
            return null;
        }

        return $query->with(['items.product.translations'])->first();
    }

    /**
     * Create or get cart for user/session
     */
    public static function createOrGetCart($userId = null, $sessionId = null)
    {
        $cart = static::getCartForUser($userId, $sessionId);

        if (!$cart) {
            $cart = static::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'expires_at' => now()->addDays(7), // Cart expires in 7 days
            ]);
        }

        return $cart;
    }

    /**
     * Scope for active carts
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope for expired carts
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }
}
