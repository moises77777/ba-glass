<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationsSeeder extends Seeder
{
    public function run(): void
    {
        // Edificio principal
        $edificioPrincipal = Location::create([
            'code' => 'EDIF-PRIN',
            'name' => 'Edificio Principal',
            'description' => 'Edificio corporativo principal',
            'type' => 'building',
            'address' => 'Calle Principal #123',
            'city' => 'Ciudad de México',
            'state' => 'CDMX',
            'postal_code' => '12345',
        ]);

        // Pisos del edificio principal
        $pisos = [
            ['code' => 'PB', 'name' => 'Planta Baja', 'type' => 'floor'],
            ['code' => 'P1', 'name' => 'Piso 1', 'type' => 'floor'],
            ['code' => 'P2', 'name' => 'Piso 2', 'type' => 'floor'],
            ['code' => 'P3', 'name' => 'Piso 3', 'type' => 'floor'],
            ['code' => 'P4', 'name' => 'Piso 4', 'type' => 'floor'],
            ['code' => 'P5', 'name' => 'Piso 5', 'type' => 'floor'],
        ];

        foreach ($pisos as $piso) {
            $pisoCreado = Location::create([
                'code' => $piso['code'],
                'name' => $piso['name'],
                'type' => $piso['type'],
                'parent_id' => $edificioPrincipal->id,
            ]);

            // Crear algunas oficinas por piso
            if ($piso['code'] === 'P1') {
                $oficinas = [
                    ['code' => 'P1-RH', 'name' => 'Oficina de Recursos Humanos', 'type' => 'room'],
                    ['code' => 'P1-VEN', 'name' => 'Oficina de Ventas', 'type' => 'room'],
                    ['code' => 'P1-REC', 'name' => 'Recepción', 'type' => 'room'],
                ];
            } elseif ($piso['code'] === 'P2') {
                $oficinas = [
                    ['code' => 'P2-TI', 'name' => 'Oficina de TI', 'type' => 'room'],
                    ['code' => 'P2-MKT', 'name' => 'Oficina de Marketing', 'type' => 'room'],
                    ['code' => 'P2-SALA1', 'name' => 'Sala de Juntas 1', 'type' => 'room'],
                ];
            } elseif ($piso['code'] === 'P3') {
                $oficinas = [
                    ['code' => 'P3-FIN', 'name' => 'Oficina de Finanzas', 'type' => 'room'],
                    ['code' => 'P3-CONT', 'name' => 'Oficina de Contabilidad', 'type' => 'room'],
                ];
            } elseif ($piso['code'] === 'P4') {
                $oficinas = [
                    ['code' => 'P4-LEG', 'name' => 'Oficina Legal', 'type' => 'room'],
                    ['code' => 'P4-SALA2', 'name' => 'Sala de Juntas 2', 'type' => 'room'],
                ];
            } elseif ($piso['code'] === 'P5') {
                $oficinas = [
                    ['code' => 'P5-DIR', 'name' => 'Dirección General', 'type' => 'room'],
                    ['code' => 'P5-SALA-DIR', 'name' => 'Sala de Consejo', 'type' => 'room'],
                ];
            } else {
                $oficinas = [
                    ['code' => 'PB-OPS', 'name' => 'Área de Operaciones', 'type' => 'area'],
                    ['code' => 'PB-ALM', 'name' => 'Almacén', 'type' => 'warehouse'],
                ];
            }

            foreach ($oficinas as $oficina) {
                Location::create([
                    'code' => $oficina['code'],
                    'name' => $oficina['name'],
                    'type' => $oficina['type'],
                    'parent_id' => $pisoCreado->id,
                ]);
            }
        }

        // Almacén de TI
        Location::create([
            'code' => 'ALM-TI',
            'name' => 'Almacén de Equipos TI',
            'description' => 'Almacén de equipos de cómputo y tecnología',
            'type' => 'warehouse',
            'parent_id' => $edificioPrincipal->id,
        ]);
    }
}
