<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'assignment_number',
        'equipment_id',
        'employee_id',
        'assignment_date',
        'expected_return_date',
        'actual_return_date',
        'status',
        'condition_at_assignment',
        'condition_at_return',
        'assigned_by',
        'received_by',
        'location_id',
        'work_area',
        'assignment_notes',
        'return_notes',
        'accessories_delivered',
        'accessories_returned',
        'custody_letter_path',
        'custody_letter_folio',
        'custody_letter_generated_at',
        'custody_letter_signed',
        'employee_signature',
        'responsible_signature',
        'return_reason',
        'return_reason_details',
    ];

    protected $casts = [
        'assignment_date' => 'datetime',
        'expected_return_date' => 'datetime',
        'actual_return_date' => 'datetime',
        'custody_letter_generated_at' => 'datetime',
        'custody_letter_signed' => 'boolean',
    ];

    const STATUSES = [
        'active' => 'Activa',
        'returned' => 'Devuelta',
        'transferred' => 'Transferida',
        'cancelled' => 'Cancelada',
        'lost' => 'Extraviada',
    ];

    const CONDITIONS = [
        'excellent' => 'Excelente',
        'good' => 'Bueno',
        'fair' => 'Regular',
        'poor' => 'Malo',
        'damaged' => 'Dañado',
    ];

    const RETURN_REASONS = [
        'employee_termination' => 'Baja del empleado',
        'equipment_upgrade' => 'Actualización de equipo',
        'equipment_damage' => 'Daño del equipo',
        'department_change' => 'Cambio de departamento',
        'project_end' => 'Fin de proyecto',
        'maintenance' => 'Mantenimiento',
        'other' => 'Otro',
    ];

    /**
     * Boot del modelo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($assignment) {
            if (empty($assignment->assignment_number)) {
                $assignment->assignment_number = self::generateAssignmentNumber();
            }
        });
    }

    /**
     * Generar número de asignación único
     */
    public static function generateAssignmentNumber(): string
    {
        $prefix = 'ASG';
        $year = date('Y');
        $lastAssignment = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastAssignment 
            ? (int) substr($lastAssignment->assignment_number, -6) + 1 
            : 1;

        return $prefix . $year . str_pad($sequence, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Equipo asignado
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * Empleado asignado
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Usuario que realizó la asignación
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Usuario que recibió la devolución
     */
    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Ubicación de uso
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Historial relacionado
     */
    public function history()
    {
        return $this->hasMany(EquipmentHistory::class);
    }

    /**
     * Scope para asignaciones activas
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope para asignaciones devueltas
     */
    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    /**
     * Verificar si está activa
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Verificar si tiene responsiva generada
     */
    public function hasCustodyLetter(): bool
    {
        return !empty($this->custody_letter_path);
    }

    /**
     * Obtener nombre del estado
     */
    public function getStatusNameAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Obtener nombre de condición al asignar
     */
    public function getConditionAtAssignmentNameAttribute(): string
    {
        return self::CONDITIONS[$this->condition_at_assignment] ?? $this->condition_at_assignment;
    }

    /**
     * Obtener nombre de condición al devolver
     */
    public function getConditionAtReturnNameAttribute(): ?string
    {
        return $this->condition_at_return 
            ? (self::CONDITIONS[$this->condition_at_return] ?? $this->condition_at_return)
            : null;
    }

    /**
     * Obtener nombre del motivo de devolución
     */
    public function getReturnReasonNameAttribute(): ?string
    {
        return $this->return_reason 
            ? (self::RETURN_REASONS[$this->return_reason] ?? $this->return_reason)
            : null;
    }

    /**
     * Obtener color de badge según estado
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'active' => 'success',
            'returned' => 'info',
            'transferred' => 'warning',
            'cancelled' => 'secondary',
            'lost' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Obtener duración de la asignación
     */
    public function getDurationAttribute(): string
    {
        $endDate = $this->actual_return_date ?? now();
        return $this->assignment_date->diffForHumans($endDate, true);
    }

    /**
     * Generar folio de responsiva
     */
    public function generateCustodyLetterFolio(): string
    {
        $prefix = 'RES';
        $year = date('Y');
        $sequence = str_pad($this->id, 6, '0', STR_PAD_LEFT);
        return "{$prefix}-{$year}-{$sequence}";
    }
}
