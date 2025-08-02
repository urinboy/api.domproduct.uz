<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CategoryTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'language_id', 'name', 'description',
        'slug', 'meta_title', 'meta_description'
    ];

    /**
     * Kategoriya bilan bog'lanish
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Til bilan bog'lanish
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * Scope: Til bo'yicha filter
     */
    public function scopeByLanguage($query, $languageCode)
    {
        return $query->whereHas('language', function ($q) use ($languageCode) {
            $q->where('code', $languageCode);
        });
    }

    /**
     * Scope: Slug bo'yicha qidirish
     */
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Avtomatik slug yaratish (model save bo'lishidan oldin)
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug) && !empty($model->name)) {
                $model->slug = $model->generateUniqueSlug($model->name);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('name') && !$model->isDirty('slug')) {
                $model->slug = $model->generateUniqueSlug($model->name);
            }
        });
    }

    /**
     * Unique slug yaratish
     */
    public function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        // Bir xil slug mavjudligini tekshirish
        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Slug mavjudligini tekshirish
     */
    private function slugExists($slug)
    {
        $query = self::where('slug', $slug)
            ->where('language_id', $this->language_id);

        // Agar updating bo'lsa, o'zini exclude qilish
        if ($this->exists) {
            $query->where('id', '!=', $this->id);
        }

        return $query->exists();
    }

    /**
     * Meta title avtomatik yaratish (agar bo'sh bo'lsa)
     */
    public function getMetaTitleAttribute($value)
    {
        return $value ?: $this->name;
    }

    /**
     * Meta description avtomatik yaratish (agar bo'sh bo'lsa)
     */
    public function getMetaDescriptionAttribute($value)
    {
        if ($value) {
            return $value;
        }

        if ($this->description) {
            return Str::limit(strip_tags($this->description), 160);
        }

        return $this->name . ' - kategoriyasidagi mahsulotlar';
    }
}
