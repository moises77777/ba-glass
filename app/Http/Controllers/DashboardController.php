<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Employee;
use App\Models\Equipment;
use App\Models\EquipmentHistory;
use App\Models\MaintenanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Estadísticas generales
        $stats = [
            'total_equipment' => Equipment::count(),
            'available_equipment' => Equipment::where('availability_status', 'available')->count(),
            'assigned_equipment' => Equipment::where('availability_status', 'assigned')->count(),
            'maintenance_equipment' => Equipment::where('availability_status', 'in_maintenance')->count(),
            'total_employees' => Employee::where('status', 'active')->count(),
            'active_assignments' => Assignment::where('status', 'active')->count(),
            'pending_maintenance' => MaintenanceRecord::whereIn('status', ['pending', 'in_progress'])->count(),
        ];

        // Equipos por categoría
        $equipmentByCategory = Equipment::select('equipment_categories.name', DB::raw('count(*) as total'))
            ->join('equipment_categories', 'equipment.category_id', '=', 'equipment_categories.id')
            ->groupBy('equipment_categories.id', 'equipment_categories.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Equipos por estado
        $equipmentByStatus = Equipment::select('availability_status', DB::raw('count(*) as total'))
            ->groupBy('availability_status')
            ->get()
            ->map(function ($item) {
                $item->status_name = Equipment::AVAILABILITY_STATUSES[$item->availability_status] ?? $item->availability_status;
                return $item;
            });

        // Equipos por condición física
        $equipmentByCondition = Equipment::select('physical_condition', DB::raw('count(*) as total'))
            ->groupBy('physical_condition')
            ->get()
            ->map(function ($item) {
                $item->condition_name = Equipment::PHYSICAL_CONDITIONS[$item->physical_condition] ?? $item->physical_condition;
                return $item;
            });

        // Últimos movimientos
        $recentHistory = EquipmentHistory::with(['equipment', 'performedBy', 'newEmployee'])
            ->orderByDesc('performed_at')
            ->limit(10)
            ->get();

        // Asignaciones recientes
        $recentAssignments = Assignment::with(['equipment', 'employee', 'assignedBy'])
            ->orderByDesc('assignment_date')
            ->limit(5)
            ->get();

        // Equipos con garantía próxima a vencer (30 días)
        $warrantyExpiring = Equipment::whereNotNull('warranty_end_date')
            ->where('warranty_end_date', '<=', now()->addDays(30))
            ->where('warranty_end_date', '>=', now())
            ->orderBy('warranty_end_date')
            ->limit(5)
            ->get();

        // Mantenimientos pendientes
        $pendingMaintenance = MaintenanceRecord::with(['equipment'])
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderBy('priority', 'desc')
            ->orderBy('reported_at')
            ->limit(5)
            ->get();

        // Equipos por departamento
        $equipmentByDepartment = Employee::select('departments.name', DB::raw('count(equipment.id) as total'))
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->join('equipment', 'employees.id', '=', 'equipment.current_employee_id')
            ->groupBy('departments.id', 'departments.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Asignaciones por mes (últimos 6 meses)
        $assignmentsByMonth = Assignment::select(
                DB::raw('MONTH(assignment_date) as month'),
                DB::raw('YEAR(assignment_date) as year'),
                DB::raw('count(*) as total')
            )
            ->where('assignment_date', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                $months = ['', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                $item->label = $months[$item->month] . ' ' . $item->year;
                return $item;
            });

        return view('dashboard', compact(
            'stats',
            'equipmentByCategory',
            'equipmentByStatus',
            'equipmentByCondition',
            'recentHistory',
            'recentAssignments',
            'warrantyExpiring',
            'pendingMaintenance',
            'equipmentByDepartment',
            'assignmentsByMonth'
        ));
    }

    public function getStats()
    {
        return response()->json([
            'total_equipment' => Equipment::count(),
            'available_equipment' => Equipment::where('availability_status', 'available')->count(),
            'assigned_equipment' => Equipment::where('availability_status', 'assigned')->count(),
            'maintenance_equipment' => Equipment::where('availability_status', 'in_maintenance')->count(),
        ]);
    }
}
