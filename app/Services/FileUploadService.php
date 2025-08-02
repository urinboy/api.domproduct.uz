<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Upload va resize image
     */
    public function uploadImage(UploadedFile $file, string $folder = 'images', array $sizes = []): array
    {
        // Validate file
        $this->validateImage($file);

        // Generate unique filename
        $filename = $this->generateFilename($file);

        // Main upload path
        $path = $folder . '/' . date('Y/m/d');
        $fullPath = $path . '/' . $filename;

        // Store original file
        Storage::disk('uploads')->put($fullPath, file_get_contents($file));

        $result = [
            'original' => $this->getPublicUrl($fullPath),
            'path' => $fullPath,
            'filename' => $filename,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType()
        ];

        // Generate resized versions if sizes provided
        if (!empty($sizes)) {
            $result['sizes'] = [];
            foreach ($sizes as $sizeName => $dimensions) {
                // For now, just use the original image for all sizes
                // Later we can implement actual resizing with GD or ImageMagick
                $result['sizes'][$sizeName] = $this->getPublicUrl($fullPath);
            }
        }

        return $result;
    }

    /**
     * Upload avatar specifically
     */
    public function uploadAvatar(UploadedFile $file, int $userId): array
    {
        $sizes = [
            'thumbnail' => ['width' => 64, 'height' => 64],
            'small' => ['width' => 128, 'height' => 128],
            'medium' => ['width' => 256, 'height' => 256],
            'large' => ['width' => 512, 'height' => 512]
        ];

        return $this->uploadImage($file, "avatars/user_{$userId}", $sizes);
    }

    /**
     * Upload product image
     */
    public function uploadProductImage(UploadedFile $file, int $productId): array
    {
        $sizes = [
            'thumbnail' => ['width' => 150, 'height' => 150],
            'small' => ['width' => 300, 'height' => 300],
            'medium' => ['width' => 600, 'height' => 600],
            'large' => ['width' => 1200, 'height' => 1200]
        ];

        return $this->uploadImage($file, "products/product_{$productId}", $sizes);
    }

    /**
     * Upload category image
     */
    public function uploadCategoryImage(UploadedFile $file, int $categoryId): array
    {
        $sizes = [
            'thumbnail' => ['width' => 100, 'height' => 100],
            'small' => ['width' => 200, 'height' => 200],
            'medium' => ['width' => 400, 'height' => 400]
        ];

        return $this->uploadImage($file, "categories/category_{$categoryId}", $sizes);
    }

    /**
     * Delete image and all its sizes
     */
    public function deleteImage(string $path): bool
    {
        try {
            // Delete original
            Storage::disk('uploads')->delete($path);

            // Delete resized versions
            $pathInfo = pathinfo($path);
            $pattern = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_*.' . $pathInfo['extension'];

            $files = Storage::disk('uploads')->files($pathInfo['dirname']);
            foreach ($files as $file) {
                if (fnmatch($pattern, $file)) {
                    Storage::disk('uploads')->delete($file);
                }
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to delete image: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Validate uploaded image
     */
    private function validateImage(UploadedFile $file): void
    {
        $maxSize = 10 * 1024 * 1024; // 10MB
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if ($file->getSize() > $maxSize) {
            throw new \InvalidArgumentException('File size too large. Maximum 10MB allowed.');
        }

        if (!in_array($file->getMimeType(), $allowedTypes)) {
            throw new \InvalidArgumentException('Invalid file type. Only JPEG, PNG, GIF and WebP allowed.');
        }
    }

    /**
     * Generate unique filename
     */
    private function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        return Str::random(40) . '.' . $extension;
    }

    /**
     * Create resized image (simplified version without Image processing)
     */
    private function createResizedImage(string $originalPath, array $dimensions, string $sizeName): string
    {
        $pathInfo = pathinfo($originalPath);
        $resizedFilename = $pathInfo['filename'] . "_{$sizeName}." . $pathInfo['extension'];
        $resizedPath = $pathInfo['dirname'] . '/' . $resizedFilename;

        // For now, just copy the original file
        // Later implement actual resizing with GD or ImageMagick
        try {
            Storage::disk('uploads')->copy($originalPath, $resizedPath);
        } catch (\Exception $e) {
            \Log::error('Failed to create resized image: ' . $e->getMessage());
            // Return original path if resize fails
            return $originalPath;
        }

        return $resizedPath;
    }

    /**
     * Get public URL for uploaded file
     */
    public function getPublicUrl(string $path): string
    {
        return Storage::disk('uploads')->url($path);
    }

    /**
     * Get file info
     */
    public function getFileInfo(string $path): ?array
    {
        if (!Storage::disk('uploads')->exists($path)) {
            return null;
        }

        return [
            'path' => $path,
            'url' => $this->getPublicUrl($path),
            'size' => Storage::disk('uploads')->size($path),
            'modified' => Storage::disk('uploads')->lastModified($path)
        ];
    }
}
