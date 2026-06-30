<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'parent_id',
        'address',
        'city',
        'state',
        'postal_code',
        'latitude',
        'longitude',
        'capacity',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    const TYPES = [
        'building' => 'Edificio',
        'floor' => 'Piso',
        'room' => 'Sala/Oficina',
        'area' => 'Área',
        'warehouse' => 'Almacén',
        'other' => 'Otro',
    ];

    /**
     * Ubicación padre
     */
    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    /**
     * Sub-ubicaciones
     */
    public function children()
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    /**
     * Equipos en esta ubicación
     */
    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }

    /**
     * Asignaciones en esta ubicación
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Scope para ubicaciones activas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para ubicaciones principales
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Obtener nombre del tipo
     */
    public function getTypeNameAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    /**
     * Obtener ruta completa de ubicación
     */
    public function getFullPathAttribute(): string
    {
        $path = [$this->name];
        $parent = $this->parent;
        
        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }
        
        return implode(' > ', $path);
    }

    /**
     * Obtener cantidad de equipos
     */
    public function getEquipmentCountAttribute(): int
    {
        return $this->equipment()->count();
    }
}
