<x-mail::message>
# Mantenimiento Programado

Se ha registrado un mantenimiento para el siguiente equipo.

**Ticket:** {{ $maintenance->ticket_number }}

## Detalles del Equipo
- **Código:** {{ $maintenance->equipment->internal_code }}
- **Equipo:** {{ $maintenance->equipment->brand?->name }} {{ $maintenance->equipment->model }}

## Detalles del Mantenimiento
- **Tipo:** {{ $maintenance->type_name ?? $maintenance->type }}
- **Prioridad:** {{ $maintenance->priority_name ?? $maintenance->priority }}
- **Estado:** {{ $maintenance->status_name ?? $maintenance->status }}
@if($maintenance->reported_at)
- **Fecha de Reporte:** {{ $maintenance->reported_at->format('d/m/Y H:i') }}
@endif

<x-mail::button :url="route('maintenance.show', $maintenance)">
Ver Mantenimiento
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
