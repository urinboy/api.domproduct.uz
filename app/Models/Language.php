<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'flag', 'is_active', 'is_default', 'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'sort_order' => 'integer'
    ];

    // Tarjimalar bilan bog'lanish
    public function categoryTranslations()
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function productTranslations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    // Foydalanuvchilar bilan bog'lanish
    public function users()
    {
        return $this->hasMany(User::class, 'preferred_language_id');
    }

    // Faol tillarni olish
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Asosiy tilni olish
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // Tartiblangan til ro'yxati
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
