<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            SystemSettingsSeeder::class,
            DepartmentsSeeder::class,
            PositionsSeeder::class,
            LocationsSeeder::class,
            BrandsSeeder::class,
            SuppliersSeeder::class,
            EquipmentCategoriesSeeder::class,
            UsersSeeder::class,
            EmployeesSeeder::class,
            EquipmentSeeder::class,
            AssignmentsSeeder::class,
        ]);
    }
}
