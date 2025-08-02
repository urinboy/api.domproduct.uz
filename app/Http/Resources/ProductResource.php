<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $language = $request->header('Accept-Language', 'uz');
        $translation = $this->getTranslation($language);
        $isAdmin = $request->user() && $request->user()->canManageProducts();

        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'barcode' => $this->when($isAdmin, $this->barcode),

            // Translation data
            'name' => $translation ? $translation->name : null,
            'slug' => $translation ? $translation->slug : null,
            'short_description' => $translation ? $translation->short_description : null,
            'description' => $translation ? $translation->description : null,
            'specifications' => $translation ? $translation->specifications : null,
            'features' => $translation ? $translation->features : null,
            'care_instructions' => $translation ? $translation->care_instructions : null,
            'warranty_info' => $translation ? $translation->warranty_info : null,

            // Pricing
            'price' => (float) $this->price,
            'sale_price' => $this->sale_price ? (float) $this->sale_price : null,
            'effective_price' => (float) $this->getEffectivePrice(),
            'discount_percentage' => $this->getDiscountPercentage(),
            'is_on_sale' => $this->isOnSale(),

            // Admin-only pricing
            'cost_price' => $this->when($isAdmin, $this->cost_price ? (float) $this->cost_price : null),

            // Stock information
            'stock_quantity' => $this->when($isAdmin || $this->track_stock, $this->stock_quantity),
            'stock_status' => $this->stock_status,
            'is_in_stock' => $this->isInStock(),
            'is_low_stock' => $this->when($isAdmin, $this->isLowStock()),
            'min_stock_level' => $this->when($isAdmin, $this->min_stock_level),
            'track_stock' => $this->when($isAdmin, $this->track_stock),

            // Physical properties
            'weight' => $this->weight ? (float) $this->weight : null,
            'dimensions' => $this->when($this->length || $this->width || $this->height, [
                'length' => $this->length ? (float) $this->length : null,
                'width' => $this->width ? (float) $this->width : null,
                'height' => $this->height ? (float) $this->height : null,
            ]),

            // Product properties
            'is_digital' => $this->is_digital,
            'is_featured' => $this->is_featured,
            'is_active' => $this->when($isAdmin, $this->is_active),

            // Statistics
            'rating' => (float) $this->rating,
            'review_count' => $this->review_count,
            'view_count' => $this->when($isAdmin, $this->view_count),

            // Images
            'primary_image' => $this->when($this->primaryImage, function () {
                return [
                    'id' => $this->primaryImage->id,
                    'alt_text' => $this->primaryImage->alt_text,
                    'urls' => $this->primaryImage->getAllUrls(),
                ];
            }),
            'images' => $this->when($this->relationLoaded('images'), function () {
                return $this->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'alt_text' => $image->alt_text,
                        'sort_order' => $image->sort_order,
                        'is_primary' => $image->is_primary,
                        'urls' => $image->getAllUrls(),
                    ];
                });
            }),

            // Category
            'category' => $this->when($this->relationLoaded('category'), function () use ($request) {
                return new CategoryResource($this->category);
            }),

            // SEO
            'meta_title' => $translation ? $translation->meta_title : null,
            'meta_description' => $translation ? $translation->meta_description : null,
            'meta_keywords' => $translation ? $translation->meta_keywords : null,

            // Admin fields
            'sort_order' => $this->when($isAdmin, $this->sort_order),
            'published_at' => $this->when($isAdmin, $this->published_at ? $this->published_at->toISOString() : null),
            'created_at' => $this->when($isAdmin, $this->created_at->toISOString()),
            'updated_at' => $this->when($isAdmin, $this->updated_at->toISOString()),

            // Additional data for admin
            'all_translations' => $this->when($isAdmin && $this->relationLoaded('translations'), function () {
                return $this->translations->map(function ($translation) {
                    return [
                        'id' => $translation->id,
                        'language' => $translation->language,
                        'name' => $translation->name,
                        'slug' => $translation->slug,
                        'short_description' => $translation->short_description,
                        'description' => $translation->description,
                        'specifications' => $translation->specifications,
                        'features' => $translation->features,
                        'care_instructions' => $translation->care_instructions,
                        'warranty_info' => $translation->warranty_info,
                        'meta_title' => $translation->meta_title,
                        'meta_description' => $translation->meta_description,
                        'meta_keywords' => $translation->meta_keywords,
                    ];
                });
            }),
        ];
    }
}
