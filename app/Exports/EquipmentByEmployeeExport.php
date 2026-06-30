<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EquipmentByEmployeeExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected Collection $employees;

    public function __construct(Collection $employees)
    {
        $this->employees = $employees;
    }

    public function collection(): Collection
    {
        $rows = collect();

        foreach ($this->employees as $employee) {
            foreach ($employee->currentEquipment as $eq) {
                $rows->push([
                    $employee->employee_number,
                    $employee->full_name,
                    $employee->department?->name,
                    $employee->position?->name,
                    $eq->internal_code,
                    $eq->category?->name,
                    $eq->brand?->name,
                    $eq->model,
                    $eq->serial_number,
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'No. Empleado',
            'Empleado',
            'Departamento',
            'Puesto',
            'Código Equipo',
            'Categoría',
            'Marca',
            'Modelo',
            'No. Serie',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
