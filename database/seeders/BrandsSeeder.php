<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandsSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Dell',
                'description' => 'Fabricante de computadoras y equipos de cómputo',
                'website' => 'https://www.dell.com',
                'support_phone' => '1-800-624-9896',
                'support_email' => 'support@dell.com',
            ],
            [
                'name' => 'HP',
                'description' => 'Hewlett-Packard - Computadoras y periféricos',
                'website' => 'https://www.hp.com',
                'support_phone' => '1-800-474-6836',
                'support_email' => 'support@hp.com',
            ],
            [
                'name' => 'Lenovo',
                'description' => 'Fabricante de computadoras ThinkPad e IdeaPad',
                'website' => 'https://www.lenovo.com',
                'support_phone' => '1-855-253-6686',
                'support_email' => 'support@lenovo.com',
            ],
            [
                'name' => 'Apple',
                'description' => 'MacBooks, iMacs y dispositivos Apple',
                'website' => 'https://www.apple.com',
                'support_phone' => '1-800-275-2273',
                'support_email' => 'support@apple.com',
            ],
            [
                'name' => 'ASUS',
                'description' => 'Computadoras y componentes',
                'website' => 'https://www.asus.com',
                'support_phone' => '1-812-282-2787',
                'support_email' => 'support@asus.com',
            ],
            [
                'name' => 'Acer',
                'description' => 'Laptops y monitores',
                'website' => 'https://www.acer.com',
                'support_phone' => '1-866-695-2237',
                'support_email' => 'support@acer.com',
            ],
            [
                'name' => 'Microsoft',
                'description' => 'Surface y accesorios',
                'website' => 'https://www.microsoft.com',
                'support_phone' => '1-800-642-7676',
                'support_email' => 'support@microsoft.com',
            ],
            [
                'name' => 'Samsung',
                'description' => 'Monitores, tablets y dispositivos',
                'website' => 'https://www.samsung.com',
                'support_phone' => '1-800-726-7864',
                'support_email' => 'support@samsung.com',
            ],
            [
                'name' => 'LG',
                'description' => 'Monitores y pantallas',
                'website' => 'https://www.lg.com',
                'support_phone' => '1-800-243-0000',
                'support_email' => 'support@lg.com',
            ],
            [
                'name' => 'Logitech',
                'description' => 'Periféricos y accesorios',
                'website' => 'https://www.logitech.com',
                'support_phone' => '1-646-454-3200',
                'support_email' => 'support@logitech.com',
            ],
            [
                'name' => 'Cisco',
                'description' => 'Equipos de red y comunicaciones',
                'website' => 'https://www.cisco.com',
                'support_phone' => '1-800-553-2447',
                'support_email' => 'support@cisco.com',
            ],
            [
                'name' => 'Epson',
                'description' => 'Impresoras y proyectores',
                'website' => 'https://www.epson.com',
                'support_phone' => '1-800-463-7766',
                'support_email' => 'support@epson.com',
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
