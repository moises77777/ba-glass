<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Equipment;
use App\Models\EquipmentHistory;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = EquipmentHistory::with([
            'equipment.category',
            'equipment.brand',
            'performedBy',
            'previousEmployee',
            'newEmployee',
            'previousLocation',
            'newLocation',
        ]);

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('equipment', function ($q) use ($search) {
                $q->where('internal_code', 'LIKE', "%{$search}%")
                  ->orWhere('serial_number', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('equipment_id')) {
            $query->where('equipment_id', $request->equipment_id);
        }

        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }

        if ($request->filled('employee_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('previous_employee_id', $request->employee_id)
                  ->orWhere('new_employee_id', $request->employee_id);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('performed_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('performed_at', '<=', $request->date_to);
        }

        if ($request->filled('performed_by')) {
            $query->where('performed_by', $request->performed_by);
        }

        // Ordenamiento
        $query->orderByDesc('performed_at');

        $history = $query->paginate(20)->withQueryString();

        // Datos para filtros
        $equipment = Equipment::orderBy('internal_code')->get();
        $employees = Employee::orderBy('first_name')->get();
        $movementTypes = EquipmentHistory::MOVEMENT_TYPES;

        return view('history.index', compact('history', 'equipment', 'employees', 'movementTypes'));
    }

    public function show(EquipmentHistory $history)
    {
        $history->load([
            'equipment.category',
            'equipment.brand',
            'assignment',
            'performedBy',
            'previousEmployee',
            'newEmployee',
            'previousLocation',
            'newLocation',
        ]);

        return view('history.show', compact('history'));
    }

    public function byEquipment(Equipment $equipment)
    {
        $history = $equipment->history()
            ->with(['performedBy', 'previousEmployee', 'newEmployee', 'previousLocation', 'newLocation'])
            ->orderByDesc('performed_at')
            ->paginate(20);

        return view('history.by-equipment', compact('equipment', 'history'));
    }

    public function byEmployee(Employee $employee)
    {
        $history = EquipmentHistory::with(['equipment.category', 'equipment.brand', 'performedBy'])
            ->where(function ($q) use ($employee) {
                $q->where('previous_employee_id', $employee->id)
                  ->orWhere('new_employee_id', $employee->id);
            })
            ->orderByDesc('performed_at')
            ->paginate(20);

        return view('history.by-employee', compact('employee', 'history'));
    }

    public function timeline(Equipment $equipment)
    {
        $history = $equipment->history()
            ->with(['performedBy', 'previousEmployee', 'newEmployee', 'assignment'])
            ->orderByDesc('performed_at')
            ->get();

        return view('history.timeline', compact('equipment', 'history'));
    }
}
