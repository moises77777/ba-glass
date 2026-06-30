<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos por módulo
        $permissions = [
            // Dashboard
            ['name' => 'dashboard.view', 'group' => 'dashboard', 'description' => 'Ver dashboard'],
            ['name' => 'dashboard.stats', 'group' => 'dashboard', 'description' => 'Ver estadísticas'],

            // Usuarios
            ['name' => 'users.view', 'group' => 'users', 'description' => 'Ver usuarios'],
            ['name' => 'users.create', 'group' => 'users', 'description' => 'Crear usuarios'],
            ['name' => 'users.edit', 'group' => 'users', 'description' => 'Editar usuarios'],
            ['name' => 'users.delete', 'group' => 'users', 'description' => 'Eliminar usuarios'],
            ['name' => 'users.manage_roles', 'group' => 'users', 'description' => 'Gestionar roles de usuarios'],

            // Empleados
            ['name' => 'employees.view', 'group' => 'employees', 'description' => 'Ver empleados'],
            ['name' => 'employees.create', 'group' => 'employees', 'description' => 'Crear empleados'],
            ['name' => 'employees.edit', 'group' => 'employees', 'description' => 'Editar empleados'],
            ['name' => 'employees.delete', 'group' => 'employees', 'description' => 'Eliminar empleados'],
            ['name' => 'employees.export', 'group' => 'employees', 'description' => 'Exportar empleados'],

            // Equipos
            ['name' => 'equipment.view', 'group' => 'equipment', 'description' => 'Ver equipos'],
            ['name' => 'equipment.create', 'group' => 'equipment', 'description' => 'Crear equipos'],
            ['name' => 'equipment.edit', 'group' => 'equipment', 'description' => 'Editar equipos'],
            ['name' => 'equipment.delete', 'group' => 'equipment', 'description' => 'Eliminar equipos'],
            ['name' => 'equipment.export', 'group' => 'equipment', 'description' => 'Exportar equipos'],
            ['name' => 'equipment.import', 'group' => 'equipment', 'description' => 'Importar equipos'],

            // Asignaciones
            ['name' => 'assignments.view', 'group' => 'assignments', 'description' => 'Ver asignaciones'],
            ['name' => 'assignments.create', 'group' => 'assignments', 'description' => 'Crear asignaciones'],
            ['name' => 'assignments.edit', 'group' => 'assignments', 'description' => 'Editar asignaciones'],
            ['name' => 'assignments.delete', 'group' => 'assignments', 'description' => 'Eliminar asignaciones'],
            ['name' => 'assignments.return', 'group' => 'assignments', 'description' => 'Procesar devoluciones'],
            ['name' => 'assignments.transfer', 'group' => 'assignments', 'description' => 'Transferir equipos'],

            // Responsivas PDF
            ['name' => 'custody_letters.view', 'group' => 'custody_letters', 'description' => 'Ver responsivas'],
            ['name' => 'custody_letters.generate', 'group' => 'custody_letters', 'description' => 'Generar responsivas'],
            ['name' => 'custody_letters.download', 'group' => 'custody_letters', 'description' => 'Descargar responsivas'],

            // Historial
            ['name' => 'history.view', 'group' => 'history', 'description' => 'Ver historial'],
            ['name' => 'history.view_all', 'group' => 'history', 'description' => 'Ver todo el historial'],
            ['name' => 'history.export', 'group' => 'history', 'description' => 'Exportar historial'],

            // Mantenimiento
            ['name' => 'maintenance.view', 'group' => 'maintenance', 'description' => 'Ver mantenimientos'],
            ['name' => 'maintenance.create', 'group' => 'maintenance', 'description' => 'Crear mantenimientos'],
            ['name' => 'maintenance.edit', 'group' => 'maintenance', 'description' => 'Editar mantenimientos'],
            ['name' => 'maintenance.delete', 'group' => 'maintenance', 'description' => 'Eliminar mantenimientos'],

            // Reportes
            ['name' => 'reports.view', 'group' => 'reports', 'description' => 'Ver reportes'],
            ['name' => 'reports.equipment', 'group' => 'reports', 'description' => 'Reporte de equipos'],
            ['name' => 'reports.assignments', 'group' => 'reports', 'description' => 'Reporte de asignaciones'],
            ['name' => 'reports.employees', 'group' => 'reports', 'description' => 'Reporte de empleados'],
            ['name' => 'reports.export', 'group' => 'reports', 'description' => 'Exportar reportes'],

            // Catálogos
            ['name' => 'catalogs.view', 'group' => 'catalogs', 'description' => 'Ver catálogos'],
            ['name' => 'catalogs.manage', 'group' => 'catalogs', 'description' => 'Gestionar catálogos'],

            // Departamentos
            ['name' => 'departments.view', 'group' => 'departments', 'description' => 'Ver departamentos'],
            ['name' => 'departments.manage', 'group' => 'departments', 'description' => 'Gestionar departamentos'],

            // Ubicaciones
            ['name' => 'locations.view', 'group' => 'locations', 'description' => 'Ver ubicaciones'],
            ['name' => 'locations.manage', 'group' => 'locations', 'description' => 'Gestionar ubicaciones'],

            // Configuración
            ['name' => 'settings.view', 'group' => 'settings', 'description' => 'Ver configuración'],
            ['name' => 'settings.edit', 'group' => 'settings', 'description' => 'Editar configuración'],

            // Auditoría
            ['name' => 'audit.view', 'group' => 'audit', 'description' => 'Ver auditoría'],
            ['name' => 'audit.export', 'group' => 'audit', 'description' => 'Exportar auditoría'],

            // Roles y permisos
            ['name' => 'roles.view', 'group' => 'roles', 'description' => 'Ver roles'],
            ['name' => 'roles.manage', 'group' => 'roles', 'description' => 'Gestionar roles'],
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'guard_name' => 'web',
                'description' => $permission['description'],
                'group' => $permission['group'],
            ]);
        }

        // Crear roles
        $adminRole = Role::create([
            'name' => 'Administrador',
            'guard_name' => 'web',
            'description' => 'Acceso total al sistema',
            'color' => '#dc3545',
            'is_system' => true,
        ]);
        $adminRole->givePermissionTo(Permission::all());

        $supervisorRole = Role::create([
            'name' => 'Supervisor',
            'guard_name' => 'web',
            'description' => 'Supervisor de área / RH / Sistemas',
            'color' => '#fd7e14',
            'is_system' => true,
        ]);
        $supervisorRole->givePermissionTo([
            'dashboard.view',
            'dashboard.stats',
            'employees.view',
            'equipment.view',
            'equipment.export',
            'assignments.view',
            'assignments.create',
            'assignments.edit',
            'assignments.return',
            'assignments.transfer',
            'custody_letters.view',
            'custody_letters.generate',
            'custody_letters.download',
            'history.view',
            'history.view_all',
            'history.export',
            'maintenance.view',
            'maintenance.create',
            'maintenance.edit',
            'reports.view',
            'reports.equipment',
            'reports.assignments',
            'reports.employees',
            'reports.export',
            'catalogs.view',
            'departments.view',
            'locations.view',
        ]);

        $employeeRole = Role::create([
            'name' => 'Empleado',
            'guard_name' => 'web',
            'description' => 'Empleado con acceso limitado',
            'color' => '#198754',
            'is_system' => true,
        ]);
        $employeeRole->givePermissionTo([
            'dashboard.view',
            'equipment.view',
            'assignments.view',
            'custody_letters.view',
            'custody_letters.download',
            'history.view',
        ]);
    }
}
