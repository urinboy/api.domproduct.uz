<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'first_name', 'last_name', 'email', 'phone', 'password',
        'birth_date', 'gender', 'address', 'city', 'district', 'postal_code',
        'latitude', 'longitude', 'role', 'avatar', 'preferred_language_id',
        'email_verified', 'phone_verified', 'is_active', 'last_login_at', 'preferences',
        'avatar_original', 'avatar_thumbnail', 'avatar_small', 'avatar_medium',
        'avatar_large', 'avatar_path'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
        'email_verified' => 'boolean',
        'phone_verified' => 'boolean',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'preferences' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    // Afzal qilingan til bilan bog'lanish
    public function preferredLanguage(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'preferred_language_id');
    }

    // Shopping Cart relationship
    public function shoppingCart(): HasOne
    {
        return $this->hasOne(ShoppingCart::class);
    }

    // Get active shopping cart
    public function getActiveCart()
    {
        return ShoppingCart::createOrGetCart($this->id);
    }

    // Orders relationship
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Recent orders (optimized)
    public function recentOrders(): HasMany
    {
        return $this->hasMany(Order::class)
            ->with(['items.product.translations', 'statusHistory'])
            ->latest()
            ->limit(10);
    }

    // Addresses relationship
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    // Active addresses (optimized)
    public function activeAddresses(): HasMany
    {
        return $this->hasMany(Address::class)
            ->where('is_active', true)
            ->orderBy('is_default', 'desc');
    }

    // Notifications relationship
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // Unread notifications (optimized)
    public function unreadNotifications(): HasMany
    {
        return $this->hasMany(Notification::class)
            ->where('status', '!=', 'read')
            ->whereNull('read_at')
            ->latest()
            ->limit(20);
    }

    // Get default billing address
    public function getDefaultBillingAddress()
    {
        return $this->addresses()
            ->billing()
            ->default()
            ->first();
    }

    // Get default shipping address
    public function getDefaultShippingAddress()
    {
        return $this->addresses()
            ->shipping()
            ->default()
            ->first();
    }

    // To'liq ismni olish
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name) ?: $this->name;
    }

    // Role bo'yicha filterlar
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeManagers($query)
    {
        return $query->where('role', 'manager');
    }

    public function scopeEmployees($query)
    {
        return $query->where('role', 'employee');
    }

    public function scopeCustomers($query)
    {
        return $query->where('role', 'customer');
    }

    // Role checking methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isEmployee()
    {
        return $this->role === 'employee';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    // Permission checking methods
    public function canManageCategories()
    {
        return in_array($this->role, ['admin', 'manager']);
    }

    public function canManageProducts()
    {
        return in_array($this->role, ['admin', 'manager', 'employee']);
    }

    public function canManageUsers()
    {
        return $this->role === 'admin';
    }

    public function canViewReports()
    {
        return in_array($this->role, ['admin', 'manager']);
    }

    /**
     * Get avatar URL by size
     */
    public function getAvatarUrl($size = 'medium')
    {
        $field = "avatar_{$size}";

        if ($this->$field) {
            return $this->$field;
        }

        // Fallback to original if size not available
        if ($this->avatar_original) {
            return $this->avatar_original;
        }

        // Fallback to old avatar field
        if ($this->avatar) {
            return $this->avatar;
        }

        // Default avatar
        return $this->getDefaultAvatar();
    }

    /**
     * Get default avatar URL
     */
    public function getDefaultAvatar()
    {
        $baseUrl = config('app.url', 'https://api.domproduct.uz');
        return $baseUrl . '/images/default-avatar.png';
    }

    /**
     * Get all avatar sizes
     */
    public function getAvatarSizes()
    {
        return [
            'thumbnail' => $this->getAvatarUrl('thumbnail'),
            'small' => $this->getAvatarUrl('small'),
            'medium' => $this->getAvatarUrl('medium'),
            'large' => $this->getAvatarUrl('large'),
            'original' => $this->getAvatarUrl('original')
        ];
    }

    /**
     * Update avatar URLs
     */
    public function updateAvatar(array $avatarData)
    {
        $this->update([
            'avatar_original' => $avatarData['original'] ?? null,
            'avatar_thumbnail' => $avatarData['sizes']['thumbnail'] ?? null,
            'avatar_small' => $avatarData['sizes']['small'] ?? null,
            'avatar_medium' => $avatarData['sizes']['medium'] ?? null,
            'avatar_large' => $avatarData['sizes']['large'] ?? null,
            'avatar_path' => $avatarData['path'] ?? null,
            'avatar' => $avatarData['sizes']['medium'] ?? $avatarData['original'] ?? null
        ]);
    }

    // Faol foydalanuvchilar
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Tasdiqlangan foydalanuvchilar
    public function scopeVerified($query)
    {
        return $query->where('email_verified', true)->where('phone_verified', true);
    }
}
