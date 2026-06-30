<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WarrantyExpiringExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
            'Categoría',
            'Marca',
            'Modelo',
            'No. Serie',
            'Asignado a',
            'Fin de Garantía',
            'Días Restantes',
        ];
    }

    public function map($eq): array
    {
        return [
            $eq->internal_code,
            $eq->category?->name,
            $eq->brand?->name,
            $eq->model,
            $eq->serial_number,
            $eq->currentEmployee?->full_name,
            $eq->warranty_end_date?->format('d/m/Y'),
            $eq->warranty_days_remaining,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
