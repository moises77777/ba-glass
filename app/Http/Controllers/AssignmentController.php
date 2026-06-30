<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AuditLog;
use App\Models\Employee;
use App\Models\Equipment;
use App\Models\EquipmentHistory;
use App\Models\Location;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Assignment::with(['equipment.category', 'equipment.brand', 'employee.department', 'assignedBy']);

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('assignment_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('equipment', function ($eq) use ($search) {
                      $eq->where('internal_code', 'LIKE', "%{$search}%")
                         ->orWhere('serial_number', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('employee', function ($emp) use ($search) {
                      $emp->where('first_name', 'LIKE', "%{$search}%")
                          ->orWhere('last_name', 'LIKE', "%{$search}%")
                          ->orWhere('employee_number', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('assignment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('assignment_date', '<=', $request->date_to);
        }

        // Ordenamiento
        $sortField = $request->get('sort', 'assignment_date');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $assignments = $query->paginate(15)->withQueryString();

        $employees = Employee::active()->orderBy('first_name')->get();

        return view('assignments.index', compact('assignments', 'employees'));
    }

    public function create(Request $request)
    {
        $equipment = null;
        $employee = null;

        if ($request->filled('equipment_id')) {
            $equipment = Equipment::with(['category', 'brand'])->find($request->equipment_id);
        }

        if ($request->filled('employee_id')) {
            $employee = Employee::with(['department', 'position'])->find($request->employee_id);
        }

        $availableEquipment = Equipment::with(['category', 'brand'])
            ->available()
            ->operational()
            ->orderBy('internal_code')
            ->get();

        $employees = Employee::with(['department', 'position'])
            ->active()
            ->orderBy('first_name')
            ->get();

        $locations = Location::active()->orderBy('name')->get();

        return view('assignments.create', compact(
            'equipment',
            'employee',
            'availableEquipment',
            'employees',
            'locations'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'employee_id' => 'required|exists:employees,id',
            'assignment_date' => 'required|date',
            'expected_return_date' => 'nullable|date|after:assignment_date',
            'condition_at_assignment' => 'required|in:excellent,good,fair,poor,damaged',
            'location_id' => 'nullable|exists:locations,id',
            'work_area' => 'nullable|string|max:100',
            'assignment_notes' => 'nullable|string',
            'accessories_delivered' => 'nullable|string',
        ]);

        $equipment = Equipment::findOrFail($validated['equipment_id']);
        $employee = Employee::findOrFail($validated['employee_id']);

        // Verificar disponibilidad
        if (!$equipment->isAvailable()) {
            return back()
                ->withInput()
                ->with('error', 'El equipo seleccionado no está disponible para asignación.');
        }

        DB::beginTransaction();
        try {
            // Crear asignación
            $assignment = Assignment::create([
                'equipment_id' => $equipment->id,
                'employee_id' => $employee->id,
                'assignment_date' => $validated['assignment_date'],
                'expected_return_date' => $validated['expected_return_date'] ?? null,
                'status' => 'active',
                'condition_at_assignment' => $validated['condition_at_assignment'],
                'assigned_by' => auth()->id(),
                'location_id' => $validated['location_id'] ?? null,
                'work_area' => $validated['work_area'] ?? null,
                'assignment_notes' => $validated['assignment_notes'] ?? null,
                'accessories_delivered' => $validated['accessories_delivered'] ?? null,
            ]);

            // Generar folio de responsiva
            $assignment->update([
                'custody_letter_folio' => $assignment->generateCustodyLetterFolio(),
            ]);

            // Actualizar equipo
            $equipment->update([
                'availability_status' => 'assigned',
                'current_employee_id' => $employee->id,
                'assignment_date' => $validated['assignment_date'],
                'location_id' => $validated['location_id'] ?? $equipment->location_id,
                'physical_condition' => $validated['condition_at_assignment'],
            ]);

            // Registrar en historial
            EquipmentHistory::create([
                'equipment_id' => $equipment->id,
                'assignment_id' => $assignment->id,
                'movement_type' => 'assignment',
                'new_employee_id' => $employee->id,
                'new_location_id' => $validated['location_id'] ?? null,
                'previous_status' => 'available',
                'new_status' => 'assigned',
                'previous_condition' => $equipment->physical_condition,
                'new_condition' => $validated['condition_at_assignment'],
                'title' => 'Asignación de equipo',
                'description' => "Equipo asignado a {$employee->full_name}",
                'performed_by' => auth()->id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'performed_at' => now(),
            ]);

            // Auditoría
            AuditLog::log('assign', $assignment, "Equipo {$equipment->internal_code} asignado a {$employee->full_name}");

            DB::commit();

            // Notificación por correo (no debe romper el flujo si falla)
            if ($employee->email) {
                try {
                    \Illuminate\Support\Facades\Mail::to($employee->email)
                        ->send(new \App\Mail\AssignmentCreatedMail($assignment));
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('No se pudo enviar correo de asignación: ' . $e->getMessage());
                }
            }

            return redirect()
                ->route('assignments.show', $assignment)
                ->with('success', 'Asignación realizada exitosamente. Folio de responsiva: ' . $assignment->custody_letter_folio);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error al realizar la asignación: ' . $e->getMessage());
        }
    }

    public function show(Assignment $assignment)
    {
        $assignment->load([
            'equipment.category',
            'equipment.brand',
            'equipment.images',
            'employee.department',
            'employee.position',
            'assignedBy',
            'receivedBy',
            'location',
            'history',
        ]);

        return view('assignments.show', compact('assignment'));
    }

    public function return(Assignment $assignment)
    {
        if (!$assignment->isActive()) {
            return back()->with('error', 'Esta asignación ya no está activa.');
        }

        $assignment->load([
            'equipment.category',
            'equipment.brand',
            'employee.department',
        ]);

        return view('assignments.return', compact('assignment'));
    }

    public function processReturn(Request $request, Assignment $assignment)
    {
        if (!$assignment->isActive()) {
            return back()->with('error', 'Esta asignación ya no está activa.');
        }

        $validated = $request->validate([
            'actual_return_date' => 'required|date',
            'condition_at_return' => 'required|in:excellent,good,fair,poor,damaged',
            'return_reason' => 'required|in:employee_termination,equipment_upgrade,equipment_damage,department_change,project_end,maintenance,other',
            'return_reason_details' => 'nullable|string',
            'return_notes' => 'nullable|string',
            'accessories_returned' => 'nullable|string',
        ]);

        $equipment = $assignment->equipment;
        $employee = $assignment->employee;

        DB::beginTransaction();
        try {
            // Actualizar asignación
            $assignment->update([
                'actual_return_date' => $validated['actual_return_date'],
                'status' => 'returned',
                'condition_at_return' => $validated['condition_at_return'],
                'received_by' => auth()->id(),
                'return_reason' => $validated['return_reason'],
                'return_reason_details' => $validated['return_reason_details'] ?? null,
                'return_notes' => $validated['return_notes'] ?? null,
                'accessories_returned' => $validated['accessories_returned'] ?? null,
            ]);

            // Actualizar equipo
            $equipment->update([
                'availability_status' => 'available',
                'current_employee_id' => null,
                'assignment_date' => null,
                'physical_condition' => $validated['condition_at_return'],
            ]);

            // Registrar en historial
            EquipmentHistory::create([
                'equipment_id' => $equipment->id,
                'assignment_id' => $assignment->id,
                'movement_type' => 'return',
                'previous_employee_id' => $employee->id,
                'previous_status' => 'assigned',
                'new_status' => 'available',
                'previous_condition' => $assignment->condition_at_assignment,
                'new_condition' => $validated['condition_at_return'],
                'title' => 'Devolución de equipo',
                'description' => "Equipo devuelto por {$employee->full_name}. Motivo: " . Assignment::RETURN_REASONS[$validated['return_reason']],
                'reason' => $validated['return_reason_details'] ?? null,
                'performed_by' => auth()->id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'performed_at' => now(),
            ]);

            // Auditoría
            AuditLog::log('unassign', $assignment, "Equipo {$equipment->internal_code} devuelto por {$employee->full_name}");

            DB::commit();

            return redirect()
                ->route('assignments.show', $assignment)
                ->with('success', 'Devolución procesada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error al procesar la devolución: ' . $e->getMessage());
        }
    }

    public function transfer(Assignment $assignment)
    {
        if (!$assignment->isActive()) {
            return back()->with('error', 'Esta asignación ya no está activa.');
        }

        $assignment->load([
            'equipment.category',
            'equipment.brand',
            'employee.department',
        ]);

        $employees = Employee::with(['department', 'position'])
            ->where('id', '!=', $assignment->employee_id)
            ->active()
            ->orderBy('first_name')
            ->get();

        $locations = Location::active()->orderBy('name')->get();

        return view('assignments.transfer', compact('assignment', 'employees', 'locations'));
    }

    public function processTransfer(Request $request, Assignment $assignment)
    {
        if (!$assignment->isActive()) {
            return back()->with('error', 'Esta asignación ya no está activa.');
        }

        $validated = $request->validate([
            'new_employee_id' => 'required|exists:employees,id|different:' . $assignment->employee_id,
            'transfer_date' => 'required|date',
            'condition_at_transfer' => 'required|in:excellent,good,fair,poor,damaged',
            'location_id' => 'nullable|exists:locations,id',
            'work_area' => 'nullable|string|max:100',
            'transfer_notes' => 'nullable|string',
        ]);

        $equipment = $assignment->equipment;
        $previousEmployee = $assignment->employee;
        $newEmployee = Employee::findOrFail($validated['new_employee_id']);

        DB::beginTransaction();
        try {
            // Cerrar asignación anterior
            $assignment->update([
                'actual_return_date' => $validated['transfer_date'],
                'status' => 'transferred',
                'condition_at_return' => $validated['condition_at_transfer'],
                'received_by' => auth()->id(),
                'return_reason' => 'other',
                'return_reason_details' => 'Transferencia a ' . $newEmployee->full_name,
            ]);

            // Crear nueva asignación
            $newAssignment = Assignment::create([
                'equipment_id' => $equipment->id,
                'employee_id' => $newEmployee->id,
                'assignment_date' => $validated['transfer_date'],
                'status' => 'active',
                'condition_at_assignment' => $validated['condition_at_transfer'],
                'assigned_by' => auth()->id(),
                'location_id' => $validated['location_id'] ?? null,
                'work_area' => $validated['work_area'] ?? null,
                'assignment_notes' => $validated['transfer_notes'] ?? null,
                'accessories_delivered' => $assignment->accessories_delivered,
            ]);

            $newAssignment->update([
                'custody_letter_folio' => $newAssignment->generateCustodyLetterFolio(),
            ]);

            // Actualizar equipo
            $equipment->update([
                'current_employee_id' => $newEmployee->id,
                'assignment_date' => $validated['transfer_date'],
                'location_id' => $validated['location_id'] ?? $equipment->location_id,
                'physical_condition' => $validated['condition_at_transfer'],
            ]);

            // Registrar en historial
            EquipmentHistory::create([
                'equipment_id' => $equipment->id,
                'assignment_id' => $newAssignment->id,
                'movement_type' => 'transfer',
                'previous_employee_id' => $previousEmployee->id,
                'new_employee_id' => $newEmployee->id,
                'new_location_id' => $validated['location_id'] ?? null,
                'previous_condition' => $assignment->condition_at_assignment,
                'new_condition' => $validated['condition_at_transfer'],
                'title' => 'Transferencia de equipo',
                'description' => "Equipo transferido de {$previousEmployee->full_name} a {$newEmployee->full_name}",
                'performed_by' => auth()->id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'performed_at' => now(),
            ]);

            // Auditoría
            AuditLog::log('transfer', $newAssignment, "Equipo {$equipment->internal_code} transferido de {$previousEmployee->full_name} a {$newEmployee->full_name}");

            DB::commit();

            return redirect()
                ->route('assignments.show', $newAssignment)
                ->with('success', 'Transferencia realizada exitosamente. Nuevo folio: ' . $newAssignment->custody_letter_folio);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error al procesar la transferencia: ' . $e->getMessage());
        }
    }

    public function generatePdf(Assignment $assignment)
    {
        $assignment->load([
            'equipment.category',
            'equipment.brand',
            'equipment.images',
            'employee.department',
            'employee.position',
            'assignedBy',
            'location',
        ]);

        // Generar QR
        $qrData = route('assignments.show', $assignment, false);
        $qrCode = base64_encode(QrCode::format('svg')->size(100)->generate($qrData));

        $pdf = Pdf::loadView('pdf.custody-letter', [
            'assignment' => $assignment,
            'qrCode' => $qrCode,
            'qrFormat' => 'svg',
            'company' => [
                'name' => config('app.company.name'),
                'address' => config('app.company.address'),
                'phone' => config('app.company.phone'),
                'email' => config('app.company.email'),
                'rfc' => config('app.company.rfc'),
            ],
        ]);

        $pdf->setPaper('letter', 'portrait');

        // Actualizar fecha de generación
        if (!$assignment->custody_letter_generated_at) {
            $assignment->update([
                'custody_letter_generated_at' => now(),
            ]);
        }

        $filename = "responsiva_{$assignment->custody_letter_folio}.pdf";

        return $pdf->download($filename);
    }

    public function previewPdf(Assignment $assignment)
    {
        $assignment->load([
            'equipment.category',
            'equipment.brand',
            'employee.department',
            'employee.position',
            'assignedBy',
            'location',
        ]);

        $qrData = route('assignments.show', $assignment, false);
        $qrCode = base64_encode(QrCode::format('svg')->size(100)->generate($qrData));

        $pdf = Pdf::loadView('pdf.custody-letter', [
            'assignment' => $assignment,
            'qrCode' => $qrCode,
            'qrFormat' => 'svg',
            'company' => [
                'name' => config('app.company.name'),
                'address' => config('app.company.address'),
                'phone' => config('app.company.phone'),
                'email' => config('app.company.email'),
                'rfc' => config('app.company.rfc'),
            ],
        ]);

        $pdf->setPaper('letter', 'portrait');

        return $pdf->stream("responsiva_{$assignment->custody_letter_folio}.pdf");
    }

    /**
     * Búsqueda en tiempo real para AJAX
     */
    public function search(Request $request)
    {
        $query = Assignment::with(['equipment.category', 'equipment.brand', 'employee.department', 'assignedBy']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('assignment_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('equipment', function ($eq) use ($search) {
                      $eq->where('internal_code', 'LIKE', "%{$search}%")
                         ->orWhere('serial_number', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('employee', function ($emp) use ($search) {
                      $emp->where('first_name', 'LIKE', "%{$search}%")
                          ->orWhere('last_name', 'LIKE', "%{$search}%")
                          ->orWhere('employee_number', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $assignments = $query->orderBy('assignment_date', 'desc')->paginate(15);

        return response()->json([
            'data' => view('assignments._table_rows', compact('assignments'))->render(),
            'pagination' => $assignments->links()->toHtml(),
            'total' => $assignments->total(),
        ]);
    }
}
