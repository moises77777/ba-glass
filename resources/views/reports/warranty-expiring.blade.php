@extends('layouts.app')
@section('title', 'Garantías por Vencer')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Garantías por Vencer</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reportes</a></li><li class="breadcrumb-item active">Garantías</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success"><i class="bi bi-file-earmark-excel me-1"></i>Exportar Excel</a>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger"><i class="bi bi-file-earmark-pdf me-1"></i>Exportar PDF</a>
    </div>
</div>

<div class="card mb-4"><div class="card-body">
    <form method="GET" class="row g-3">
        <div class="col-md-10">
            <label class="form-label">Próximos días a vencer</label>
            <select name="days" class="form-select">
                <option value="7" {{ $days == 7 ? 'selected' : '' }}>7 días</option>
                <option value="15" {{ $days == 15 ? 'selected' : '' }}>15 días</option>
                <option value="30" {{ $days == 30 ? 'selected' : '' }}>30 días</option>
                <option value="60" {{ $days == 60 ? 'selected' : '' }}>60 días</option>
                <option value="90" {{ $days == 90 ? 'selected' : '' }}>90 días</option>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end"><button type="submit" class="btn btn-primary w-100">Filtrar</button></div>
    </form>
</div></div>

<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead><tr><th>Código</th><th>Equipo</th><th>Empleado</th><th>Inicio Garantía</th><th>Fin Garantía</th><th>Días Restantes</th></tr></thead>
    <tbody>
        @forelse($equipment as $eq)
        @php $daysLeft = now()->diffInDays($eq->warranty_end_date, false); @endphp
        <tr class="{{ $daysLeft <= 7 ? 'table-danger' : ($daysLeft <= 15 ? 'table-warning' : '') }}">
            <td><a href="{{ route('equipment.show', $eq) }}">{{ $eq->internal_code }}</a></td>
            <td>{{ $eq->brand?->name }} {{ $eq->model }}<br><small class="text-muted">{{ $eq->category?->name }}</small></td>
            <td>{{ $eq->currentEmployee?->full_name ?? '-' }}</td>
            <td>{{ $eq->warranty_start_date?->format('d/m/Y') ?? '-' }}</td>
            <td><strong>{{ $eq->warranty_end_date?->format('d/m/Y') }}</strong></td>
            <td><span class="badge bg-{{ $daysLeft <= 7 ? 'danger' : ($daysLeft <= 15 ? 'warning' : 'info') }}">{{ (int)$daysLeft }} días</span></td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center py-4 text-muted">No hay garantías por vencer en los próximos {{ $days }} días</td></tr>
        @endforelse
    </tbody>
</table>
</div></div></div>
@endsection
