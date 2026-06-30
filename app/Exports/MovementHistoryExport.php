<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MovementHistoryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected Collection $history;

    public function __construct(Collection $history)
    {
        $this->history = $history;
    }

    public function collection(): Collection
    {
        return $this->history;
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Tipo de Movimiento',
            'Equipo',
            'Empleado Anterior',
            'Empleado Nuevo',
            'Realizado por',
            'Notas',
        ];
    }

    public function map($h): array
    {
        return [
            $h->performed_at?->format('d/m/Y H:i'),
            \App\Models\EquipmentHistory::MOVEMENT_TYPES[$h->movement_type] ?? $h->movement_type,
            $h->equipment?->internal_code,
            $h->previousEmployee?->full_name,
            $h->newEmployee?->full_name,
            $h->performedBy?->name,
            $h->notes,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
