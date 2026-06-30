<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'filename',
        'original_filename',
        'path',
        'mime_type',
        'size',
        'title',
        'description',
        'type',
        'sort_order',
        'is_primary',
        'uploaded_by',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'size' => 'integer',
        'sort_order' => 'integer',
    ];

    const TYPES = [
        'main' => 'Principal',
        'front' => 'Frontal',
        'back' => 'Trasera',
        'side' => 'Lateral',
        'detail' => 'Detalle',
        'damage' => 'Daño',
        'other' => 'Otra',
    ];

    /**
     * Equipo al que pertenece
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * Usuario que subió la imagen
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Obtener URL de la imagen
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    /**
     * Obtener nombre del tipo
     */
    public function getTypeNameAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    /**
     * Obtener tamaño formateado
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
