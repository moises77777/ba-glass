<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MaintenanceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected Collection $records;

    public function __construct(Collection $records)
    {
        $this->records = $records;
    }

    public function collection(): Collection
    {
        return $this->records;
    }

    public function headings(): array
    {
        return [
            'Ticket',
            'Equipo',
            'Tipo',
            'Prioridad',
            'Estado',
            'Reportado por',
            'Asignado a',
            'Proveedor',
            'Fecha Reporte',
            'Costo Total',
        ];
    }

    public function map($m): array
    {
        return [
            $m->ticket_number,
            $m->equipment?->internal_code,
            $m->type_name ?? $m->type,
            $m->priority_name ?? $m->priority,
            $m->status_name ?? $m->status,
            $m->reporter?->name,
            $m->assignee?->name,
            $m->supplier?->name,
            $m->reported_at?->format('d/m/Y'),
            $m->total_cost,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
