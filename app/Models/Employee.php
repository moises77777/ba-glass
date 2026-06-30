<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'mobile',
        'curp',
        'rfc',
        'birth_date',
        'hire_date',
        'termination_date',
        'department_id',
        'position_id',
        'user_id',
        'supervisor_id',
        'address',
        'city',
        'state',
        'postal_code',
        'emergency_contact_name',
        'emergency_contact_phone',
        'photo',
        'signature',
        'status',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'hire_date' => 'date',
        'termination_date' => 'date',
    ];

    protected $appends = ['full_name'];

    /**
     * Usuario asociado
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Departamento
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Posición/Puesto
     */
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Supervisor
     */
    public function supervisor()
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    /**
     * Subordinados
     */
    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'supervisor_id');
    }

    /**
     * Equipos asignados actualmente
     */
    public function currentEquipment()
    {
        return $this->hasMany(Equipment::class, 'current_employee_id');
    }

    /**
     * Todas las asignaciones
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Asignaciones activas
     */
    public function activeAssignments()
    {
        return $this->assignments()->where('status', 'active');
    }

    /**
     * Historial de equipos
     */
    public function equipmentHistory()
    {
        return $this->hasMany(EquipmentHistory::class, 'new_employee_id');
    }

    /**
     * Obtener nombre completo
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Obtener URL de la foto
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&background=6366f1&color=fff';
    }

    /**
     * Scope para empleados activos
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope para búsqueda
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('employee_number', 'LIKE', "%{$search}%")
              ->orWhere('first_name', 'LIKE', "%{$search}%")
              ->orWhere('last_name', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Verificar si tiene equipos asignados
     */
    public function hasEquipmentAssigned(): bool
    {
        return $this->currentEquipment()->exists();
    }

    /**
     * Obtener cantidad de equipos asignados
     */
    public function getEquipmentCountAttribute(): int
    {
        return $this->currentEquipment()->count();
    }

    /**
     * Verificar si está activo
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
