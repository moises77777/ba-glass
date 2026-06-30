<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'department_id',
        'level',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'level' => 'integer',
    ];

    /**
     * Departamento al que pertenece
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Empleados con esta posición
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Scope para posiciones activas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Obtener nombre completo con departamento
     */
    public function getFullNameAttribute(): string
    {
        return $this->name . ' - ' . $this->department->name;
    }
}
