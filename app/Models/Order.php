<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'guest_email',
        'guest_phone',
        'status',
        'payment_status',
        'payment_method',
        'delivery_method',
        'currency',
        'subtotal',
        'discount_amount',
        'delivery_fee',
        'tax_amount',
        'total_amount',
        'billing_address',
        'delivery_address',
        'delivery_date',
        'delivery_time_slot',
        'special_instructions',
        'payment_data',
        'tracking_number',
        'order_notes',
        'processed_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'refunded_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'billing_address' => 'array',
        'delivery_address' => 'array',
        'payment_data' => 'array',
        'delivery_date' => 'date',
        'processed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';

    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_FAILED = 'failed';
    const PAYMENT_STATUS_REFUNDED = 'refunded';
    const PAYMENT_STATUS_PARTIAL_REFUND = 'partial_refund';

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the order status history.
     */
    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'desc');
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = static::generateOrderNumber();
            }
        });

        static::created(function ($order) {
            $order->addStatusHistory($order->status, 'Order created');
        });
    }

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber()
    {
        do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (static::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Create order from shopping cart
     */
    public static function createFromCart(ShoppingCart $cart, array $orderData = [])
    {
        $order = static::create(array_merge([
            'user_id' => $cart->user_id,
            'status' => static::STATUS_PENDING,
            'payment_status' => static::PAYMENT_STATUS_PENDING,
            'currency' => 'UZS',
            'subtotal' => $cart->total_amount,
            'total_amount' => $cart->getFinalTotal(),
            'discount_amount' => $cart->coupon_data['discount_amount'] ?? 0,
        ], $orderData));

        // Create order items from cart items
        foreach ($cart->activeItems as $cartItem) {
            $order->items()->create($cartItem->toOrderItemArray());
        }

        return $order;
    }

    /**
     * Add status history record
     */
    public function addStatusHistory($status, $notes = null, $notifyCustomer = false)
    {
        return $this->statusHistory()->create([
            'from_status' => $this->getOriginal('status'),
            'to_status' => $status,
            'notes' => $notes,
            'customer_notified' => $notifyCustomer,
            'metadata' => [
                'ip_address' => app('request')->ip(),
                'user_agent' => app('request')->userAgent(),
            ],
        ]);
    }

    /**
     * Update order status
     */
    public function updateStatus($newStatus, $notes = null, $notifyCustomer = false)
    {
        $oldStatus = $this->status;

        $this->update(['status' => $newStatus]);

        // Add timestamp for specific statuses
        switch ($newStatus) {
            case static::STATUS_PROCESSING:
                $this->update(['processed_at' => now()]);
                break;
            case static::STATUS_SHIPPED:
                $this->update(['shipped_at' => now()]);
                break;
            case static::STATUS_DELIVERED:
                $this->update(['delivered_at' => now()]);
                break;
            case static::STATUS_CANCELLED:
                $this->update(['cancelled_at' => now()]);
                break;
            case static::STATUS_REFUNDED:
                $this->update(['refunded_at' => now()]);
                break;
        }

        $this->addStatusHistory(
            $newStatus,
            $notes ?? "Status changed from {$oldStatus} to {$newStatus}",
            $notifyCustomer
        );

        return $this;
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus($paymentStatus, $paymentData = [])
    {
        $this->update([
            'payment_status' => $paymentStatus,
            'payment_data' => array_merge($this->payment_data ?? [], $paymentData),
        ]);

        $this->addStatusHistory(
            $this->status,
            "Payment status updated to {$paymentStatus}"
        );

        return $this;
    }

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->status, [
            static::STATUS_PENDING,
            static::STATUS_CONFIRMED,
        ]);
    }

    /**
     * Check if order can be refunded
     */
    public function canBeRefunded()
    {
        return $this->payment_status === static::PAYMENT_STATUS_PAID &&
               in_array($this->status, [
                   static::STATUS_DELIVERED,
                   static::STATUS_SHIPPED,
               ]);
    }

    /**
     * Cancel order
     */
    public function cancel($reason = null)
    {
        if (!$this->canBeCancelled()) {
            throw new \Exception('Order cannot be cancelled in current status');
        }

        return $this->updateStatus(static::STATUS_CANCELLED, $reason, true);
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_amount, 2) . ' ' . $this->currency;
    }

    /**
     * Get customer info (user or guest)
     */
    public function getCustomerInfoAttribute()
    {
        if ($this->user_id) {
            return [
                'type' => 'registered',
                'name' => $this->user->first_name . ' ' . $this->user->last_name,
                'email' => $this->user->email,
                'phone' => $this->user->phone,
            ];
        }

        return [
            'type' => 'guest',
            'email' => $this->guest_email,
            'phone' => $this->guest_phone,
        ];
    }

    /**
     * Get order summary
     */
    public function getSummaryAttribute()
    {
        return [
            'order_number' => $this->order_number,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'total_amount' => $this->total_amount,
            'currency' => $this->currency,
            'items_count' => $this->items()->sum('quantity'),
            'created_at' => $this->created_at,
        ];
    }

    /**
     * Scope for orders by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for paid orders
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', static::PAYMENT_STATUS_PAID);
    }

    /**
     * Scope for pending orders
     */
    public function scopePending($query)
    {
        return $query->where('status', static::STATUS_PENDING);
    }

    /**
     * Scope for recent orders
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for orders within date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}
