<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\BulkPurchase;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\EquipmentHistory;
use App\Models\EquipmentModel;
use App\Models\Location;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BulkPurchaseController extends Controller
{
    public function index()
    {
        $purchases = BulkPurchase::with(['equipmentModel.brand', 'supplier', 'category', 'brand'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('bulk-purchases.index', compact('purchases'));
    }

    public function create()
    {
        $equipmentModels = EquipmentModel::with('brand')->active()->orderBy('name')->get();
        $categories      = EquipmentCategory::whereIn('code', ['LAPTOP', 'DESKTOP'])->orderBy('sort_order')->get();
        $brands          = Brand::active()->orderBy('name')->get();
        $suppliers       = Supplier::active()->orderBy('name')->get();
        $locations       = Location::active()->orderBy('name')->get();

        return view('bulk-purchases.create', compact(
            'equipmentModels', 'categories', 'brands', 'suppliers', 'locations'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_model_id'  => 'nullable|exists:equipment_models,id',
            'supplier_id'         => 'nullable|exists:suppliers,id',
            'category_id'         => 'required|exists:equipment_categories,id',
            'brand_id'            => 'required|exists:brands,id',
            'model_name'          => 'required|string|max:150',
            'unit_price'          => 'nullable|numeric|min:0',
            'currency'            => 'nullable|string|max:3',
            'purchase_order'      => 'nullable|string|max:50',
            'invoice_number'      => 'nullable|string|max:50',
            'purchase_date'       => 'nullable|date',
            'warranty_start_date' => 'nullable|date',
            'warranty_end_date'   => 'nullable|date',
            'warranty_type'       => 'nullable|string|max:100',
            'location_id'         => 'nullable|exists:locations,id',
            'physical_condition'  => 'required|string',
            'operational_status'  => 'required|string',
            'notes'               => 'nullable|string',
            'items'               => 'required|array|min:1',
            'items.*.serial_number'   => 'nullable|string|max:100',
            'items.*.asset_tag'       => 'nullable|string|max:100',
            'items.*.internal_code'   => 'nullable|string|max:50',
            'items.*.specific_location' => 'nullable|string|max:100',
            'items.*.observations'    => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $folio = BulkPurchase::generateFolio();

            $purchase = BulkPurchase::create([
                'folio'               => $folio,
                'equipment_model_id'  => $validated['equipment_model_id'] ?? null,
                'supplier_id'         => $validated['supplier_id'] ?? null,
                'category_id'         => $validated['category_id'],
                'brand_id'            => $validated['brand_id'],
                'model_name'          => $validated['model_name'],
                'quantity'            => count($validated['items']),
                'unit_price'          => $validated['unit_price'] ?? 0,
                'currency'            => $validated['currency'] ?? 'MXN',
                'purchase_order'      => $validated['purchase_order'] ?? null,
                'invoice_number'      => $validated['invoice_number'] ?? null,
                'purchase_date'       => $validated['purchase_date'] ?? null,
                'warranty_start_date' => $validated['warranty_start_date'] ?? null,
                'warranty_end_date'   => $validated['warranty_end_date'] ?? null,
                'warranty_type'       => $validated['warranty_type'] ?? null,
                'location_id'         => $validated['location_id'] ?? null,
                'physical_condition'  => $validated['physical_condition'],
                'operational_status'  => $validated['operational_status'],
                'notes'               => $validated['notes'] ?? null,
                'created_by'          => auth()->id(),
            ]);

            foreach ($validated['items'] as $index => $item) {
                $internalCode = !empty($item['internal_code'])
                    ? $item['internal_code']
                    : $this->generateInternalCode();

                $equipment = Equipment::create([
                    'internal_code'       => $internalCode,
                    'asset_tag'           => $item['asset_tag'] ?? null,
                    'category_id'         => $validated['category_id'],
                    'brand_id'            => $validated['brand_id'],
                    'equipment_model_id'  => $validated['equipment_model_id'] ?? null,
                    'model'               => $validated['model_name'],
                    'serial_number'       => $item['serial_number'] ?? null,
                    'supplier_id'         => $validated['supplier_id'] ?? null,
                    'purchase_order'      => $validated['purchase_order'] ?? null,
                    'invoice_number'      => $validated['invoice_number'] ?? null,
                    'purchase_date'       => $validated['purchase_date'] ?? null,
                    'purchase_price'      => $validated['unit_price'] ?? 0,
                    'currency'            => $validated['currency'] ?? 'MXN',
                    'warranty_start_date' => $validated['warranty_start_date'] ?? null,
                    'warranty_end_date'   => $validated['warranty_end_date'] ?? null,
                    'warranty_type'       => $validated['warranty_type'] ?? null,
                    'location_id'         => $validated['location_id'] ?? null,
                    'specific_location'   => $item['specific_location'] ?? null,
                    'physical_condition'  => $validated['physical_condition'],
                    'operational_status'  => $validated['operational_status'],
                    'availability_status' => 'available',
                    'observations'        => $item['observations'] ?? null,
                    'created_by'          => auth()->id(),
                ]);

                EquipmentHistory::createEntry(
                    $equipment,
                    'other',
                    'Equipo registrado por compra masiva',
                    "Folio: {$folio} | Unidad " . ($index + 1) . " de " . count($validated['items']),
                    []
                );
            }

            DB::commit();

            return redirect()->route('bulk-purchases.show', $purchase)
                ->with('success', "Compra {$folio} registrada: " . count($validated['items']) . " equipos creados.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al registrar la compra: ' . $e->getMessage());
        }
    }

    public function show(BulkPurchase $bulkPurchase)
    {
        $bulkPurchase->load(['equipmentModel.brand', 'supplier', 'category', 'brand', 'createdBy']);

        $equipment = Equipment::where('purchase_order', $bulkPurchase->purchase_order)
            ->where('model', $bulkPurchase->model_name)
            ->where('brand_id', $bulkPurchase->brand_id)
            ->get();

        return view('bulk-purchases.show', compact('bulkPurchase', 'equipment'));
    }

    private function generateInternalCode(): string
    {
        $year = date('Y');
        $last = Equipment::withTrashed()
            ->where('internal_code', 'like', "EQ-{$year}-%")
            ->orderByDesc('internal_code')
            ->value('internal_code');
        $seq = $last ? (intval(substr($last, 8)) + 1) : 1;
        return 'EQ-' . $year . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
