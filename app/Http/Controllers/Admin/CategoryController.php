<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Language;
use App\Models\CategoryTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Category::with(['translations.language']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('translations', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        // Parent filter
        if ($request->has('parent') && $request->parent !== '') {
            if ($request->parent === 'root') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $request->parent);
            }
        }

        $categories = $query->orderBy('sort_order')->orderBy('id')->paginate(15);
        $parentCategories = Category::whereNull('parent_id')->active()->get();

        return view('admin.categories.index', compact('categories', 'parentCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $languages = Language::active()->get();
        $parentCategories = Category::whereNull('parent_id')->active()->get();

        return view('admin.categories.create', compact('languages', 'parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validateCategory($request);

        $category = Category::create([
            'parent_id' => $request->parent_id,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $this->handleImageUpload($request, $category);
        }

        // Handle icon
        if ($request->filled('icon')) {
            $category->update(['icon' => $request->icon]);
        }

        // Save translations
        $this->saveTranslations($request, $category);

        return redirect()->route('admin.categories.index')
            ->with('success', __('admin.category_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load(['translations.language', 'parent', 'children.translations', 'products.translations']);

        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $category->load(['translations.language']);
        $languages = Language::active()->get();
        $parentCategories = Category::whereNull('parent_id')->active()->where('id', '!=', $category->id)->get();

        return view('admin.categories.edit', compact('category', 'languages', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $this->validateCategory($request, $category->id);

        $category->update([
            'parent_id' => $request->parent_id,
            'sort_order' => $request->sort_order ?? $category->sort_order,
            'is_active' => $request->boolean('is_active'),
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            $this->deleteOldImage($category);
            $this->handleImageUpload($request, $category);
        }

        // Handle icon
        if ($request->filled('icon')) {
            $category->update(['icon' => $request->icon]);
        }

        // Update translations
        $this->saveTranslations($request, $category);

        return redirect()->route('admin.categories.index')
            ->with('success', __('admin.category_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has children
        if ($category->children()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', __('admin.category_has_children_cannot_delete'));
        }

        // Check if category has products
        if ($category->products()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', __('admin.category_has_products_cannot_delete'));
        }

        // Delete image files
        $this->deleteOldImage($category);

        // Delete translations
        $category->translations()->delete();

        // Delete category
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', __('admin.category_deleted_successfully'));
    }

    /**
     * Validate category data
     */
    private function validateCategory(Request $request, $categoryId = null)
    {
        $rules = [
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'nullable|integer|min:0',
            'icon' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
        ];

        // Validation for translations
        $languages = Language::active()->get();
        foreach ($languages as $language) {
            $rules["translations.{$language->code}.name"] = 'required|string|max:255';
            $rules["translations.{$language->code}.description"] = 'nullable|string';
            $rules["translations.{$language->code}.slug"] = 'nullable|string|max:255';
        }

        $request->validate($rules);
    }

    /**
     * Save category translations
     */
    private function saveTranslations(Request $request, Category $category)
    {
        $languages = Language::active()->get();

        foreach ($languages as $language) {
            $translationData = $request->input("translations.{$language->code}");

            if (!empty($translationData['name'])) {
                $category->translations()->updateOrCreate(
                    ['language_id' => $language->id],
                    [
                        'name' => $translationData['name'],
                        'description' => $translationData['description'] ?? null,
                        'slug' => $translationData['slug'] ?? Str::slug($translationData['name']),
                    ]
                );
            }
        }
    }

    /**
     * Handle image upload with multiple sizes
     */
    private function handleImageUpload(Request $request, Category $category)
    {
        if (!$request->hasFile('image')) {
            return;
        }

        $file = $request->file('image');
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

        // Store original image
        $path = $file->storeAs('categories', $fileName, 'public');
        $fullPath = storage_path('app/public/' . $path);

        // Generate different sizes
        $imageSizes = $this->generateImageSizes($fullPath, $fileName);

        $category->update([
            'image_original' => asset('storage/' . $path),
            'image_thumbnail' => $imageSizes['thumbnail'] ?? null,
            'image_small' => $imageSizes['small'] ?? null,
            'image_medium' => $imageSizes['medium'] ?? null,
            'image_large' => $imageSizes['large'] ?? null,
            'image_path' => $path,
            'image' => $imageSizes['medium'] ?? asset('storage/' . $path),
        ]);
    }

    /**
     * Generate different image sizes
     */
    private function generateImageSizes($imagePath, $fileName)
    {
        $sizes = [
            'thumbnail' => [150, 150],
            'small' => [300, 300],
            'medium' => [500, 500],
            'large' => [800, 800],
        ];

        $generatedSizes = [];

        foreach ($sizes as $sizeName => [$width, $height]) {
            try {
                $resizedFileName = pathinfo($fileName, PATHINFO_FILENAME) . "_{$sizeName}." . pathinfo($fileName, PATHINFO_EXTENSION);
                $resizedPath = storage_path('app/public/categories/' . $resizedFileName);

                // Simple resize using GD (you can use Intervention Image for better results)
                if ($this->resizeImage($imagePath, $resizedPath, $width, $height)) {
                    $generatedSizes[$sizeName] = asset('storage/categories/' . $resizedFileName);
                }
            } catch (\Exception $e) {
                // Log error but continue
                Log::warning("Failed to generate {$sizeName} size for category image: " . $e->getMessage());
            }
        }

        return $generatedSizes;
    }    /**
     * Simple image resize using GD
     */
    private function resizeImage($source, $destination, $newWidth, $newHeight)
    {
        if (!extension_loaded('gd')) {
            return false;
        }

        $imageInfo = getimagesize($source);
        if (!$imageInfo) {
            return false;
        }

        $mime = $imageInfo['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($source);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($source);
                break;
            case 'image/gif':
                $sourceImage = imagecreatefromgif($source);
                break;
            default:
                return false;
        }

        if (!$sourceImage) {
            return false;
        }

        $originalWidth = imagesx($sourceImage);
        $originalHeight = imagesy($sourceImage);

        // Calculate aspect ratio
        $ratio = min($newWidth / $originalWidth, $newHeight / $originalHeight);
        $newWidth = $originalWidth * $ratio;
        $newHeight = $originalHeight * $ratio;

        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG and GIF
        if ($mime == 'image/png' || $mime == 'image/gif') {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

        $result = false;
        switch ($mime) {
            case 'image/jpeg':
                $result = imagejpeg($newImage, $destination, 85);
                break;
            case 'image/png':
                $result = imagepng($newImage, $destination);
                break;
            case 'image/gif':
                $result = imagegif($newImage, $destination);
                break;
        }

        imagedestroy($sourceImage);
        imagedestroy($newImage);

        return $result;
    }

    /**
     * Delete old category images
     */
    private function deleteOldImage(Category $category)
    {
        $imagePaths = [
            $category->image_path,
            str_replace(['_thumbnail', '_small', '_medium', '_large'], '', $category->image_path)
        ];

        foreach ($imagePaths as $path) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);

        $status = $category->is_active ? __('admin.activated') : __('admin.deactivated');

        return response()->json([
            'success' => true,
            'message' => __('admin.category_status_updated', ['status' => $status]),
            'is_active' => $category->is_active
        ]);
    }
}
