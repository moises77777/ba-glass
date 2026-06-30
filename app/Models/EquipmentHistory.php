<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentHistory extends Model
{
    use HasFactory;

    protected $table = 'equipment_history';

    protected $fillable = [
        'equipment_id',
        'assignment_id',
        'movement_type',
        'previous_employee_id',
        'new_employee_id',
        'previous_location_id',
        'new_location_id',
        'previous_status',
        'new_status',
        'previous_condition',
        'new_condition',
        'title',
        'description',
        'reason',
        'changes',
        'metadata',
        'performed_by',
        'ip_address',
        'user_agent',
        'performed_at',
    ];

    protected $casts = [
        'changes' => 'array',
        'metadata' => 'array',
        'performed_at' => 'datetime',
    ];

    const MOVEMENT_TYPES = [
        'assignment' => 'Asignación',
        'return' => 'Devolución',
        'transfer' => 'Transferencia',
        'location_change' => 'Cambio de ubicación',
        'status_change' => 'Cambio de estado',
        'maintenance_start' => 'Inicio de mantenimiento',
        'maintenance_end' => 'Fin de mantenimiento',
        'condition_update' => 'Actualización de condición',
        'retirement' => 'Baja',
        'reactivation' => 'Reactivación',
        'data_update' => 'Actualización de datos',
        'image_added' => 'Imagen agregada',
        'document_added' => 'Documento agregado',
        'other' => 'Otro',
    ];

    /**
     * Equipo relacionado
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * Asignación relacionada
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Empleado anterior
     */
    public function previousEmployee()
    {
        return $this->belongsTo(Employee::class, 'previous_employee_id');
    }

    /**
     * Nuevo empleado
     */
    public function newEmployee()
    {
        return $this->belongsTo(Employee::class, 'new_employee_id');
    }

    /**
     * Ubicación anterior
     */
    public function previousLocation()
    {
        return $this->belongsTo(Location::class, 'previous_location_id');
    }

    /**
     * Nueva ubicación
     */
    public function newLocation()
    {
        return $this->belongsTo(Location::class, 'new_location_id');
    }

    /**
     * Usuario que realizó el movimiento
     */
    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    /**
     * Scope por tipo de movimiento
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('movement_type', $type);
    }

    /**
     * Scope para movimientos recientes
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('performed_at', '>=', now()->subDays($days));
    }

    /**
     * Obtener nombre del tipo de movimiento
     */
    public function getMovementTypeNameAttribute(): string
    {
        return self::MOVEMENT_TYPES[$this->movement_type] ?? $this->movement_type;
    }

    /**
     * Obtener icono según tipo de movimiento
     */
    public function getMovementIconAttribute(): string
    {
        return match($this->movement_type) {
            'assignment' => 'bi-person-plus',
            'return' => 'bi-box-arrow-in-left',
            'transfer' => 'bi-arrow-left-right',
            'location_change' => 'bi-geo-alt',
            'status_change' => 'bi-toggle-on',
            'maintenance_start' => 'bi-tools',
            'maintenance_end' => 'bi-check-circle',
            'condition_update' => 'bi-clipboard-check',
            'retirement' => 'bi-x-circle',
            'reactivation' => 'bi-arrow-repeat',
            'data_update' => 'bi-pencil',
            'image_added' => 'bi-image',
            'document_added' => 'bi-file-earmark',
            default => 'bi-clock-history',
        };
    }

    /**
     * Obtener color según tipo de movimiento
     */
    public function getMovementColorAttribute(): string
    {
        return match($this->movement_type) {
            'assignment' => 'success',
            'return' => 'info',
            'transfer' => 'warning',
            'retirement' => 'danger',
            'maintenance_start' => 'warning',
            'maintenance_end' => 'success',
            default => 'secondary',
        };
    }

    /**
     * Crear registro de historial
     */
    public static function createEntry(
        Equipment $equipment,
        string $movementType,
        string $title,
        ?string $description = null,
        array $additionalData = []
    ): self {
        return self::create(array_merge([
            'equipment_id' => $equipment->id,
            'movement_type' => $movementType,
            'title' => $title,
            'description' => $description,
            'performed_by' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'performed_at' => now(),
        ], $additionalData));
    }
}
