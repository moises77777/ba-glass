<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentsSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'code' => 'DIR',
                'name' => 'Dirección General',
                'description' => 'Alta dirección de la empresa',
                'location' => 'Piso 5',
                'phone' => 'Ext. 100',
                'email' => 'direccion@baglass.com',
            ],
            [
                'code' => 'TI',
                'name' => 'Tecnologías de la Información',
                'description' => 'Departamento de sistemas y tecnología',
                'location' => 'Piso 2',
                'phone' => 'Ext. 200',
                'email' => 'ti@baglass.com',
            ],
            [
                'code' => 'RH',
                'name' => 'Recursos Humanos',
                'description' => 'Gestión del talento humano',
                'location' => 'Piso 1',
                'phone' => 'Ext. 300',
                'email' => 'rh@baglass.com',
            ],
            [
                'code' => 'FIN',
                'name' => 'Finanzas',
                'description' => 'Administración financiera y contabilidad',
                'location' => 'Piso 3',
                'phone' => 'Ext. 400',
                'email' => 'finanzas@baglass.com',
            ],
            [
                'code' => 'VEN',
                'name' => 'Ventas',
                'description' => 'Departamento comercial y ventas',
                'location' => 'Piso 1',
                'phone' => 'Ext. 500',
                'email' => 'ventas@baglass.com',
            ],
            [
                'code' => 'MKT',
                'name' => 'Marketing',
                'description' => 'Mercadotecnia y publicidad',
                'location' => 'Piso 2',
                'phone' => 'Ext. 600',
                'email' => 'marketing@baglass.com',
            ],
            [
                'code' => 'OPS',
                'name' => 'Operaciones',
                'description' => 'Operaciones y logística',
                'location' => 'Planta Baja',
                'phone' => 'Ext. 700',
                'email' => 'operaciones@baglass.com',
            ],
            [
                'code' => 'LEG',
                'name' => 'Legal',
                'description' => 'Departamento jurídico',
                'location' => 'Piso 4',
                'phone' => 'Ext. 800',
                'email' => 'legal@baglass.com',
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
