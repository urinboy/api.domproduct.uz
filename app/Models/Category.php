<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id', 'sort_order', 'icon', 'image', 'is_active',
        'image_original', 'image_thumbnail', 'image_small', 'image_medium',
        'image_large', 'image_path'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Ota kategoriya bilan bog'lanish (parent relationship)
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Bola kategoriyalar bilan bog'lanish (children relationship)
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Faqat faol bola kategoriyalar
     */
    public function activeChildren()
    {
        return $this->children()->where('is_active', true);
    }

    /**
     * Active children with translations (optimized)
     */
    public function activeChildrenWithTranslations($language = 'uz')
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->where('is_active', true)
            ->with(['translations' => function ($query) use ($language) {
                $query->whereHas('language', function ($q) use ($language) {
                    $q->where('code', $language);
                });
            }])
            ->orderBy('sort_order');
    }

    /**
     * Tarjimalar bilan bog'lanish
     */
    public function translations()
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    /**
     * Translation for specific language (optimized)
     */
    public function translationForLanguage($languageCode = 'uz')
    {
        return $this->hasOne(CategoryTranslation::class)
            ->whereHas('language', function ($query) use ($languageCode) {
                $query->where('code', $languageCode);
            });
    }

    /**
     * Products bilan bog'lanish
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Active products bilan bog'lanish
     */
    public function activeProducts()
    {
        return $this->products()->active()->published();
    }

    /**
     * Ma'lum bir tildagi tarjimani olish
     */
    public function translation($languageCode = 'uz')
    {
        return $this->hasOne(CategoryTranslation::class)
            ->whereHas('language', function ($query) use ($languageCode) {
                $query->where('code', $languageCode);
            });
    }

    /**
     * Scope: Faqat faol kategoriyalar
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Faqat ota kategoriyalar (parent_id = null)
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope: Tartiblangan
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Bola kategoriyalar bormi?
     */
    public function hasChildren()
    {
        return $this->children()->exists();
    }

    /**
     * Qancha chuqur joylashgan? (depth level)
     */
    public function getDepth()
    {
        $depth = 0;
        $parent = $this->parent;

        while ($parent) {
            $depth++;
            $parent = $parent->parent;
        }

        return $depth;
    }

    /**
     * Get category path (array of parent names)
     */
    public function getPath($languageCode = 'uz')
    {
        $path = [];
        $current = $this;

        while ($current) {
            array_unshift($path, $current->getName($languageCode));
            $current = $current->parent;
        }

        return $path;
    }    /**
     * Barcha ota kategoriyalarni olish (breadcrumb uchun)
     */
    public function getAncestors()
    {
        $ancestors = collect();
        $parent = $this->parent;

        while ($parent) {
            $ancestors->prepend($parent);
            $parent = $parent->parent;
        }

        return $ancestors;
    }

    /**
     * Ma'lum tildagi nom olish (fallback bilan)
     */
    public function getName($languageCode = 'uz')
    {
        $translation = $this->translations()
            ->whereHas('language', function ($query) use ($languageCode) {
                $query->where('code', $languageCode);
            })->first();

        if ($translation) {
            return $translation->name;
        }

        // Fallback: Default tildan olish
        $defaultTranslation = $this->translations()
            ->whereHas('language', function ($query) {
                $query->where('is_default', true);
            })->first();

        return $defaultTranslation ? $defaultTranslation->name : 'No Name';
    }

    /**
     * Ma'lum tildagi slug olish
     */
    public function getSlug($languageCode = 'uz')
    {
        $translation = $this->translations()
            ->whereHas('language', function ($query) use ($languageCode) {
                $query->where('code', $languageCode);
            })->first();

        return $translation ? $translation->slug : Str::slug($this->getName($languageCode));
    }

    /**
     * Get icon URL
     */
    public function getIconUrl()
    {
        if ($this->icon) {
            return $this->icon;
        }

        $baseUrl = config('app.url', 'https://api.domproduct.uz');
        return $baseUrl . '/images/default-category-icon.svg';
    }

    /**
     * Get image URL by size
     */
    public function getImageUrl($size = 'medium')
    {
        $field = "image_{$size}";

        if ($this->$field) {
            return $this->$field;
        }

        // Fallback to original if size not available
        if ($this->image_original) {
            return $this->image_original;
        }

        // Fallback to old image field
        if ($this->image) {
            return $this->image;
        }

        // Default image
        return $this->getDefaultImage();
    }

    /**
     * Get default image URL
     */
    public function getDefaultImage()
    {
        return asset('images/default-image.png');
    }

    /**
     * Get safe image URL with fallback to default image
     * This method handles cases where image URL might return 404
     */
    public function getSafeImageUrl($size = 'medium')
    {
        $imageUrl = $this->getImageUrl($size);

        // If getImageUrl already returns default image, return it
        if ($imageUrl === $this->getDefaultImage()) {
            return $imageUrl;
        }

        // For external URLs or storage URLs, we can't easily check if they exist
        // So we return the URL and let frontend handle the fallback
        return $imageUrl;
    }

    /**
     * Get all image sizes
     */
    public function getImageSizes()
    {
        return [
            'thumbnail' => $this->getSafeImageUrl('thumbnail'),
            'small' => $this->getSafeImageUrl('small'),
            'medium' => $this->getSafeImageUrl('medium'),
            'large' => $this->getSafeImageUrl('large'),
            'original' => $this->getSafeImageUrl('original')
        ];
    }

    /**
     * Check if category has a custom image (not default)
     */
    public function hasCustomImage()
    {
        return $this->image_original || $this->image_thumbnail ||
               $this->image_small || $this->image_medium ||
               $this->image_large || $this->image;
    }

    /**
     * Get image HTML tag with safe fallback
     */
    public function getImageTag($size = 'medium', $attributes = [])
    {
        $defaultAttributes = [
            'src' => $this->getSafeImageUrl($size),
            'alt' => $this->getName(),
            'class' => 'category-image',
            'data-default' => $this->getDefaultImage(),
            'onerror' => 'this.src=this.dataset.default; this.onerror=null;'
        ];

        $attributes = array_merge($defaultAttributes, $attributes);

        $attributeString = '';
        foreach ($attributes as $key => $value) {
            $attributeString .= sprintf('%s="%s" ', $key, htmlspecialchars($value));
        }

        return sprintf('<img %s/>', rtrim($attributeString));
    }

    /**
     * Update image URLs
     */
    public function updateImage(array $imageData)
    {
        $this->update([
            'image_original' => $imageData['original'] ?? null,
            'image_thumbnail' => $imageData['sizes']['thumbnail'] ?? null,
            'image_small' => $imageData['sizes']['small'] ?? null,
            'image_medium' => $imageData['sizes']['medium'] ?? null,
            'image_large' => $imageData['sizes']['large'] ?? null,
            'image_path' => $imageData['path'] ?? null,
            'image' => $imageData['sizes']['medium'] ?? $imageData['original'] ?? null
        ]);
    }

    /**
     * Get safe image data for API responses
     */
    public function getImageData($includeDefault = true)
    {
        $data = [
            'has_custom_image' => $this->hasCustomImage(),
            'sizes' => [
                'thumbnail' => $this->getSafeImageUrl('thumbnail'),
                'small' => $this->getSafeImageUrl('small'),
                'medium' => $this->getSafeImageUrl('medium'),
                'large' => $this->getSafeImageUrl('large'),
                'original' => $this->getSafeImageUrl('original')
            ]
        ];

        if ($includeDefault) {
            $data['default_image'] = $this->getDefaultImage();
        }

        return $data;
    }
}
