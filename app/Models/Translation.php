<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'language_id',
        'group'
    ];

    protected $casts = [
        'language_id' => 'integer'
    ];

    /**
     * Tilga bog'lanish
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * Kalitga qarab tarjimani olish
     */
    public static function getTranslation($key, $languageCode = null, $default = null)
    {
        if (!$languageCode) {
            $languageCode = app()->getLocale();
        }

        $translation = self::whereHas('language', function ($query) use ($languageCode) {
            $query->where('code', $languageCode);
        })->where('key', $key)->first();

        return $translation ? $translation->value : ($default ?? $key);
    }

    /**
     * Scope: Tilga qarab
     */
    public function scopeForLanguage($query, $languageCode)
    {
        return $query->whereHas('language', function ($q) use ($languageCode) {
            $q->where('code', $languageCode);
        });
    }

    /**
     * Scope: Guruhga qarab
     */
    public function scopeInGroup($query, $group)
    {
        return $query->where('group', $group);
    }
}
