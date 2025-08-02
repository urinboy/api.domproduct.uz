<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'full_name' => $this->full_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            'avatar' => $this->getAvatarUrl('medium'),
            'avatar_sizes' => $this->when(
                $request->user() && ($request->user()->id === $this->id || $request->user()->canManageUsers()),
                $this->getAvatarSizes()
            ),

            // Location info
            'address' => $this->address,
            'city' => $this->city,
            'district' => $this->district,
            'postal_code' => $this->postal_code,
            'coordinates' => $this->when(
                $this->latitude && $this->longitude,
                [
                    'latitude' => $this->latitude,
                    'longitude' => $this->longitude
                ]
            ),

            // Personal info
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,

            // Status
            'is_active' => $this->is_active,
            'email_verified' => $this->email_verified,
            'phone_verified' => $this->phone_verified,

            // Language preference
            'preferred_language' => $this->when(
                $this->preferredLanguage,
                $this->preferredLanguage->code ?? 'uz'
            ),

            // Timestamps
            'last_login_at' => $this->last_login_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Permissions (only for authenticated user or admin)
            'permissions' => $this->when(
                $request->user() && ($request->user()->id === $this->id || $request->user()->canManageUsers()),
                [
                    'can_manage_categories' => $this->canManageCategories(),
                    'can_manage_products' => $this->canManageProducts(),
                    'can_manage_users' => $this->canManageUsers(),
                    'can_view_reports' => $this->canViewReports()
                ]
            )
        ];
    }
}
