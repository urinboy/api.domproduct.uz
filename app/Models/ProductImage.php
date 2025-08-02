<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'filename',
        'path',
        'alt_text',
        'sort_order',
        'mime_type',
        'file_size',
        'width',
        'height',
        'is_primary',
        'is_active',
        'sizes',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
        'sizes' => 'array',
    ];

    /**
     * Get the product that owns the image.
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
            // If this is set as primary, unset other primary images for the same product
            if ($model->is_primary) {
                static::where('product_id', $model->product_id)
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);
            }
        });

        static::updating(function ($model) {
            // If this is being set as primary, unset other primary images for the same product
            if ($model->isDirty('is_primary') && $model->is_primary) {
                static::where('product_id', $model->product_id)
                    ->where('id', '!=', $model->id)
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);
            }
        });

        static::deleting(function ($model) {
            // Delete physical files when model is deleted
            $model->deleteFiles();
        });
    }

    /**
     * Get image URL for specific size
     */
    public function getImageUrl($size = 'medium')
    {
        $baseUrl = config('app.url');

        // API subdomain URL for production
        if (config('app.env') === 'production') {
            $baseUrl = 'https://api.domproduct.uz';
        }

        if ($size === 'original') {
            return $baseUrl . '/storage/' . $this->path;
        }

        $sizes = $this->sizes ?? [];

        if (isset($sizes[$size])) {
            return $baseUrl . '/storage/' . $sizes[$size];
        }

        // Fallback to original if size not found
        return $baseUrl . '/storage/' . $this->path;
    }

    /**
     * Get all image URLs with different sizes
     */
    public function getAllUrls()
    {
        $urls = [
            'original' => $this->getImageUrl('original')
        ];

        $sizes = $this->sizes ?? [];
        foreach ($sizes as $size => $path) {
            $urls[$size] = $this->getImageUrl($size);
        }

        return $urls;
    }

    /**
     * Update image with new file and sizes
     */
    public function updateImage($filename, $path, $sizes = [])
    {
        // Delete old files
        $this->deleteFiles();

        // Update with new file info
        $this->update([
            'filename' => $filename,
            'path' => $path,
            'sizes' => $sizes,
        ]);
    }

    /**
     * Delete all associated files
     */
    public function deleteFiles()
    {
        // Delete original file
        if (Storage::disk('public')->exists($this->path)) {
            Storage::disk('public')->delete($this->path);
        }

        // Delete size variants
        $sizes = $this->sizes ?? [];
        foreach ($sizes as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    /**
     * Scope for active images
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for primary image
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope for ordered images
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Set as primary image
     */
    public function setPrimary()
    {
        // Unset other primary images for the same product
        static::where('product_id', $this->product_id)
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);

        // Set this as primary
        $this->update(['is_primary' => true]);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSize()
    {
        if (!$this->file_size) {
            return null;
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
