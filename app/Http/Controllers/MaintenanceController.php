<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Equipment;
use App\Models\EquipmentHistory;
use App\Models\MaintenanceRecord;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = MaintenanceRecord::with(['equipment.category', 'reporter', 'assignee', 'supplier']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'LIKE', "%{$search}%")
                  ->orWhere('title', 'LIKE', "%{$search}%")
                  ->orWhereHas('equipment', function ($eq) use ($search) {
                      $eq->where('internal_code', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $query->orderByDesc('reported_at');

        $records = $query->paginate(15)->withQueryString();

        return view('maintenance.index', compact('records'));
    }

    public function create(Request $request)
    {
        $equipment = Equipment::with(['category', 'brand'])
            ->where(function ($q) {
                $q->whereIn('physical_condition', ['damaged', 'for_repair', 'poor'])
                  ->orWhereIn('operational_status', ['under_repair', 'non_operational'])
                  ->orWhere('availability_status', 'in_maintenance');
            })
            ->orderBy('internal_code')
            ->get();
        $suppliers = Supplier::active()->orderBy('name')->get();
        $users = User::active()->orderBy('name')->get();

        $selectedEquipment = null;
        if ($request->filled('equipment_id')) {
            $selectedEquipment = Equipment::find($request->equipment_id);
        }

        return view('maintenance.create', compact('equipment', 'suppliers', 'users', 'selectedEquipment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'type' => 'required|in:preventive,corrective,upgrade,cleaning,inspection,other',
            'priority' => 'required|in:low,medium,high,critical',
            'title' => 'required|string|max:150',
            'problem_description' => 'required|string',
            'scheduled_date' => 'nullable|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'assigned_to' => 'nullable|exists:users,id',
            'condition_before' => 'nullable|in:excellent,good,fair,poor,damaged,non_operational',
            'notes' => 'nullable|string',
        ]);

        $validated['reported_at'] = now();
        $validated['reported_by'] = auth()->id();
        $validated['status'] = 'pending';

        DB::beginTransaction();
        try {
            $record = MaintenanceRecord::create($validated);

            $equipment = Equipment::find($validated['equipment_id']);

            // Registrar en historial
            EquipmentHistory::createEntry(
                $equipment,
                'maintenance_start',
                'Mantenimiento registrado',
                "Ticket: {$record->ticket_number} - {$record->title}",
                ['metadata' => ['maintenance_id' => $record->id]]
            );

            AuditLog::log('create', $record, 'Mantenimiento registrado: ' . $record->ticket_number);

            DB::commit();

            // Notificación por correo al técnico asignado (no rompe el flujo si falla)
            $record->load(['equipment.brand', 'assignee']);
            if ($record->assignee && $record->assignee->email) {
                try {
                    \Illuminate\Support\Facades\Mail::to($record->assignee->email)
                        ->send(new \App\Mail\MaintenanceScheduledMail($record));
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('No se pudo enviar correo de mantenimiento: ' . $e->getMessage());
                }
            }

            return redirect()
                ->route('maintenance.show', $record)
                ->with('success', 'Mantenimiento registrado exitosamente. Ticket: ' . $record->ticket_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al registrar el mantenimiento: ' . $e->getMessage());
        }
    }

    public function show(MaintenanceRecord $maintenance)
    {
        $maintenance->load(['equipment.category', 'equipment.brand', 'reporter', 'assignee', 'completer', 'supplier']);

        return view('maintenance.show', compact('maintenance'));
    }

    public function edit(MaintenanceRecord $maintenance)
    {
        $equipment = Equipment::with(['category', 'brand'])
            ->where(function ($q) use ($maintenance) {
                $q->whereIn('physical_condition', ['damaged', 'for_repair', 'poor'])
                  ->orWhereIn('operational_status', ['under_repair', 'non_operational'])
                  ->orWhere('availability_status', 'in_maintenance')
                  ->orWhere('id', $maintenance->equipment_id);
            })
            ->orderBy('internal_code')
            ->get();
        $suppliers = Supplier::active()->orderBy('name')->get();
        $users = User::active()->orderBy('name')->get();

        return view('maintenance.edit', compact('maintenance', 'equipment', 'suppliers', 'users'));
    }

    public function update(Request $request, MaintenanceRecord $maintenance)
    {
        $validated = $request->validate([
            'type' => 'required|in:preventive,corrective,upgrade,cleaning,inspection,other',
            'status' => 'required|in:pending,in_progress,completed,cancelled,on_hold',
            'priority' => 'required|in:low,medium,high,critical',
            'title' => 'required|string|max:150',
            'problem_description' => 'required|string',
            'diagnosis' => 'nullable|string',
            'solution' => 'nullable|string',
            'parts_replaced' => 'nullable|string',
            'labor_cost' => 'nullable|numeric|min:0',
            'parts_cost' => 'nullable|numeric|min:0',
            'scheduled_date' => 'nullable|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'technician_name' => 'nullable|string|max:150',
            'technician_phone' => 'nullable|string|max:20',
            'assigned_to' => 'nullable|exists:users,id',
            'condition_before' => 'nullable|in:excellent,good,fair,poor,damaged,non_operational',
            'condition_after' => 'nullable|in:excellent,good,fair,poor,damaged,non_operational',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $maintenance->status;

        DB::beginTransaction();
        try {
            // Actualizar fechas según estado
            if ($validated['status'] === 'in_progress' && !$maintenance->started_at) {
                $validated['started_at'] = now();
            }

            if ($validated['status'] === 'completed' && !$maintenance->completed_at) {
                $validated['completed_at'] = now();
                $validated['completed_by'] = auth()->id();
            }

            // Calcular costo total
            $validated['total_cost'] = ($validated['labor_cost'] ?? 0) + ($validated['parts_cost'] ?? 0);

            $maintenance->update($validated);

            // Si se completó, registrar en historial
            if ($oldStatus !== 'completed' && $validated['status'] === 'completed') {
                EquipmentHistory::createEntry(
                    $maintenance->equipment,
                    'maintenance_end',
                    'Mantenimiento completado',
                    "Ticket: {$maintenance->ticket_number}",
                    [
                        'metadata' => ['maintenance_id' => $maintenance->id],
                        'previous_condition' => $validated['condition_before'],
                        'new_condition' => $validated['condition_after'],
                    ]
                );

                // Actualizar equipo al completar mantenimiento
                $equipmentUpdate = [
                    'availability_status'  => 'available',
                    'operational_status'   => 'operational',
                    'last_maintenance_date' => now(),
                ];
                if (!empty($validated['condition_after'])) {
                    $equipmentUpdate['physical_condition'] = $validated['condition_after'];
                }
                $maintenance->equipment->update($equipmentUpdate);
            }

            AuditLog::log('update', $maintenance, 'Mantenimiento actualizado: ' . $maintenance->ticket_number);

            DB::commit();

            return redirect()
                ->route('maintenance.show', $maintenance)
                ->with('success', 'Mantenimiento actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar el mantenimiento: ' . $e->getMessage());
        }
    }

    public function destroy(MaintenanceRecord $maintenance)
    {
        if ($maintenance->status === 'completed') {
            return back()->with('error', 'No se puede eliminar un mantenimiento completado.');
        }

        DB::beginTransaction();
        try {
            AuditLog::log('delete', $maintenance, 'Mantenimiento eliminado: ' . $maintenance->ticket_number);
            $maintenance->delete();
            DB::commit();

            return redirect()
                ->route('maintenance.index')
                ->with('success', 'Mantenimiento eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar el mantenimiento: ' . $e->getMessage());
        }
    }
}
