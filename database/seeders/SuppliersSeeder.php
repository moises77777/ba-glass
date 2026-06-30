<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SuppliersSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'code' => 'PROV-001',
                'name' => 'Tecnología Avanzada S.A. de C.V.',
                'rfc' => 'TAV123456789',
                'contact_name' => 'Juan Pérez García',
                'email' => 'ventas@tecavanzada.com',
                'phone' => '(55) 1234-5678',
                'mobile' => '(55) 9876-5432',
                'address' => 'Av. Insurgentes Sur #1234',
                'city' => 'Ciudad de México',
                'state' => 'CDMX',
                'postal_code' => '03100',
                'website' => 'https://www.tecavanzada.com',
                'notes' => 'Proveedor principal de equipos de cómputo',
            ],
            [
                'code' => 'PROV-002',
                'name' => 'Soluciones Informáticas del Norte',
                'rfc' => 'SIN987654321',
                'contact_name' => 'María López Hernández',
                'email' => 'contacto@solinformaticas.com',
                'phone' => '(81) 8765-4321',
                'mobile' => '(81) 1234-5678',
                'address' => 'Blvd. Constitución #567',
                'city' => 'Monterrey',
                'state' => 'Nuevo León',
                'postal_code' => '64000',
                'website' => 'https://www.solinformaticas.com',
                'notes' => 'Especialistas en servidores y redes',
            ],
            [
                'code' => 'PROV-003',
                'name' => 'CompuMex Distribuidora',
                'rfc' => 'CMD456789123',
                'contact_name' => 'Roberto Sánchez Martínez',
                'email' => 'ventas@compumex.com.mx',
                'phone' => '(33) 3456-7890',
                'mobile' => '(33) 9012-3456',
                'address' => 'Av. Vallarta #890',
                'city' => 'Guadalajara',
                'state' => 'Jalisco',
                'postal_code' => '44100',
                'website' => 'https://www.compumex.com.mx',
                'notes' => 'Distribuidor autorizado Dell y HP',
            ],
            [
                'code' => 'PROV-004',
                'name' => 'Servicios Técnicos Especializados',
                'rfc' => 'STE789123456',
                'contact_name' => 'Ana García Ruiz',
                'email' => 'servicio@stecnicos.com',
                'phone' => '(55) 5678-9012',
                'mobile' => '(55) 3456-7890',
                'address' => 'Calle Reforma #234',
                'city' => 'Ciudad de México',
                'state' => 'CDMX',
                'postal_code' => '06600',
                'website' => 'https://www.stecnicos.com',
                'notes' => 'Servicio de mantenimiento y reparación',
            ],
            [
                'code' => 'PROV-005',
                'name' => 'Apple Premium Reseller México',
                'rfc' => 'APR321654987',
                'contact_name' => 'Carlos Mendoza López',
                'email' => 'ventas@applepremium.mx',
                'phone' => '(55) 2345-6789',
                'mobile' => '(55) 8901-2345',
                'address' => 'Av. Presidente Masaryk #456',
                'city' => 'Ciudad de México',
                'state' => 'CDMX',
                'postal_code' => '11560',
                'website' => 'https://www.applepremium.mx',
                'notes' => 'Distribuidor autorizado Apple',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
