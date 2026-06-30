<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipment';

    protected $fillable = [
        'internal_code',
        'asset_tag',
        'category_id',
        'brand_id',
        'equipment_model_id',
        'model',
        'serial_number',
        'part_number',
        'processor',
        'ram',
        'storage',
        'storage_type',
        'graphics_card',
        'screen_size',
        'screen_resolution',
        'operating_system',
        'os_version',
        'os_license_key',
        'mac_address',
        'ip_address',
        'hostname',
        'supplier_id',
        'purchase_order',
        'invoice_number',
        'purchase_date',
        'purchase_price',
        'currency',
        'warranty_start_date',
        'warranty_end_date',
        'warranty_type',
        'physical_condition',
        'operational_status',
        'availability_status',
        'location_id',
        'specific_location',
        'current_employee_id',
        'assignment_date',
        'delivery_date',
        'last_maintenance_date',
        'next_maintenance_date',
        'retirement_date',
        'description',
        'observations',
        'accessories',
        'has_charger', 'charger_details',
        'has_mouse', 'mouse_details',
        'has_keyboard', 'has_power_strip', 'has_bag_case',
        'adapters', 'other_accessories',
        'custom_fields',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_start_date' => 'date',
        'warranty_end_date' => 'date',
        'assignment_date' => 'date',
        'delivery_date' => 'date',
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'retirement_date' => 'date',
        'purchase_price' => 'decimal:2',
        'has_charger' => 'boolean',
        'has_mouse' => 'boolean',
        'has_keyboard' => 'boolean',
        'has_power_strip' => 'boolean',
        'has_bag_case' => 'boolean',
        'custom_fields' => 'array',
    ];

    const PHYSICAL_CONDITIONS = [
        'excellent' => 'Excelente',
        'good' => 'Bueno',
        'fair' => 'Regular',
        'poor' => 'Malo',
        'damaged' => 'Dañado',
        'for_repair' => 'Para reparación',
    ];

    const OPERATIONAL_STATUSES = [
        'operational' => 'Operativo',
        'non_operational' => 'No operativo',
        'under_repair' => 'En reparación',
        'obsolete' => 'Obsoleto',
        'pending_setup' => 'Pendiente de configurar',
    ];

    const AVAILABILITY_STATUSES = [
        'available' => 'Disponible',
        'assigned' => 'Asignado',
        'in_maintenance' => 'En mantenimiento',
        'retired' => 'Dado de baja',
        'lost' => 'Extraviado',
        'stolen' => 'Robado',
    ];

    /**
     * Categoría del equipo
     */
    public function category()
    {
        return $this->belongsTo(EquipmentCategory::class, 'category_id');
    }

    /**
     * Marca del equipo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Proveedor
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Ubicación
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Empleado actual asignado
     */
    public function currentEmployee()
    {
        return $this->belongsTo(Employee::class, 'current_employee_id');
    }

    /**
     * Usuario que creó el registro
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Usuario que actualizó el registro
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Imágenes del equipo
     */
    public function images()
    {
        return $this->hasMany(EquipmentImage::class);
    }

    /**
     * Imagen principal
     */
    public function primaryImage()
    {
        return $this->hasOne(EquipmentImage::class)->where('is_primary', true);
    }

    /**
     * Todas las asignaciones
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Asignación activa
     */
    public function activeAssignment()
    {
        return $this->hasOne(Assignment::class)->where('status', 'active');
    }

    /**
     * Historial de movimientos
     */
    public function history()
    {
        return $this->hasMany(EquipmentHistory::class)->orderBy('performed_at', 'desc');
    }

    /**
     * Registros de mantenimiento
     */
    public function maintenanceRecords()
    {
        return $this->hasMany(MaintenanceRecord::class);
    }

    /**
     * Scope para equipos disponibles
     */
    public function scopeAvailable($query)
    {
        return $query->where('availability_status', 'available')
            ->whereNotIn('physical_condition', ['damaged', 'for_repair']);
    }

    /**
     * Scope para equipos asignados
     */
    public function scopeAssigned($query)
    {
        return $query->where('availability_status', 'assigned');
    }

    /**
     * Scope para equipos operativos
     */
    public function scopeOperational($query)
    {
        return $query->where('operational_status', 'operational');
    }

    /**
     * Scope para búsqueda
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('internal_code', 'LIKE', "%{$search}%")
              ->orWhere('serial_number', 'LIKE', "%{$search}%")
              ->orWhere('model', 'LIKE', "%{$search}%")
              ->orWhere('asset_tag', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Verificar si está disponible
     */
    public function isAvailable(): bool
    {
        return $this->availability_status === 'available'
            && !in_array($this->physical_condition, ['damaged', 'for_repair']);
    }

    /**
     * Verificar si está asignado
     */
    public function isAssigned(): bool
    {
        return $this->availability_status === 'assigned';
    }

    /**
     * Verificar si la garantía está vigente
     */
    public function hasActiveWarranty(): bool
    {
        return $this->warranty_end_date && $this->warranty_end_date->isFuture();
    }

    /**
     * Obtener días restantes de garantía
     */
    public function getWarrantyDaysRemainingAttribute(): ?int
    {
        if (!$this->warranty_end_date) {
            return null;
        }
        return max(0, now()->diffInDays($this->warranty_end_date, false));
    }

    /**
     * Obtener nombre de condición física
     */
    public function getPhysicalConditionNameAttribute(): string
    {
        return self::PHYSICAL_CONDITIONS[$this->physical_condition] ?? $this->physical_condition;
    }

    /**
     * Obtener nombre de estado operativo
     */
    public function getOperationalStatusNameAttribute(): string
    {
        return self::OPERATIONAL_STATUSES[$this->operational_status] ?? $this->operational_status;
    }

    /**
     * Obtener nombre de disponibilidad
     */
    public function getAvailabilityStatusNameAttribute(): string
    {
        return self::AVAILABILITY_STATUSES[$this->availability_status] ?? $this->availability_status;
    }

    /**
     * Obtener nombre completo del equipo
     */
    public function getFullNameAttribute(): string
    {
        $parts = array_filter([
            $this->brand?->name,
            $this->model,
        ]);
        return implode(' ', $parts) ?: $this->internal_code;
    }

    /**
     * Obtener especificaciones resumidas
     */
    public function getSpecsSummaryAttribute(): string
    {
        $specs = array_filter([
            $this->processor,
            $this->ram,
            $this->storage,
        ]);
        return implode(' | ', $specs);
    }

    /**
     * Obtener URL de imagen principal
     */
    public function getPrimaryImageUrlAttribute(): ?string
    {
        $image = $this->primaryImage ?? $this->images->first();
        return $image ? asset('storage/' . $image->path) : null;
    }

    /**
     * Obtener color de badge según disponibilidad
     */
    public function getAvailabilityBadgeColorAttribute(): string
    {
        return match($this->availability_status) {
            'available' => 'success',
            'assigned' => 'primary',
            'in_maintenance' => 'warning',
            'retired' => 'secondary',
            'lost', 'stolen' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Obtener color de badge según condición
     */
    public function getConditionBadgeColorAttribute(): string
    {
        return match($this->physical_condition) {
            'excellent' => 'success',
            'good' => 'info',
            'fair' => 'warning',
            'poor', 'damaged', 'for_repair' => 'danger',
            default => 'secondary',
        };
    }
}
