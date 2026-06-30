<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'equipment_id',
        'type',
        'status',
        'priority',
        'reported_at',
        'started_at',
        'completed_at',
        'scheduled_date',
        'title',
        'problem_description',
        'diagnosis',
        'solution',
        'parts_replaced',
        'labor_cost',
        'parts_cost',
        'total_cost',
        'supplier_id',
        'technician_name',
        'technician_phone',
        'reported_by',
        'assigned_to',
        'completed_by',
        'condition_before',
        'condition_after',
        'notes',
        'attachments',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'scheduled_date' => 'datetime',
        'labor_cost' => 'decimal:2',
        'parts_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'attachments' => 'array',
    ];

    const TYPES = [
        'preventive' => 'Preventivo',
        'corrective' => 'Correctivo',
        'upgrade' => 'Actualización',
        'cleaning' => 'Limpieza',
        'inspection' => 'Inspección',
        'other' => 'Otro',
    ];

    const STATUSES = [
        'pending' => 'Pendiente',
        'in_progress' => 'En progreso',
        'completed' => 'Completado',
        'cancelled' => 'Cancelado',
        'on_hold' => 'En espera',
    ];

    const PRIORITIES = [
        'low' => 'Baja',
        'medium' => 'Media',
        'high' => 'Alta',
        'critical' => 'Crítica',
    ];

    /**
     * Boot del modelo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($record) {
            if (empty($record->ticket_number)) {
                $record->ticket_number = self::generateTicketNumber();
            }
        });
    }

    /**
     * Generar número de ticket único
     */
    public static function generateTicketNumber(): string
    {
        $prefix = 'MNT';
        $year = date('Y');

        do {
            $lastRecord = self::withTrashed()
                ->whereYear('created_at', $year)
                ->orderBy('id', 'desc')
                ->first();

            $sequence = $lastRecord
                ? (int) substr($lastRecord->ticket_number, -6) + 1
                : 1;

            $ticket = $prefix . $year . str_pad($sequence, 6, '0', STR_PAD_LEFT);
        } while (self::withTrashed()->where('ticket_number', $ticket)->exists());

        return $ticket;
    }

    /**
     * Equipo en mantenimiento
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * Proveedor de servicio
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Usuario que reportó
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Usuario asignado
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Usuario que completó
     */
    public function completer()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    /**
     * Scope para registros pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para registros en progreso
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope para registros completados
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Obtener nombre del tipo
     */
    public function getTypeNameAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    /**
     * Obtener nombre del estado
     */
    public function getStatusNameAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Obtener nombre de la prioridad
     */
    public function getPriorityNameAttribute(): string
    {
        return self::PRIORITIES[$this->priority] ?? $this->priority;
    }

    /**
     * Obtener color de badge según estado
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'in_progress' => 'info',
            'completed' => 'success',
            'cancelled' => 'secondary',
            'on_hold' => 'dark',
            default => 'secondary',
        };
    }

    /**
     * Obtener color de badge según prioridad
     */
    public function getPriorityBadgeColorAttribute(): string
    {
        return match($this->priority) {
            'low' => 'secondary',
            'medium' => 'info',
            'high' => 'warning',
            'critical' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Calcular costo total
     */
    public function calculateTotalCost(): void
    {
        $this->total_cost = ($this->labor_cost ?? 0) + ($this->parts_cost ?? 0);
        $this->save();
    }
}
