<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'channel',
        'user_id',
        'recipient_email',
        'recipient_phone',
        'title',
        'message',
        'data',
        'status',
        'sent_at',
        'read_at',
        'retry_count',
        'error_message',
        'template',
    ];

    protected $casts = [
        'data' => 'array',
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    /**
     * Foydalanuvchi bilan bog'lanish
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Bildirishnomani yuborilgan deb belgilash
     */
    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Bildirishnomani o'qilgan deb belgilash
     */
    public function markAsRead(): void
    {
        $this->update([
            'status' => 'read',
            'read_at' => now(),
        ]);
    }

    /**
     * Bildirishnomani muvaffaqiyatsiz deb belgilash
     */
    public function markAsFailed(string $errorMessage = null): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
            'retry_count' => $this->retry_count + 1,
        ]);
    }

    /**
     * Qayta urinish mumkinligini tekshirish
     */
    public function canRetry(): bool
    {
        return $this->retry_count < 3 && $this->status === 'failed';
    }

    /**
     * Scope: faqat yuborilmagan
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: faqat yuborilgan
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope: faqat o'qilmagan
     */
    public function scopeUnread($query)
    {
        return $query->where('status', '!=', 'read');
    }
}
