<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EquipmentApiController extends Controller
{
    public function index(Request $request): JsonResponse
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

        $equipment = $query->orderBy('internal_code')->paginate($request->get('per_page', 15));

        return response()->json($equipment);
    }

    public function available(): JsonResponse
    {
        $equipment = Equipment::with(['category', 'brand'])
            ->available()
            ->operational()
            ->orderBy('internal_code')
            ->get();

        return response()->json($equipment);
    }

    public function assigned(): JsonResponse
    {
        $equipment = Equipment::with(['category', 'brand', 'currentEmployee.department'])
            ->assigned()
            ->orderBy('internal_code')
            ->get();

        return response()->json($equipment);
    }

    public function stats(): JsonResponse
    {
        return response()->json([
            'total' => Equipment::count(),
            'available' => Equipment::where('availability_status', 'available')->count(),
            'assigned' => Equipment::where('availability_status', 'assigned')->count(),
            'in_maintenance' => Equipment::where('availability_status', 'in_maintenance')->count(),
            'retired' => Equipment::where('availability_status', 'retired')->count(),
            'by_condition' => Equipment::selectRaw('physical_condition, count(*) as total')
                ->groupBy('physical_condition')
                ->pluck('total', 'physical_condition'),
        ]);
    }

    public function show(Equipment $equipment): JsonResponse
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
        ]);

        return response()->json($equipment);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'internal_code' => 'required|string|max:50|unique:equipment',
            'category_id' => 'required|exists:equipment_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'model' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'physical_condition' => 'required|in:excellent,good,fair,poor,damaged,for_repair',
            'operational_status' => 'required|in:operational,non_operational,under_repair,obsolete,pending_setup',
        ]);

        $validated['availability_status'] = 'available';
        $validated['created_by'] = auth()->id();

        $equipment = Equipment::create($validated);

        return response()->json($equipment, 201);
    }

    public function update(Request $request, Equipment $equipment): JsonResponse
    {
        $validated = $request->validate([
            'internal_code' => 'required|string|max:50|unique:equipment,internal_code,' . $equipment->id,
            'category_id' => 'required|exists:equipment_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'model' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'physical_condition' => 'required|in:excellent,good,fair,poor,damaged,for_repair',
            'operational_status' => 'required|in:operational,non_operational,under_repair,obsolete,pending_setup',
        ]);

        $validated['updated_by'] = auth()->id();

        $equipment->update($validated);

        return response()->json($equipment);
    }

    public function destroy(Equipment $equipment): JsonResponse
    {
        if ($equipment->isAssigned()) {
            return response()->json(['error' => 'No se puede eliminar un equipo asignado'], 422);
        }

        $equipment->delete();

        return response()->json(['message' => 'Equipo eliminado correctamente']);
    }

    public function history(Equipment $equipment): JsonResponse
    {
        $history = $equipment->history()
            ->with(['performedBy', 'previousEmployee', 'newEmployee'])
            ->orderByDesc('performed_at')
            ->paginate(20);

        return response()->json($history);
    }
}
