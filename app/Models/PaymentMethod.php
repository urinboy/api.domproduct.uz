<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'display_name',
        'description',
        'is_active',
        'is_online',
        'sort_order',
        'min_amount',
        'max_amount',
        'fee_percentage',
        'fee_fixed',
        'config',
        'gateway_url',
        'webhook_url',
        'supported_currencies',
        'icon_url',
        'logo_url',
        'color_scheme',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_online' => 'boolean',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'fee_fixed' => 'decimal:2',
        'fee_percentage' => 'decimal:2',
        'config' => 'array',
        'supported_currencies' => 'array',
    ];

    const TYPE_CASH = 'cash';
    const TYPE_CARD = 'card';
    const TYPE_BANK_TRANSFER = 'bank_transfer';
    const TYPE_DIGITAL_WALLET = 'digital_wallet';
    const TYPE_CRYPTO = 'crypto';

    const FEE_TYPE_FIXED = 'fixed';
    const FEE_TYPE_PERCENTAGE = 'percentage';
    const FEE_TYPE_COMBINED = 'combined';

    /**
     * Get formatted fee amount
     */
    public function getFormattedFeeAttribute()
    {
        $parts = [];

        if ($this->fee_fixed > 0) {
            $parts[] = number_format($this->fee_fixed, 0) . ' UZS';
        }

        if ($this->fee_percentage > 0) {
            $parts[] = $this->fee_percentage . '%';
        }

        if (empty($parts)) {
            return 'Bepul';
        }

        return implode(' + ', $parts);
    }    /**
     * Get display name with fee info
     */
    public function getDisplayNameAttribute()
    {
        $name = $this->name;

        if ($this->fee_type && ($this->fee_amount > 0 || $this->fee_percentage > 0)) {
            $name .= ' (' . $this->formatted_fee . ')';
        }

        return $name;
    }

    /**
     * Calculate fee for given amount
     */
    public function calculateFee($amount)
    {
        $fee = $this->fee_fixed ?? 0;

        if ($this->fee_percentage > 0) {
            $fee += ($amount * $this->fee_percentage) / 100;
        }

        return round($fee, 2);
    }    /**
     * Get total amount including fee
     */
    public function getTotalWithFee($amount)
    {
        return $amount + $this->calculateFee($amount);
    }

    /**
     * Check if payment method supports given currency
     */
    public function supportsCurrency($currency)
    {
        if (empty($this->supported_currencies)) {
            return true; // If no currencies specified, support all
        }

        return in_array($currency, $this->supported_currencies);
    }

    /**
     * Check if payment method is available in country
     */
    public function isAvailableInCountry($country)
    {
        if (empty($this->countries)) {
            return true; // If no countries specified, available everywhere
        }

        return in_array($country, $this->countries);
    }

    /**
     * Check if amount is within limits
     */
    public function isAmountValid($amount)
    {
        if ($this->min_amount && $amount < $this->min_amount) {
            return false;
        }

        if ($this->max_amount && $amount > $this->max_amount) {
            return false;
        }

        return true;
    }

    /**
     * Get amount limits info
     */
    public function getAmountLimitsAttribute()
    {
        $limits = [];

        if ($this->min_amount) {
            $limits['min'] = number_format($this->min_amount, 2) . ' UZS';
        }

        if ($this->max_amount) {
            $limits['max'] = number_format($this->max_amount, 2) . ' UZS';
        }

        return $limits;
    }

    /**
     * Get gateway configuration for specific key
     */
    public function getGatewayConfig($key, $default = null)
    {
        return data_get($this->gateway_config, $key, $default);
    }

    /**
     * Set gateway configuration
     */
    public function setGatewayConfig($key, $value)
    {
        $config = $this->gateway_config ?? [];
        data_set($config, $key, $value);

        $this->update(['gateway_config' => $config]);

        return $this;
    }

    /**
     * Check if payment method requires additional data
     */
    public function requiresAdditionalData()
    {
        return in_array($this->type, [
            self::TYPE_CARD,
            self::TYPE_BANK_TRANSFER,
        ]);
    }

    /**
     * Get required fields for this payment method
     */
    public function getRequiredFieldsAttribute()
    {
        $fields = [];

        switch ($this->type) {
            case self::TYPE_CARD:
                $fields = ['card_number', 'expiry_month', 'expiry_year', 'cvv', 'cardholder_name'];
                break;

            case self::TYPE_BANK_TRANSFER:
                $fields = ['bank_name', 'account_number', 'account_holder'];
                break;

            case self::TYPE_DIGITAL_WALLET:
                $fields = ['wallet_id'];
                break;
        }

        return $fields;
    }

    /**
     * Get payment method icon
     */
    public function getIconAttribute()
    {
        $icons = [
            self::TYPE_CASH => 'fas fa-money-bill-wave',
            self::TYPE_CARD => 'fas fa-credit-card',
            self::TYPE_BANK_TRANSFER => 'fas fa-university',
            self::TYPE_DIGITAL_WALLET => 'fas fa-wallet',
            self::TYPE_CRYPTO => 'fab fa-bitcoin',
        ];

        return $icons[$this->type] ?? 'fas fa-payment';
    }

    /**
     * Get processing time in readable format
     */
    public function getFormattedProcessingTimeAttribute()
    {
        if (!$this->processing_time) {
            return 'Instant';
        }

        return $this->processing_time;
    }

    /**
     * Create payment record for order
     */
    public function createPaymentRecord($order, $amount, $additionalData = [])
    {
        return [
            'payment_method_id' => $this->id,
            'payment_method_name' => $this->name,
            'payment_method_type' => $this->type,
            'amount' => $amount,
            'fee' => $this->calculateFee($amount),
            'total_amount' => $this->getTotalWithFee($amount),
            'currency' => $order->currency,
            'gateway_config' => $this->gateway_config,
            'additional_data' => $additionalData,
            'created_at' => now(),
        ];
    }

    /**
     * Scope for active payment methods
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for methods supporting currency
     */
    public function scopeSupportingCurrency($query, $currency)
    {
        return $query->where(function ($q) use ($currency) {
            $q->whereNull('supported_currencies')
              ->orWhereJsonContains('supported_currencies', $currency);
        });
    }

    /**
     * Scope for methods available in country
     */
    public function scopeAvailableInCountry($query, $country)
    {
        return $query->where(function ($q) use ($country) {
            $q->whereNull('countries')
              ->orWhereJsonContains('countries', $country);
        });
    }

    /**
     * Scope for methods supporting amount
     */
    public function scopeSupportingAmount($query, $amount)
    {
        return $query->where(function ($q) use ($amount) {
            $q->where(function ($subQ) use ($amount) {
                $subQ->whereNull('min_amount')
                     ->orWhere('min_amount', '<=', $amount);
            })->where(function ($subQ) use ($amount) {
                $subQ->whereNull('max_amount')
                     ->orWhere('max_amount', '>=', $amount);
            });
        });
    }

    /**
     * Get available payment methods for order
     */
    public static function getAvailableForOrder($amount, $currency = 'UZS', $country = null)
    {
        $query = static::active()
            ->supportingCurrency($currency)
            ->supportingAmount($amount);

        if ($country) {
            $query->availableInCountry($country);
        }

        return $query->orderBy('sort_order')
                    ->orderBy('name')
                    ->get();
    }
}
