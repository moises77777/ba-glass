<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['department', 'position', 'user']);

        // Filtros
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('position_id')) {
            $query->where('position_id', $request->position_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Ordenamiento
        $sortField = $request->get('sort', 'employee_number');
        $sortDirection = $request->get('direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        $employees = $query->paginate(15)->withQueryString();

        // Datos para filtros
        $departments = Department::active()->orderBy('name')->get();
        $positions = Position::orderBy('name')->get();

        return view('employees.index', compact('employees', 'departments', 'positions'));
    }

    public function create()
    {
        $departments = Department::active()->orderBy('name')->get();
        $positions = Position::orderBy('name')->get();
        $users = User::whereDoesntHave('employee')->active()->orderBy('name')->get();
        $supervisors = Employee::active()->orderBy('first_name')->get();

        return view('employees.create', compact('departments', 'positions', 'users', 'supervisors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_number' => 'required|string|max:20|unique:employees',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:employees',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'curp' => 'nullable|string|max:18|unique:employees',
            'rfc' => 'nullable|string|max:13',
            'birth_date' => 'nullable|date|before:today',
            'hire_date' => 'required|date',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'user_id' => 'nullable|exists:users,id|unique:employees',
            'supervisor_id' => 'nullable|exists:employees,id',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'emergency_contact_name' => 'nullable|string|max:150',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Subir foto
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $validated['photo'] = $photo->storeAs('employees', $filename, 'public');
            }

            $validated['status'] = 'active';
            $employee = Employee::create($validated);

            // Auditoría
            AuditLog::log('create', $employee, 'Empleado creado: ' . $employee->full_name);

            DB::commit();

            return redirect()
                ->route('employees.show', $employee)
                ->with('success', 'Empleado registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error al registrar el empleado: ' . $e->getMessage());
        }
    }

    public function show(Employee $employee)
    {
        $employee->load([
            'department',
            'position',
            'user',
            'supervisor',
            'subordinates',
            'currentEquipment.category',
            'currentEquipment.brand',
        ]);

        $assignments = $employee->assignments()
            ->with(['equipment.category', 'equipment.brand', 'assignedBy'])
            ->orderByDesc('assignment_date')
            ->get();

        $equipmentHistory = $employee->equipmentHistory()
            ->with(['equipment', 'performedBy'])
            ->orderByDesc('performed_at')
            ->limit(20)
            ->get();

        return view('employees.show', compact('employee', 'assignments', 'equipmentHistory'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::active()->orderBy('name')->get();
        $positions = Position::orderBy('name')->get();
        $users = User::where(function ($query) use ($employee) {
            $query->whereDoesntHave('employee')
                  ->orWhere('id', $employee->user_id);
        })->active()->orderBy('name')->get();
        $supervisors = Employee::where('id', '!=', $employee->id)
            ->active()
            ->orderBy('first_name')
            ->get();

        return view('employees.edit', compact('employee', 'departments', 'positions', 'users', 'supervisors'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'employee_number' => ['required', 'string', 'max:20', Rule::unique('employees')->ignore($employee->id)],
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:150', Rule::unique('employees')->ignore($employee->id)],
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'curp' => ['nullable', 'string', 'max:18', Rule::unique('employees')->ignore($employee->id)],
            'rfc' => 'nullable|string|max:13',
            'birth_date' => 'nullable|date|before:today',
            'hire_date' => 'required|date',
            'termination_date' => 'nullable|date|after:hire_date',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'user_id' => ['nullable', 'exists:users,id', Rule::unique('employees')->ignore($employee->id)],
            'supervisor_id' => 'nullable|exists:employees,id',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'emergency_contact_name' => 'nullable|string|max:150',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:active,inactive,on_leave,terminated',
            'notes' => 'nullable|string',
        ]);

        $oldValues = $employee->toArray();

        DB::beginTransaction();
        try {
            // Subir nueva foto
            if ($request->hasFile('photo')) {
                // Eliminar foto anterior
                if ($employee->photo) {
                    Storage::disk('public')->delete($employee->photo);
                }
                $photo = $request->file('photo');
                $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $validated['photo'] = $photo->storeAs('employees', $filename, 'public');
            }

            $employee->update($validated);

            // Auditoría
            AuditLog::log('update', $employee, 'Empleado actualizado: ' . $employee->full_name, $oldValues, $validated);

            DB::commit();

            return redirect()
                ->route('employees.show', $employee)
                ->with('success', 'Empleado actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar el empleado: ' . $e->getMessage());
        }
    }

    public function destroy(Employee $employee)
    {
        if ($employee->hasEquipmentAssigned()) {
            return back()->with('error', 'No se puede eliminar un empleado que tiene equipos asignados. Primero debe devolver los equipos.');
        }

        DB::beginTransaction();
        try {
            // Eliminar foto
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }

            // Auditoría
            AuditLog::log('delete', $employee, 'Empleado eliminado: ' . $employee->full_name);

            $employee->delete();

            DB::commit();

            return redirect()
                ->route('employees.index')
                ->with('success', 'Empleado eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar el empleado: ' . $e->getMessage());
        }
    }

    public function getPositionsByDepartment(Department $department)
    {
        $positions = $department->positions()->orderBy('name')->get();
        return response()->json($positions);
    }

    public function active()
    {
        $employees = Employee::with(['department', 'position'])
            ->active()
            ->orderBy('first_name')
            ->get();

        return response()->json($employees);
    }

    /**
     * Búsqueda en tiempo real para AJAX
     */
    public function search(Request $request)
    {
        $query = Employee::with(['department', 'position', 'user']);

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $employees = $query->orderBy('employee_number')->paginate(15);

        return response()->json([
            'data' => view('employees._table_rows', compact('employees'))->render(),
            'pagination' => $employees->links()->toHtml(),
            'total' => $employees->total(),
        ]);
    }

    /**
     * Directorio de empleados con equipos asignados
     */
    public function directory(Request $request)
    {
        $query = Employee::with(['department', 'position', 'assignments.equipment.category', 'assignments.equipment.brand'])
            ->active();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $employees = $query->orderBy('first_name')->paginate(20)->withQueryString();
        $departments = Department::active()->orderBy('name')->get();

        return view('employees.directory', compact('employees', 'departments'));
    }

    /**
     * Exportar directorio a PDF
     */
    public function exportDirectoryPdf(Request $request)
    {
        $query = Employee::with(['department', 'position', 'assignments.equipment.category', 'assignments.equipment.brand'])
            ->active();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $employees = $query->orderBy('first_name')->get();
        $departments = Department::active()->orderBy('name')->get();

        $pdf = Pdf::loadView('employees.directory-pdf', compact('employees', 'departments'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('directorio_equipos_' . now()->format('Ymd_Hi') . '.pdf');
    }

    /**
     * Exportar directorio a Excel
     */
    public function exportDirectoryExcel(Request $request)
    {
        $query = Employee::with(['department', 'position', 'assignments.equipment.category', 'assignments.equipment.brand'])
            ->active();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $employees = $query->orderBy('first_name')->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Titulo
        $sheet->setCellValue('A1', 'DIRECTORIO DE EQUIPOS ASIGNADOS - BA GLASS MEXICO');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        $sheet->setCellValue('A2', 'Generado: ' . now()->format('d/m/Y H:i'));
        $sheet->mergeCells('A2:H2');

        // Encabezados
        $headers = ['No. Empleado', 'Nombre', 'Departamento', 'Puesto', 'Equipo', 'Codigo', 'Marca / Modelo', 'Fecha Asignacion'];
        $col = 1;
        foreach ($headers as $header) {
            $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '4';
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getFill()->setFillType('solid')->getStartColor()->setRGB('0055A4');
            $sheet->getStyle($cell)->getFont()->getColor()->setRGB('FFFFFF');
            $col++;
        }

        // Datos
        $row = 5;
        foreach ($employees as $employee) {
            $activeAssignments = $employee->assignments->where('status', 'active');
            if ($activeAssignments->count() > 0) {
                foreach ($activeAssignments as $assignment) {
                    $sheet->setCellValue('A' . $row, $employee->employee_number ?? '-');
                    $sheet->setCellValue('B' . $row, $employee->full_name);
                    $sheet->setCellValue('C' . $row, $employee->department->name ?? '-');
                    $sheet->setCellValue('D' . $row, $employee->position->name ?? '-');
                    $sheet->setCellValue('E' . $row, $assignment->equipment->category->name ?? '-');
                    $sheet->setCellValue('F' . $row, $assignment->equipment->internal_code ?? '-');
                    $sheet->setCellValue('G' . $row, ($assignment->equipment->brand->name ?? '-') . ' ' . ($assignment->equipment->model ?? ''));
                    $sheet->setCellValue('H' . $row, $assignment->assignment_date ? $assignment->assignment_date->format('d/m/Y') : '-');
                    $row++;
                }
            }
        }

        // Auto ajustar columnas
        foreach (range(1, 8) as $colIndex) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'directorio_equipos_' . now()->format('Ymd_Hi') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'dir_');
        $writer->save($tempFile);

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
