<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'manager_id',
        'location',
        'phone',
        'email',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Gerente del departamento
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Posiciones del departamento
     */
    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    /**
     * Empleados del departamento
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Empleados activos del departamento
     */
    public function activeEmployees()
    {
        return $this->employees()->where('status', 'active');
    }

    /**
     * Scope para departamentos activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Obtener cantidad de empleados
     */
    public function getEmployeeCountAttribute(): int
    {
        return $this->employees()->count();
    }

    /**
     * Obtener cantidad de equipos asignados al departamento
     */
    public function getEquipmentCountAttribute(): int
    {
        return Equipment::whereHas('currentEmployee', function ($query) {
            $query->where('department_id', $this->id);
        })->count();
    }
}
