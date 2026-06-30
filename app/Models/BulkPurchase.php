<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BulkPurchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bulk_purchases';

    protected $fillable = [
        'folio',
        'equipment_model_id',
        'supplier_id',
        'category_id',
        'brand_id',
        'model_name',
        'quantity',
        'unit_price',
        'currency',
        'purchase_order',
        'invoice_number',
        'purchase_date',
        'warranty_start_date',
        'warranty_end_date',
        'warranty_type',
        'location_id',
        'physical_condition',
        'operational_status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_start_date' => 'date',
        'warranty_end_date' => 'date',
        'unit_price' => 'decimal:2',
    ];

    public function equipmentModel()
    {
        return $this->belongsTo(EquipmentModel::class, 'equipment_model_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category()
    {
        return $this->belongsTo(EquipmentCategory::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateFolio(): string
    {
        $year = date('Y');
        $last = static::withTrashed()
            ->where('folio', 'like', "COMP{$year}%")
            ->orderByDesc('folio')
            ->value('folio');
        $seq = $last ? (intval(substr($last, 8)) + 1) : 1;
        return 'COMP' . $year . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
