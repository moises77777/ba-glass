<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Brand;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\EquipmentHistory;
use App\Models\EquipmentImage;
use App\Models\Location;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::with(['category', 'brand', 'location', 'currentEmployee']);

        // Filtros
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('availability_status')) {
            $query->where('availability_status', $request->availability_status);
        }

        if ($request->filled('physical_condition')) {
            $query->where('physical_condition', $request->physical_condition);
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        // Ordenamiento
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $equipment = $query->paginate(15)->withQueryString();

        // Datos para filtros
        $categories = EquipmentCategory::active()->orderBy('name')->get();
        $brands = Brand::active()->orderBy('name')->get();
        $locations = Location::active()->orderBy('name')->get();

        return view('equipment.index', compact(
            'equipment',
            'categories',
            'brands',
            'locations'
        ));
    }

    public function create()
    {
        $categories = EquipmentCategory::active()->whereIn('name', ['Laptops', 'Equipos de Escritorio'])->orderBy('name')->get();
        $brands = Brand::active()->orderBy('name')->get();
        $suppliers = Supplier::active()->orderBy('name')->get();
        $locations = Location::active()->orderBy('name')->get();

        return view('equipment.create', compact('categories', 'brands', 'suppliers', 'locations'));
    }

    public function store(Request $request)
    {
        // Obtener categoría para verificar si requiere serial
        $category = EquipmentCategory::find($request->category_id);
        $requiresSerial = $category ? $category->requires_serial : true;

        $validated = $request->validate([
            'category_id' => 'required|exists:equipment_categories,id',
            'brand_id' => 'required|exists:brands,id',
            'model' => 'required|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'part_number' => 'nullable|string|max:100',
            'processor' => 'nullable|string|max:150',
            'ram' => 'nullable|string|max:50',
            'storage' => 'nullable|string|max:100',
            'storage_type' => 'nullable|string|max:50',
            'graphics_card' => 'nullable|string|max:150',
            'screen_size' => 'nullable|string|max:20',
            'screen_resolution' => 'nullable|string|max:30',
            'operating_system' => 'nullable|string|max:100',
            'os_version' => 'nullable|string|max:50',
            'os_license_key' => 'nullable|string|max:100',
            'mac_address' => 'nullable|string|max:17',
            'ip_address' => 'nullable|string|max:45',
            'hostname' => 'nullable|string|max:100',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'purchase_order' => 'nullable|string|max:50',
            'invoice_number' => 'nullable|string|max:50',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'warranty_start_date' => 'nullable|date',
            'warranty_end_date' => 'nullable|date|after_or_equal:warranty_start_date',
            'warranty_type' => 'nullable|string|max:100',
            'physical_condition' => 'required|in:excellent,good,fair,poor,damaged,for_repair',
            'operational_status' => 'required|in:operational,non_operational,under_repair,obsolete,pending_setup',
            'location_id' => 'nullable|exists:locations,id',
            'specific_location' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'observations' => 'nullable|string',
            'accessories' => 'nullable|string',
            'has_charger' => 'boolean',
            'charger_details' => 'nullable|string|max:150',
            'has_mouse' => 'boolean',
            'mouse_details' => 'nullable|string|max:150',
            'has_keyboard' => 'boolean',
            'has_power_strip' => 'boolean',
            'has_bag_case' => 'boolean',
            'adapters' => 'nullable|string|max:255',
            'other_accessories' => 'nullable|string|max:255',
        ]);

        // Generar código interno y etiqueta de activo automáticamente
        $count = Equipment::withTrashed()->count() + 1;
        $year = now()->format('y');
        $consecutive = str_pad($count, 4, '0', STR_PAD_LEFT);
        $validated['internal_code'] = 'EQ-' . $year . '-' . $consecutive;
        $validated['asset_tag'] = 'BA-' . $year . '-' . $consecutive;

        $validated['availability_status'] = 'available';
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        DB::beginTransaction();
        try {
            $equipment = Equipment::create($validated);

            // Registrar en historial
            EquipmentHistory::createEntry(
                $equipment,
                'data_update',
                'Equipo registrado en el sistema',
                'Nuevo equipo agregado al inventario'
            );

            // Auditoría
            AuditLog::log('create', $equipment, 'Equipo creado: ' . $equipment->internal_code);

            DB::commit();

            return redirect()
                ->route('equipment.show', $equipment)
                ->with('success', 'Equipo registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error al registrar el equipo: ' . $e->getMessage());
        }
    }

    public function show(Equipment $equipment)
    {
        $equipment->load([
            'category',
            'brand',
            'supplier',
            'location',
            'currentEmployee.department',
            'currentEmployee.position',
            'images',
            'activeAssignment',
            'creator',
            'updater',
        ]);

        $history = $equipment->history()
            ->with(['performedBy', 'previousEmployee', 'newEmployee'])
            ->orderByDesc('performed_at')
            ->limit(20)
            ->get();

        $assignments = $equipment->assignments()
            ->with(['employee', 'assignedBy'])
            ->orderByDesc('assignment_date')
            ->get();

        $maintenanceRecords = $equipment->maintenanceRecords()
            ->with(['reporter', 'assignee'])
            ->orderByDesc('reported_at')
            ->limit(10)
            ->get();

        // Generar código QR con la ruta relativa (funciona con localhost, ngrok o dominio propio)
        $qrPath = route('equipment.show', $equipment, false);
        $qrCode = base64_encode(
            \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(180)
                ->margin(1)
                ->generate($qrPath)
        );

        return view('equipment.show', compact('equipment', 'history', 'assignments', 'maintenanceRecords', 'qrCode'));
    }

    public function edit(Equipment $equipment)
    {
        $categories = EquipmentCategory::active()->whereIn('name', ['Laptops', 'Equipos de Escritorio'])->orderBy('name')->get();
        $brands = Brand::active()->orderBy('name')->get();
        $suppliers = Supplier::active()->orderBy('name')->get();
        $locations = Location::active()->orderBy('name')->get();

        return view('equipment.edit', compact('equipment', 'categories', 'brands', 'suppliers', 'locations'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        // Obtener categoría para verificar si requiere serial
        $category = EquipmentCategory::find($request->category_id);
        $requiresSerial = $category ? $category->requires_serial : true;

        $validated = $request->validate([
            'category_id' => 'required|exists:equipment_categories,id',
            'brand_id' => 'required|exists:brands,id',
            'model' => 'required|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'part_number' => 'nullable|string|max:100',
            'processor' => 'nullable|string|max:150',
            'ram' => 'nullable|string|max:50',
            'storage' => 'nullable|string|max:100',
            'storage_type' => 'nullable|string|max:50',
            'graphics_card' => 'nullable|string|max:150',
            'screen_size' => 'nullable|string|max:20',
            'screen_resolution' => 'nullable|string|max:30',
            'operating_system' => 'nullable|string|max:100',
            'os_version' => 'nullable|string|max:50',
            'os_license_key' => 'nullable|string|max:100',
            'mac_address' => 'nullable|string|max:17',
            'ip_address' => 'nullable|string|max:45',
            'hostname' => 'nullable|string|max:100',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'purchase_order' => 'nullable|string|max:50',
            'invoice_number' => 'nullable|string|max:50',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'warranty_start_date' => 'nullable|date',
            'warranty_end_date' => 'nullable|date|after_or_equal:warranty_start_date',
            'warranty_type' => 'nullable|string|max:100',
            'physical_condition' => 'required|in:excellent,good,fair,poor,damaged,for_repair',
            'operational_status' => 'required|in:operational,non_operational,under_repair,obsolete,pending_setup',
            'location_id' => 'nullable|exists:locations,id',
            'specific_location' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'observations' => 'nullable|string',
            'accessories' => 'nullable|string',
            'has_charger' => 'boolean',
            'charger_details' => 'nullable|string|max:150',
            'has_mouse' => 'boolean',
            'mouse_details' => 'nullable|string|max:150',
            'has_keyboard' => 'boolean',
            'has_power_strip' => 'boolean',
            'has_bag_case' => 'boolean',
            'adapters' => 'nullable|string|max:255',
            'other_accessories' => 'nullable|string|max:255',
        ]);

        $oldValues = $equipment->toArray();
        $validated['updated_by'] = auth()->id();

        DB::beginTransaction();
        try {
            // Detectar cambios importantes
            $changes = [];
            if ($equipment->physical_condition !== $validated['physical_condition']) {
                $changes['physical_condition'] = [
                    'old' => $equipment->physical_condition,
                    'new' => $validated['physical_condition'],
                ];
            }
            if ($equipment->operational_status !== $validated['operational_status']) {
                $changes['operational_status'] = [
                    'old' => $equipment->operational_status,
                    'new' => $validated['operational_status'],
                ];

                // Sincronizar availability_status según operational_status
                // (solo si no está asignado actualmente)
                if ($equipment->availability_status !== 'assigned') {
                    $validated['availability_status'] = match($validated['operational_status']) {
                        'under_repair'    => 'in_maintenance',
                        'non_operational' => 'in_maintenance',
                        'obsolete'        => 'retired',
                        'operational', 'pending_setup' => 'available',
                        default           => $equipment->availability_status,
                    };
                }
            }
            if ($equipment->location_id !== ($validated['location_id'] ?? null)) {
                $changes['location_id'] = [
                    'old' => $equipment->location_id,
                    'new' => $validated['location_id'] ?? null,
                ];
            }

            $equipment->update($validated);

            // Registrar cambios en historial
            if (!empty($changes)) {
                EquipmentHistory::createEntry(
                    $equipment,
                    'data_update',
                    'Datos del equipo actualizados',
                    'Se actualizaron los datos del equipo',
                    [
                        'changes' => $changes,
                        'previous_condition' => $oldValues['physical_condition'] ?? null,
                        'new_condition' => $validated['physical_condition'],
                        'previous_location_id' => $oldValues['location_id'] ?? null,
                        'new_location_id' => $validated['location_id'] ?? null,
                    ]
                );
            }

            // Auditoría
            AuditLog::log('update', $equipment, 'Equipo actualizado: ' . $equipment->internal_code, $oldValues, $validated);

            DB::commit();

            return redirect()
                ->route('equipment.show', $equipment)
                ->with('success', 'Equipo actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar el equipo: ' . $e->getMessage());
        }
    }

    public function destroy(Equipment $equipment)
    {
        if ($equipment->isAssigned()) {
            return back()->with('error', 'No se puede eliminar un equipo que está asignado.');
        }

        DB::beginTransaction();
        try {
            // Auditoría
            AuditLog::log('delete', $equipment, 'Equipo eliminado: ' . $equipment->internal_code);

            $equipment->delete();

            DB::commit();

            return redirect()
                ->route('equipment.index')
                ->with('success', 'Equipo eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar el equipo: ' . $e->getMessage());
        }
    }

    public function available()
    {
        $equipment = Equipment::with(['category', 'brand', 'location'])
            ->available()
            ->operational()
            ->orderBy('internal_code')
            ->get();

        return view('equipment.available', compact('equipment'));
    }

    /**
     * Página de escaneo de QR con la cámara
     */
    public function scan()
    {
        return view('equipment.scan');
    }

    /**
     * Búsqueda en tiempo real para AJAX
     */
    public function search(Request $request)
    {
        $query = Equipment::with(['category', 'brand', 'location', 'currentEmployee']);

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('availability_status')) {
            $query->where('availability_status', $request->availability_status);
        }

        $equipment = $query->orderBy('internal_code')->paginate(15);

        return response()->json([
            'data' => view('equipment._table_rows', compact('equipment'))->render(),
            'pagination' => $equipment->links()->toHtml(),
            'total' => $equipment->total(),
        ]);
    }
}
