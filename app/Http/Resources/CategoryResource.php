<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Determine language code from request
        $languageCode = $request->get('lang', $request->header('Accept-Language', 'uz'));
        if (strlen($languageCode) > 2) {
            $languageCode = substr($languageCode, 0, 2);
        }

        // Get translation for the specified language
        $translation = $this->translation($languageCode)->first();

        // Fallback to 'uz' if translation not found
        if (!$translation) {
            $translation = $this->translation('uz')->first();
        }

        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,

            // Translated fields
            'name' => $translation->name ?? 'No translation',
            'description' => $translation->description ?? null,
            'slug' => $translation->slug ?? null,
            'meta_title' => $translation->meta_title ?? null,
            'meta_description' => $translation->meta_description ?? null,

            // Images
            'icon' => $this->getIconUrl(),
            'image' => $this->getImageUrl('medium'),
            'image_sizes' => $this->when(
                $request->user() && $request->user()->canManageCategories(),
                $this->getImageSizes()
            ),

            // Hierarchy information
            'has_children' => $this->hasChildren(),
            'depth' => $this->getDepth(),
            'path' => $this->getPath(),

            // Relationships (when loaded)
            'parent' => $this->when(
                $this->relationLoaded('parent') && $this->parent,
                function () use ($request) {
                    return new CategoryResource($this->parent);
                }
            ),

            'children' => $this->when(
                $this->relationLoaded('children'),
                CategoryResource::collection($this->children)
            ),

            'children_count' => $this->when(
                $this->children_count !== null,
                $this->children_count
            ),

            // Admin-only fields
            'all_translations' => $this->when(
                $request->user() && $request->user()->canManageCategories(),
                $this->translations->groupBy('language.code')->map(function ($translation) {
                    return $translation->first();
                })
            ),

            // Timestamps
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
