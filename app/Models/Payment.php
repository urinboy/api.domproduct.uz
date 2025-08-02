<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'transaction_id',
        'payment_method',
        'amount',
        'currency',
        'status',
        'payment_data',
        'notes',
        'confirmed_at',
        'failed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_data' => 'array',
        'confirmed_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    // To'lov usullari
    const METHOD_CASH = 'cash';
    const METHOD_CARD = 'card';
    const METHOD_BANK_TRANSFER = 'bank_transfer';

    // To'lov holatlari
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';

    /**
     * Get the user that owns the payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order that this payment belongs to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope for successful payments
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Check if payment is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if payment failed
     */
    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Get payment method name in Uzbek
     */
    public function getMethodNameAttribute(): string
    {
        $methods = [
            self::METHOD_CASH => 'Naqd to\'lov',
            self::METHOD_CARD => 'Plastik karta',
            self::METHOD_BANK_TRANSFER => 'Bank o\'tkazmasi',
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Get status name in Uzbek
     */
    public function getStatusNameAttribute(): string
    {
        $statuses = [
            self::STATUS_PENDING => 'Kutilmoqda',
            self::STATUS_PROCESSING => 'Qayta ishlanmoqda',
            self::STATUS_COMPLETED => 'Yakunlangan',
            self::STATUS_FAILED => 'Muvaffaqiyatsiz',
            self::STATUS_CANCELLED => 'Bekor qilingan',
            self::STATUS_REFUNDED => 'Qaytarilgan',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 0, '.', ' ') . ' ' . $this->currency;
    }
}
