<x-mail::message>
# Nueva Asignación de Equipo

Se ha registrado una nueva asignación de equipo en el sistema de inventario.

**Folio:** {{ $assignment->assignment_number }}
@if($assignment->custody_letter_folio)
**Carta Responsiva:** {{ $assignment->custody_letter_folio }}
@endif

## Detalles del Equipo
- **Código:** {{ $assignment->equipment->internal_code }}
- **Equipo:** {{ $assignment->equipment->brand?->name }} {{ $assignment->equipment->model }}
- **No. Serie:** {{ $assignment->equipment->serial_number ?? 'N/A' }}

## Empleado Asignado
- **Nombre:** {{ $assignment->employee->full_name }}
- **Departamento:** {{ $assignment->employee->department->name }}
- **Fecha de Asignación:** {{ $assignment->assignment_date->format('d/m/Y H:i') }}

<x-mail::button :url="route('assignments.show', $assignment)">
Ver Asignación
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
