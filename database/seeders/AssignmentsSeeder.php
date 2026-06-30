<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Employee;
use App\Models\Equipment;
use App\Models\EquipmentHistory;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssignmentsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@baglass.com')->first();
        $oficinaTI = Location::where('code', 'P2-TI')->first();
        $oficinaVentas = Location::where('code', 'P1-VEN')->first();
        $oficinaMKT = Location::where('code', 'P2-MKT')->first();

        // Asignar algunos equipos a empleados
        $assignments = [
            [
                'equipment_code' => 'EQ-2024-0001',
                'employee_number' => 'EMP-001',
                'location_id' => $oficinaTI->id,
                'work_area' => 'Oficina del Gerente de TI',
                'assignment_notes' => 'Equipo principal para el gerente de TI',
                'accessories_delivered' => 'Cargador original, mouse inalámbrico Logitech MX Master 3',
            ],
            [
                'equipment_code' => 'EQ-2024-0003',
                'employee_number' => 'EMP-003',
                'location_id' => $oficinaTI->id,
                'work_area' => 'Área de desarrollo',
                'assignment_notes' => 'Equipo para desarrollo de software',
                'accessories_delivered' => 'Cargador original, docking station HP USB-C',
            ],
            [
                'equipment_code' => 'EQ-2024-0004',
                'employee_number' => 'EMP-004',
                'location_id' => $oficinaVentas->id,
                'work_area' => 'Área de ejecutivos de ventas',
                'assignment_notes' => 'Equipo para ejecutivo de ventas',
                'accessories_delivered' => 'Cargador original',
            ],
            [
                'equipment_code' => 'EQ-2024-0006',
                'employee_number' => 'EMP-006',
                'location_id' => $oficinaMKT->id,
                'work_area' => 'Área de diseño',
                'assignment_notes' => 'MacBook Pro para diseño gráfico',
                'accessories_delivered' => 'Cargador MagSafe, cable USB-C, adaptador USB-C a HDMI',
            ],
        ];

        foreach ($assignments as $assignmentData) {
            $equipment = Equipment::where('internal_code', $assignmentData['equipment_code'])->first();
            $employee = Employee::where('employee_number', $assignmentData['employee_number'])->first();

            if ($equipment && $employee) {
                $assignment = Assignment::create([
                    'equipment_id' => $equipment->id,
                    'employee_id' => $employee->id,
                    'assignment_date' => now()->subDays(rand(5, 30)),
                    'status' => 'active',
                    'condition_at_assignment' => 'excellent',
                    'assigned_by' => $admin->id,
                    'location_id' => $assignmentData['location_id'],
                    'work_area' => $assignmentData['work_area'],
                    'assignment_notes' => $assignmentData['assignment_notes'],
                    'accessories_delivered' => $assignmentData['accessories_delivered'],
                    'custody_letter_folio' => 'RES-' . date('Y') . '-' . str_pad($equipment->id, 6, '0', STR_PAD_LEFT),
                ]);

                // Actualizar equipo
                $equipment->update([
                    'availability_status' => 'assigned',
                    'current_employee_id' => $employee->id,
                    'assignment_date' => $assignment->assignment_date,
                    'location_id' => $assignmentData['location_id'],
                ]);

                // Crear registro en historial
                EquipmentHistory::create([
                    'equipment_id' => $equipment->id,
                    'assignment_id' => $assignment->id,
                    'movement_type' => 'assignment',
                    'new_employee_id' => $employee->id,
                    'new_location_id' => $assignmentData['location_id'],
                    'previous_status' => 'available',
                    'new_status' => 'assigned',
                    'title' => 'Asignación de equipo',
                    'description' => "Equipo asignado a {$employee->full_name}",
                    'performed_by' => $admin->id,
                    'performed_at' => $assignment->assignment_date,
                ]);
            }
        }
    }
}
