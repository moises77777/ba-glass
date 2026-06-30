<?php

namespace Database\Seeders;

use App\Models\EquipmentCategory;
use Illuminate\Database\Seeder;

class EquipmentCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        // Solo 2 categorías: Laptop y PC de Escritorio
        EquipmentCategory::create([
            'code' => 'LAPTOP',
            'name' => 'Laptop',
            'description' => 'Computadora portátil',
            'icon' => 'bi-laptop',
            'color' => '#0d6efd',
            'sort_order' => 1,
            'requires_serial' => true,
        ]);

        EquipmentCategory::create([
            'code' => 'DESKTOP',
            'name' => 'PC de Escritorio',
            'description' => 'Computadora de escritorio',
            'icon' => 'bi-pc-display',
            'color' => '#0d6efd',
            'sort_order' => 2,
            'requires_serial' => true,
        ]);
    }
}
