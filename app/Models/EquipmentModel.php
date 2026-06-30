<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipment_models';

    protected $fillable = [
        'brand_id',
        'category_id',
        'name',
        'part_number',
        'processor',
        'ram',
        'storage',
        'storage_type',
        'graphics_card',
        'screen_size',
        'operating_system',
        'reference_price',
        'currency',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'reference_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(EquipmentCategory::class, 'category_id');
    }

    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'equipment_model_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFullNameAttribute(): string
    {
        return trim(($this->brand?->name ?? '') . ' ' . $this->name);
    }
}
