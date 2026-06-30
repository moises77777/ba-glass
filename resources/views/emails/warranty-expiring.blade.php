<x-mail::message>
# Garantías Próximas a Vencer

Los siguientes equipos tienen su garantía próxima a vencer en los próximos **{{ $days }} días**:

<x-mail::table>
| Código | Equipo | No. Serie | Vence |
|:-------|:-------|:----------|:------|
@foreach($equipment as $eq)
| {{ $eq->internal_code }} | {{ $eq->brand?->name }} {{ $eq->model }} | {{ $eq->serial_number ?? 'N/A' }} | {{ $eq->warranty_end_date->format('d/m/Y') }} |
@endforeach
</x-mail::table>

Total de equipos: **{{ $equipment->count() }}**

<x-mail::button :url="route('reports.warranty-expiring')">
Ver Reporte Completo
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
