<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'from_status',
        'to_status',
        'notes',
        'metadata',
        'customer_notified',
        'notification_method',
    ];

    protected $casts = [
        'customer_notified' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Get the order that owns this status history.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get status display name
     */
    public function getStatusDisplayAttribute()
    {
        $statusNames = [
            'pending' => __('admin.orders_module.pending'),
            'confirmed' => __('admin.orders_module.confirmed'),
            'processing' => __('admin.orders_module.preparing'),
            'shipped' => __('admin.orders_module.out_for_delivery'),
            'delivered' => __('admin.orders_module.delivered'),
            'cancelled' => __('admin.orders_module.cancelled'),
            'refunded' => __('admin.orders_module.refunded'),
        ];

        return $statusNames[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get formatted datetime
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d.m.Y H:i');
    }

    /**
     * Mark as notified
     */
    public function markAsNotified()
    {
        $this->update([
            'notified_at' => now(),
        ]);

        return $this;
    }

    /**
     * Check if customer was notified
     */
    public function isNotified()
    {
        return !is_null($this->notified_at);
    }

    /**
     * Get notification status
     */
    public function getNotificationStatusAttribute()
    {
        if (!$this->notify_customer) {
            return 'not_required';
        }

        return $this->isNotified() ? 'sent' : 'pending';
    }

    /**
     * Add metadata
     */
    public function addMetadata($key, $value)
    {
        $metadata = $this->metadata ?? [];
        $metadata[$key] = $value;

        $this->update(['metadata' => $metadata]);

        return $this;
    }

    /**
     * Get metadata value
     */
    public function getMetadata($key, $default = null)
    {
        return data_get($this->metadata, $key, $default);
    }

    /**
     * Get user info from metadata
     */
    public function getUserInfoAttribute()
    {
        return [
            'ip_address' => $this->getMetadata('ip_address'),
            'user_agent' => $this->getMetadata('user_agent'),
            'admin_user' => $this->getMetadata('admin_user'),
        ];
    }

    /**
     * Create status history entry
     */
    public static function createEntry($orderId, $status, $notes = null, $notifyCustomer = false, $metadata = [])
    {
        return static::create([
            'order_id' => $orderId,
            'status' => $status,
            'notes' => $notes,
            'notify_customer' => $notifyCustomer,
            'metadata' => array_merge([
                'created_by' => auth()->id(),
                'ip_address' => app('request')->ip(),
                'user_agent' => app('request')->userAgent(),
            ], $metadata),
        ]);
    }

    /**
     * Get timeline entry for display
     */
    public function getTimelineEntryAttribute()
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'status_display' => $this->status_display,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'formatted_date' => $this->formatted_date,
            'notify_customer' => $this->notify_customer,
            'notification_status' => $this->notification_status,
            'user_info' => $this->user_info,
        ];
    }

    /**
     * Scope for entries that need notification
     */
    public function scopeNeedsNotification($query)
    {
        return $query->where('notify_customer', true)
                    ->whereNull('notified_at');
    }

    /**
     * Scope for specific status
     */
    public function scopeForStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for recent entries
     */
    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    /**
     * Scope for entries within date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get status change summary for order
     */
    public static function getOrderTimeline($orderId)
    {
        return static::where('order_id', $orderId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($history) {
                return $history->timeline_entry;
            });
    }

    /**
     * Get status statistics
     */
    public static function getStatusStats($dateFrom = null, $dateTo = null)
    {
        $query = static::query();

        if ($dateFrom) {
            $query->where('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->where('created_at', '<=', $dateTo);
        }

        return $query->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }
}
