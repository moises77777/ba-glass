<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EquipmentExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected Collection $equipment;

    public function __construct(Collection $equipment)
    {
        $this->equipment = $equipment;
    }

    public function collection(): Collection
    {
        return $this->equipment;
    }

    public function headings(): array
    {
        return [
            'Código Interno',
            'Etiqueta',
            'Categoría',
            'Marca',
            'Modelo',
            'No. Serie',
            'Disponibilidad',
            'Condición',
            'Asignado a',
            'Departamento',
            'Ubicación',
            'Fecha Compra',
            'Precio',
            'Garantía Vence',
        ];
    }

    public function map($eq): array
    {
        return [
            $eq->internal_code,
            $eq->asset_tag,
            $eq->category?->name,
            $eq->brand?->name,
            $eq->model,
            $eq->serial_number,
            $eq->availability_status_name ?? $eq->availability_status,
            $eq->physical_condition_name ?? $eq->physical_condition,
            $eq->currentEmployee?->full_name,
            $eq->currentEmployee?->department?->name,
            $eq->location?->name,
            $eq->purchase_date?->format('d/m/Y'),
            $eq->purchase_price,
            $eq->warranty_end_date?->format('d/m/Y'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
