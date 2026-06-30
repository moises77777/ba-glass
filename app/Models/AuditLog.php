<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'action',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'description',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'metadata',
        'tags',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    const ACTIONS = [
        'create' => 'Crear',
        'update' => 'Actualizar',
        'delete' => 'Eliminar',
        'restore' => 'Restaurar',
        'login' => 'Iniciar sesión',
        'logout' => 'Cerrar sesión',
        'export' => 'Exportar',
        'import' => 'Importar',
        'print' => 'Imprimir',
        'download' => 'Descargar',
        'view' => 'Ver',
        'search' => 'Buscar',
        'assign' => 'Asignar',
        'unassign' => 'Desasignar',
        'transfer' => 'Transferir',
        'approve' => 'Aprobar',
        'reject' => 'Rechazar',
        'other' => 'Otro',
    ];

    /**
     * Usuario que realizó la acción
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Modelo auditado
     */
    public function auditable()
    {
        return $this->morphTo();
    }

    /**
     * Scope por acción
     */
    public function scopeOfAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope por modelo
     */
    public function scopeForModel($query, $model)
    {
        return $query->where('auditable_type', $model);
    }

    /**
     * Scope por usuario
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para registros recientes
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Obtener nombre de la acción
     */
    public function getActionNameAttribute(): string
    {
        return self::ACTIONS[$this->action] ?? $this->action;
    }

    /**
     * Obtener nombre del modelo auditado
     */
    public function getAuditableNameAttribute(): string
    {
        $modelNames = [
            'App\\Models\\User' => 'Usuario',
            'App\\Models\\Employee' => 'Empleado',
            'App\\Models\\Equipment' => 'Equipo',
            'App\\Models\\Assignment' => 'Asignación',
            'App\\Models\\Department' => 'Departamento',
            'App\\Models\\Position' => 'Puesto',
            'App\\Models\\Brand' => 'Marca',
            'App\\Models\\Supplier' => 'Proveedor',
            'App\\Models\\Location' => 'Ubicación',
            'App\\Models\\MaintenanceRecord' => 'Mantenimiento',
        ];

        return $modelNames[$this->auditable_type] ?? class_basename($this->auditable_type);
    }

    /**
     * Obtener icono según acción
     */
    public function getActionIconAttribute(): string
    {
        return match($this->action) {
            'create' => 'bi-plus-circle',
            'update' => 'bi-pencil',
            'delete' => 'bi-trash',
            'restore' => 'bi-arrow-counterclockwise',
            'login' => 'bi-box-arrow-in-right',
            'logout' => 'bi-box-arrow-left',
            'export' => 'bi-download',
            'import' => 'bi-upload',
            'print' => 'bi-printer',
            'download' => 'bi-file-earmark-arrow-down',
            'view' => 'bi-eye',
            'assign' => 'bi-person-plus',
            'unassign' => 'bi-person-dash',
            'transfer' => 'bi-arrow-left-right',
            default => 'bi-clock-history',
        };
    }

    /**
     * Obtener color según acción
     */
    public function getActionColorAttribute(): string
    {
        return match($this->action) {
            'create' => 'success',
            'update' => 'info',
            'delete' => 'danger',
            'restore' => 'warning',
            'login' => 'primary',
            'logout' => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Crear registro de auditoría
     */
    public static function log(
        string $action,
        ?Model $model = null,
        ?string $description = null,
        array $oldValues = [],
        array $newValues = [],
        array $metadata = []
    ): self {
        $user = auth()->user();

        return self::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'user_email' => $user?->email,
            'action' => $action,
            'auditable_type' => $model ? get_class($model) : null,
            'auditable_id' => $model?->id,
            'old_values' => $oldValues ?: null,
            'new_values' => $newValues ?: null,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'metadata' => $metadata ?: null,
            'created_at' => now(),
        ]);
    }
}
