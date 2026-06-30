<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Configuración de la empresa
            [
                'group' => 'company',
                'key' => 'company_name',
                'value' => 'Mi Empresa S.A. de C.V.',
                'type' => 'string',
                'label' => 'Nombre de la empresa',
                'description' => 'Nombre oficial de la empresa',
                'is_public' => true,
                'sort_order' => 1,
            ],
            [
                'group' => 'company',
                'key' => 'company_rfc',
                'value' => 'ABC123456789',
                'type' => 'string',
                'label' => 'RFC',
                'description' => 'RFC de la empresa',
                'is_public' => true,
                'sort_order' => 2,
            ],
            [
                'group' => 'company',
                'key' => 'company_address',
                'value' => 'Calle Principal #123, Col. Centro, CP 12345, Ciudad de México',
                'type' => 'string',
                'label' => 'Dirección',
                'description' => 'Dirección fiscal de la empresa',
                'is_public' => true,
                'sort_order' => 3,
            ],
            [
                'group' => 'company',
                'key' => 'company_phone',
                'value' => '+52 (55) 1234-5678',
                'type' => 'string',
                'label' => 'Teléfono',
                'description' => 'Teléfono principal',
                'is_public' => true,
                'sort_order' => 4,
            ],
            [
                'group' => 'company',
                'key' => 'company_email',
                'value' => 'contacto@baglass.com',
                'type' => 'string',
                'label' => 'Correo electrónico',
                'description' => 'Correo electrónico de contacto',
                'is_public' => true,
                'sort_order' => 5,
            ],
            [
                'group' => 'company',
                'key' => 'company_logo',
                'value' => null,
                'type' => 'string',
                'label' => 'Logo de la empresa',
                'description' => 'Ruta del logo de la empresa',
                'is_public' => true,
                'sort_order' => 6,
            ],

            // Configuración del sistema
            [
                'group' => 'system',
                'key' => 'items_per_page',
                'value' => '15',
                'type' => 'integer',
                'label' => 'Elementos por página',
                'description' => 'Cantidad de elementos a mostrar en las tablas',
                'options' => json_encode([10, 15, 25, 50, 100]),
                'sort_order' => 1,
            ],
            [
                'group' => 'system',
                'key' => 'date_format',
                'value' => 'd/m/Y',
                'type' => 'string',
                'label' => 'Formato de fecha',
                'description' => 'Formato para mostrar fechas',
                'options' => json_encode(['d/m/Y', 'Y-m-d', 'm/d/Y', 'd-m-Y']),
                'sort_order' => 2,
            ],
            [
                'group' => 'system',
                'key' => 'datetime_format',
                'value' => 'd/m/Y H:i',
                'type' => 'string',
                'label' => 'Formato de fecha y hora',
                'description' => 'Formato para mostrar fecha y hora',
                'sort_order' => 3,
            ],
            [
                'group' => 'system',
                'key' => 'currency',
                'value' => 'MXN',
                'type' => 'string',
                'label' => 'Moneda',
                'description' => 'Moneda predeterminada',
                'options' => json_encode(['MXN', 'USD', 'EUR']),
                'sort_order' => 4,
            ],

            // Configuración de equipos
            [
                'group' => 'equipment',
                'key' => 'code_prefix',
                'value' => 'EQ',
                'type' => 'string',
                'label' => 'Prefijo de código',
                'description' => 'Prefijo para códigos de equipos',
                'sort_order' => 1,
            ],
            [
                'group' => 'equipment',
                'key' => 'auto_generate_code',
                'value' => 'true',
                'type' => 'boolean',
                'label' => 'Generar código automáticamente',
                'description' => 'Generar código interno automáticamente',
                'sort_order' => 2,
            ],
            [
                'group' => 'equipment',
                'key' => 'require_serial_number',
                'value' => 'true',
                'type' => 'boolean',
                'label' => 'Requerir número de serie',
                'description' => 'El número de serie es obligatorio',
                'sort_order' => 3,
            ],

            // Configuración de responsivas
            [
                'group' => 'custody_letter',
                'key' => 'folio_prefix',
                'value' => 'RES',
                'type' => 'string',
                'label' => 'Prefijo de folio',
                'description' => 'Prefijo para folios de responsivas',
                'sort_order' => 1,
            ],
            [
                'group' => 'custody_letter',
                'key' => 'include_qr',
                'value' => 'true',
                'type' => 'boolean',
                'label' => 'Incluir código QR',
                'description' => 'Incluir código QR en las responsivas',
                'sort_order' => 2,
            ],
            [
                'group' => 'custody_letter',
                'key' => 'terms_and_conditions',
                'value' => 'El empleado se compromete a hacer buen uso del equipo asignado, reportar cualquier daño o mal funcionamiento de manera inmediata, y devolverlo en las mismas condiciones en que fue recibido al término de su relación laboral o cuando le sea requerido.',
                'type' => 'text',
                'label' => 'Términos y condiciones',
                'description' => 'Texto de términos y condiciones para responsivas',
                'sort_order' => 3,
            ],

            // Configuración de notificaciones
            [
                'group' => 'notifications',
                'key' => 'warranty_alert_days',
                'value' => '30',
                'type' => 'integer',
                'label' => 'Días de alerta de garantía',
                'description' => 'Días antes de vencimiento para alertar',
                'sort_order' => 1,
            ],
            [
                'group' => 'notifications',
                'key' => 'maintenance_reminder_days',
                'value' => '7',
                'type' => 'integer',
                'label' => 'Días de recordatorio de mantenimiento',
                'description' => 'Días antes para recordar mantenimiento',
                'sort_order' => 2,
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::create($setting);
        }
    }
}
