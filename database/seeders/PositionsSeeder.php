<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionsSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            'DIR' => [
                ['code' => 'CEO', 'name' => 'Director General', 'level' => 1],
                ['code' => 'CFO', 'name' => 'Director Financiero', 'level' => 2],
                ['code' => 'CTO', 'name' => 'Director de Tecnología', 'level' => 2],
                ['code' => 'COO', 'name' => 'Director de Operaciones', 'level' => 2],
                ['code' => 'ASIST-DIR', 'name' => 'Asistente de Dirección', 'level' => 3],
            ],
            'TI' => [
                ['code' => 'GER-TI', 'name' => 'Gerente de TI', 'level' => 2],
                ['code' => 'COORD-TI', 'name' => 'Coordinador de Sistemas', 'level' => 3],
                ['code' => 'DEV-SR', 'name' => 'Desarrollador Senior', 'level' => 4],
                ['code' => 'DEV-JR', 'name' => 'Desarrollador Junior', 'level' => 5],
                ['code' => 'SOPORTE', 'name' => 'Soporte Técnico', 'level' => 5],
                ['code' => 'DBA', 'name' => 'Administrador de Base de Datos', 'level' => 4],
                ['code' => 'INFRA', 'name' => 'Especialista en Infraestructura', 'level' => 4],
            ],
            'RH' => [
                ['code' => 'GER-RH', 'name' => 'Gerente de Recursos Humanos', 'level' => 2],
                ['code' => 'COORD-RH', 'name' => 'Coordinador de RH', 'level' => 3],
                ['code' => 'RECL', 'name' => 'Reclutador', 'level' => 4],
                ['code' => 'NOM', 'name' => 'Especialista en Nóminas', 'level' => 4],
                ['code' => 'CAP', 'name' => 'Capacitador', 'level' => 4],
            ],
            'FIN' => [
                ['code' => 'GER-FIN', 'name' => 'Gerente de Finanzas', 'level' => 2],
                ['code' => 'CONT', 'name' => 'Contador General', 'level' => 3],
                ['code' => 'AUX-CONT', 'name' => 'Auxiliar Contable', 'level' => 5],
                ['code' => 'TES', 'name' => 'Tesorero', 'level' => 3],
                ['code' => 'ANAL-FIN', 'name' => 'Analista Financiero', 'level' => 4],
            ],
            'VEN' => [
                ['code' => 'GER-VEN', 'name' => 'Gerente de Ventas', 'level' => 2],
                ['code' => 'COORD-VEN', 'name' => 'Coordinador de Ventas', 'level' => 3],
                ['code' => 'EJEC-VEN', 'name' => 'Ejecutivo de Ventas', 'level' => 4],
                ['code' => 'ASIST-VEN', 'name' => 'Asistente de Ventas', 'level' => 5],
            ],
            'MKT' => [
                ['code' => 'GER-MKT', 'name' => 'Gerente de Marketing', 'level' => 2],
                ['code' => 'COORD-MKT', 'name' => 'Coordinador de Marketing', 'level' => 3],
                ['code' => 'DIS', 'name' => 'Diseñador Gráfico', 'level' => 4],
                ['code' => 'CM', 'name' => 'Community Manager', 'level' => 4],
                ['code' => 'CONT-MKT', 'name' => 'Creador de Contenido', 'level' => 4],
            ],
            'OPS' => [
                ['code' => 'GER-OPS', 'name' => 'Gerente de Operaciones', 'level' => 2],
                ['code' => 'COORD-OPS', 'name' => 'Coordinador de Operaciones', 'level' => 3],
                ['code' => 'SUP-OPS', 'name' => 'Supervisor de Operaciones', 'level' => 4],
                ['code' => 'LOG', 'name' => 'Especialista en Logística', 'level' => 4],
                ['code' => 'ALM', 'name' => 'Almacenista', 'level' => 5],
            ],
            'LEG' => [
                ['code' => 'GER-LEG', 'name' => 'Gerente Legal', 'level' => 2],
                ['code' => 'ABOG', 'name' => 'Abogado', 'level' => 3],
                ['code' => 'PARAL', 'name' => 'Paralegal', 'level' => 4],
            ],
        ];

        foreach ($positions as $deptCode => $deptPositions) {
            $department = Department::where('code', $deptCode)->first();
            if ($department) {
                foreach ($deptPositions as $position) {
                    Position::create([
                        'code' => $position['code'],
                        'name' => $position['name'],
                        'level' => $position['level'],
                        'department_id' => $department->id,
                    ]);
                }
            }
        }
    }
}
