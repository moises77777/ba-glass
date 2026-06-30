<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'icon',
        'color',
        'parent_id',
        'sort_order',
        'requires_serial',
        'is_active',
    ];

    protected $casts = [
        'requires_serial' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Categoría padre
     */
    public function parent()
    {
        return $this->belongsTo(EquipmentCategory::class, 'parent_id');
    }

    /**
     * Subcategorías
     */
    public function children()
    {
        return $this->hasMany(EquipmentCategory::class, 'parent_id');
    }

    /**
     * Equipos de esta categoría
     */
    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'category_id');
    }

    /**
     * Scope para categorías activas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para categorías principales (sin padre)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Obtener nombre completo con jerarquía
     */
    public function getFullNameAttribute(): string
    {
        if ($this->parent) {
            return $this->parent->name . ' > ' . $this->name;
        }
        return $this->name;
    }

    /**
     * Obtener cantidad de equipos
     */
    public function getEquipmentCountAttribute(): int
    {
        return $this->equipment()->count();
    }
}
