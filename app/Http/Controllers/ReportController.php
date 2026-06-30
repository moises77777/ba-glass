<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\EquipmentHistory;
use App\Models\MaintenanceRecord;
use App\Exports\EquipmentExport;
use App\Exports\EquipmentByEmployeeExport;
use App\Exports\MovementHistoryExport;
use App\Exports\WarrantyExpiringExport;
use App\Exports\MaintenanceExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function equipmentSummary(Request $request)
    {
        $query = Equipment::with(['category', 'brand', 'location', 'currentEmployee.department']);

        // Filtros
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

        $equipment = $query->orderBy('internal_code')->get();

        // Estadísticas
        $stats = [
            'total' => $equipment->count(),
            'by_status' => $equipment->groupBy('availability_status')->map->count(),
            'by_condition' => $equipment->groupBy('physical_condition')->map->count(),
            'by_category' => $equipment->groupBy('category.name')->map->count(),
            'total_value' => $equipment->sum('purchase_price'),
        ];

        $categories = EquipmentCategory::active()->orderBy('name')->get();

        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.equipment-summary', compact('equipment', 'stats'));
            $pdf->setPaper('letter', 'landscape');
            return $pdf->download('reporte_equipos_' . date('Y-m-d') . '.pdf');
        }

        if ($request->has('export') && $request->export === 'excel') {
            return Excel::download(new EquipmentExport($equipment), 'reporte_equipos_' . date('Y-m-d') . '.xlsx');
        }

        return view('reports.equipment-summary', compact('equipment', 'stats', 'categories'));
    }

    public function assignedEquipment(Request $request)
    {
        $query = Equipment::with(['category', 'brand', 'currentEmployee.department', 'currentEmployee.position', 'activeAssignment'])
            ->where('availability_status', 'assigned');

        if ($request->filled('department_id')) {
            $query->whereHas('currentEmployee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $equipment = $query->orderBy('internal_code')->get();

        $departments = Department::active()->orderBy('name')->get();
        $categories = EquipmentCategory::active()->orderBy('name')->get();

        // Agrupar por departamento
        $byDepartment = $equipment->groupBy(function ($item) {
            return $item->currentEmployee?->department?->name ?? 'Sin departamento';
        });

        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.assigned-equipment', compact('equipment', 'byDepartment'));
            $pdf->setPaper('letter', 'landscape');
            return $pdf->download('equipos_asignados_' . date('Y-m-d') . '.pdf');
        }

        if ($request->has('export') && $request->export === 'excel') {
            return Excel::download(new EquipmentExport($equipment), 'equipos_asignados_' . date('Y-m-d') . '.xlsx');
        }

        return view('reports.assigned-equipment', compact('equipment', 'byDepartment', 'departments', 'categories'));
    }

    public function availableEquipment(Request $request)
    {
        $query = Equipment::with(['category', 'brand', 'location'])
            ->where('availability_status', 'available');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('operational_status')) {
            $query->where('operational_status', $request->operational_status);
        }

        $equipment = $query->orderBy('internal_code')->get();

        $categories = EquipmentCategory::active()->orderBy('name')->get();

        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.available-equipment', compact('equipment'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->download('equipos_disponibles_' . date('Y-m-d') . '.pdf');
        }

        if ($request->has('export') && $request->export === 'excel') {
            return Excel::download(new EquipmentExport($equipment), 'equipos_disponibles_' . date('Y-m-d') . '.xlsx');
        }

        return view('reports.available-equipment', compact('equipment', 'categories'));
    }

    public function equipmentByEmployee(Request $request)
    {
        $query = Employee::with(['department', 'position', 'currentEquipment.category', 'currentEquipment.brand'])
            ->has('currentEquipment');

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $employees = $query->orderBy('first_name')->get();

        $departments = Department::active()->orderBy('name')->get();

        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.equipment-by-employee', compact('employees'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->download('equipos_por_empleado_' . date('Y-m-d') . '.pdf');
        }

        if ($request->has('export') && $request->export === 'excel') {
            return Excel::download(new EquipmentByEmployeeExport($employees), 'equipos_por_empleado_' . date('Y-m-d') . '.xlsx');
        }

        return view('reports.equipment-by-employee', compact('employees', 'departments'));
    }

    public function equipmentByDepartment(Request $request)
    {
        $departments = Department::with(['employees.currentEquipment.category', 'employees.currentEquipment.brand'])
            ->withCount(['employees' => function ($q) {
                $q->where('status', 'active');
            }])
            ->orderBy('name')
            ->get();

        // Calcular equipos por departamento
        $departmentStats = $departments->map(function ($dept) {
            $equipment = $dept->employees->flatMap->currentEquipment;
            return [
                'department' => $dept,
                'total_equipment' => $equipment->count(),
                'by_category' => $equipment->groupBy('category.name')->map->count(),
                'total_value' => $equipment->sum('purchase_price'),
            ];
        });

        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.equipment-by-department', compact('departmentStats'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->download('equipos_por_departamento_' . date('Y-m-d') . '.pdf');
        }

        return view('reports.equipment-by-department', compact('departmentStats'));
    }

    public function movementHistory(Request $request)
    {
        $query = EquipmentHistory::with([
            'equipment.category',
            'performedBy',
            'previousEmployee',
            'newEmployee',
        ]);

        if ($request->filled('date_from')) {
            $query->whereDate('performed_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('performed_at', '<=', $request->date_to);
        }

        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }

        $history = $query->orderByDesc('performed_at')->paginate(50)->withQueryString();

        // Estadísticas
        $stats = [
            'total_movements' => EquipmentHistory::count(),
            'by_type' => EquipmentHistory::select('movement_type', DB::raw('count(*) as total'))
                ->groupBy('movement_type')
                ->pluck('total', 'movement_type'),
        ];

        $movementTypes = EquipmentHistory::MOVEMENT_TYPES;

        if ($request->has('export') && $request->export === 'pdf') {
            $allHistory = $query->orderByDesc('performed_at')->get();
            $pdf = Pdf::loadView('reports.pdf.movement-history', compact('allHistory', 'stats'));
            $pdf->setPaper('letter', 'landscape');
            return $pdf->download('historial_movimientos_' . date('Y-m-d') . '.pdf');
        }

        if ($request->has('export') && $request->export === 'excel') {
            $allHistory = $query->orderByDesc('performed_at')->get();
            return Excel::download(new MovementHistoryExport($allHistory), 'historial_movimientos_' . date('Y-m-d') . '.xlsx');
        }

        return view('reports.movement-history', compact('history', 'stats', 'movementTypes'));
    }

    public function warrantyExpiring(Request $request)
    {
        $days = $request->get('days', 30);

        $equipment = Equipment::with(['category', 'brand', 'currentEmployee'])
            ->whereNotNull('warranty_end_date')
            ->where('warranty_end_date', '<=', now()->addDays($days))
            ->where('warranty_end_date', '>=', now())
            ->orderBy('warranty_end_date')
            ->get();

        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.warranty-expiring', compact('equipment', 'days'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->download('garantias_por_vencer_' . date('Y-m-d') . '.pdf');
        }

        if ($request->has('export') && $request->export === 'excel') {
            return Excel::download(new WarrantyExpiringExport($equipment), 'garantias_por_vencer_' . date('Y-m-d') . '.xlsx');
        }

        return view('reports.warranty-expiring', compact('equipment', 'days'));
    }

    public function maintenanceReport(Request $request)
    {
        $query = MaintenanceRecord::with(['equipment.category', 'reporter', 'assignee', 'supplier']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('reported_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('reported_at', '<=', $request->date_to);
        }

        $records = $query->orderByDesc('reported_at')->paginate(30)->withQueryString();

        // Estadísticas
        $stats = [
            'total' => MaintenanceRecord::count(),
            'pending' => MaintenanceRecord::where('status', 'pending')->count(),
            'in_progress' => MaintenanceRecord::where('status', 'in_progress')->count(),
            'completed' => MaintenanceRecord::where('status', 'completed')->count(),
            'total_cost' => MaintenanceRecord::where('status', 'completed')->sum('total_cost'),
        ];

        if ($request->has('export') && $request->export === 'pdf') {
            $allRecords = $query->orderByDesc('reported_at')->get();
            $pdf = Pdf::loadView('reports.pdf.maintenance-report', compact('allRecords', 'stats'));
            $pdf->setPaper('letter', 'landscape');
            return $pdf->download('reporte_mantenimiento_' . date('Y-m-d') . '.pdf');
        }

        if ($request->has('export') && $request->export === 'excel') {
            $allRecords = $query->orderByDesc('reported_at')->get();
            return Excel::download(new MaintenanceExport($allRecords), 'reporte_mantenimiento_' . date('Y-m-d') . '.xlsx');
        }

        return view('reports.maintenance-report', compact('records', 'stats'));
    }
}
