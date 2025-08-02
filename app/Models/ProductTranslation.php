<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ProductTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'language',
        'name',
        'slug',
        'short_description',
        'description',
        'specifications',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'features',
        'care_instructions',
        'warranty_info',
    ];

    protected $casts = [
        'specifications' => 'array',
        'features' => 'array',
    ];

    /**
     * Get the product that owns the translation.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug) && !empty($model->name)) {
                $model->slug = $model->generateUniqueSlug($model->name, $model->language);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('name') && (empty($model->slug) || $model->isDirty('name'))) {
                $model->slug = $model->generateUniqueSlug($model->name, $model->language);
            }
        });
    }

    /**
     * Generate a unique slug for the translation
     */
    private function generateUniqueSlug($name, $language)
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while ($this->slugExists($slug, $language)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if slug exists for the given language
     */
    private function slugExists($slug, $language)
    {
        $query = static::where('slug', $slug)->where('language', $language);

        if ($this->exists) {
            $query->where('id', '!=', $this->id);
        }

        return $query->exists();
    }

    /**
     * Get the meta title or fall back to name
     */
    public function getMetaTitleAttribute($value)
    {
        return $value ?: $this->name;
    }

    /**
     * Get the meta description or fall back to short description
     */
    public function getMetaDescriptionAttribute($value)
    {
        return $value ?: Str::limit(strip_tags($this->short_description ?? ''), 160);
    }

    /**
     * Scope for specific language
     */
    public function scopeForLanguage($query, $language)
    {
        return $query->where('language', $language);
    }

    /**
     * Scope for finding by slug and language
     */
    public function scopeBySlugAndLanguage($query, $slug, $language)
    {
        return $query->where('slug', $slug)->where('language', $language);
    }
}
