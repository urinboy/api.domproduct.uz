<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'first_name',
        'last_name',
        'company',
        'phone',
        'email',
        'country',
        'region',
        'city',
        'district',
        'street_address',
        'apartment',
        'postal_code',
        'latitude',
        'longitude',
        'delivery_instructions',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    const TYPE_BILLING = 'billing';
    const TYPE_SHIPPING = 'shipping';
    const TYPE_BOTH = 'both';

    /**
     * Get the user that owns the address.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($address) {
            if ($address->is_default) {
                // Remove default status from other addresses of the same type
                static::where('user_id', $address->user_id)
                    ->where('type', $address->type)
                    ->update(['is_default' => false]);
            }
        });

        static::updating(function ($address) {
            if ($address->is_default && $address->isDirty('is_default')) {
                // Remove default status from other addresses of the same type
                static::where('user_id', $address->user_id)
                    ->where('type', $address->type)
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
            }
        });
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get full address as single string
     */
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->street_address,
            $this->apartment ? "apt. {$this->apartment}" : null,
            $this->district,
            $this->city,
            $this->region,
            $this->country,
            $this->postal_code,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get formatted address for display
     */
    public function getFormattedAddressAttribute()
    {
        return [
            'name' => $this->full_name,
            'company' => $this->company,
            'phone' => $this->phone,
            'email' => $this->email,
            'address_line_1' => $this->street_address,
            'address_line_2' => $this->apartment,
            'city' => $this->city,
            'district' => $this->district,
            'region' => $this->region,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'coordinates' => $this->coordinates,
            'delivery_instructions' => $this->delivery_instructions,
        ];
    }

    /**
     * Get coordinates as array
     */
    public function getCoordinatesAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return [
                'lat' => (float) $this->latitude,
                'lng' => (float) $this->longitude,
            ];
        }

        return null;
    }

    /**
     * Set coordinates
     */
    public function setCoordinates($latitude, $longitude)
    {
        $this->update([
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        return $this;
    }

    /**
     * Get type display name
     */
    public function getTypeDisplayAttribute()
    {
        $types = [
            self::TYPE_BILLING => 'Billing Address',
            self::TYPE_SHIPPING => 'Shipping Address',
            self::TYPE_BOTH => 'Billing & Shipping',
        ];

        return $types[$this->type] ?? $this->type;
    }

    /**
     * Check if address is complete
     */
    public function isComplete()
    {
        $required = [
            'first_name', 'last_name', 'phone',
            'country', 'region', 'city', 'street_address'
        ];

        foreach ($required as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get missing required fields
     */
    public function getMissingFields()
    {
        $required = [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => 'Phone Number',
            'country' => 'Country',
            'region' => 'Region',
            'city' => 'City',
            'street_address' => 'Street Address',
        ];

        $missing = [];

        foreach ($required as $field => $label) {
            if (empty($this->$field)) {
                $missing[$field] = $label;
            }
        }

        return $missing;
    }

    /**
     * Make this address default
     */
    public function makeDefault()
    {
        // Remove default from other addresses of same type
        static::where('user_id', $this->user_id)
            ->where('type', $this->type)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        $this->update(['is_default' => true]);

        return $this;
    }

    /**
     * Calculate distance to another address or coordinates
     */
    public function distanceTo($latitude, $longitude, $unit = 'km')
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        $earthRadius = $unit === 'km' ? 6371 : 3959; // km or miles

        $lat1 = deg2rad($this->latitude);
        $lon1 = deg2rad($this->longitude);
        $lat2 = deg2rad($latitude);
        $lon2 = deg2rad($longitude);

        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1) * cos($lat2) *
             sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    /**
     * Convert to array for order storage
     */
    public function toOrderArray()
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'company' => $this->company,
            'phone' => $this->phone,
            'email' => $this->email,
            'country' => $this->country,
            'region' => $this->region,
            'city' => $this->city,
            'district' => $this->district,
            'street_address' => $this->street_address,
            'apartment' => $this->apartment,
            'postal_code' => $this->postal_code,
            'coordinates' => $this->coordinates,
            'delivery_instructions' => $this->delivery_instructions,
        ];
    }

    /**
     * Scope for active addresses
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for default addresses
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope for specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for billing addresses
     */
    public function scopeBilling($query)
    {
        return $query->whereIn('type', [self::TYPE_BILLING, self::TYPE_BOTH]);
    }

    /**
     * Scope for shipping addresses
     */
    public function scopeShipping($query)
    {
        return $query->whereIn('type', [self::TYPE_SHIPPING, self::TYPE_BOTH]);
    }

    /**
     * Scope for addresses in specific city
     */
    public function scopeInCity($query, $city)
    {
        return $query->where('city', $city);
    }

    /**
     * Scope for addresses in specific region
     */
    public function scopeInRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    /**
     * Scope for addresses with coordinates
     */
    public function scopeWithCoordinates($query)
    {
        return $query->whereNotNull('latitude')
                    ->whereNotNull('longitude');
    }
}
