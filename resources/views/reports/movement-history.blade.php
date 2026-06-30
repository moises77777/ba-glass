@extends('layouts.app')
@section('title', 'Historial de Movimientos')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Historial de Movimientos</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reportes</a></li><li class="breadcrumb-item active">Historial</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success"><i class="bi bi-file-earmark-excel me-1"></i>Exportar Excel</a>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger"><i class="bi bi-file-earmark-pdf me-1"></i>Exportar PDF</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><h3 class="text-primary">{{ $stats['total_movements'] }}</h3><small>Total Movimientos</small></div></div></div>
    @foreach($stats['by_type'] as $type => $count)
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><h3 class="text-info">{{ $count }}</h3><small>{{ $movementTypes[$type] ?? ucfirst($type) }}</small></div></div></div>
    @endforeach
</div>

<div class="card mb-4"><div class="card-body">
    <form method="GET" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Desde</label>
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Hasta</label>
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Tipo</label>
            <select name="movement_type" class="form-select">
                <option value="">Todos</option>
                @foreach($movementTypes as $key => $label)
                    <option value="{{ $key }}" {{ request('movement_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
        </div>
    </form>
</div></div>

<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead><tr><th>Fecha</th><th>Tipo</th><th>Equipo</th><th>De</th><th>A</th><th>Realizado por</th><th>Notas</th></tr></thead>
    <tbody>
        @forelse($history as $h)
        <tr>
            <td><small>{{ $h->performed_at?->format('d/m/Y H:i') }}</small></td>
            <td><span class="badge bg-info">{{ $movementTypes[$h->movement_type] ?? $h->movement_type }}</span></td>
            <td><a href="{{ route('equipment.show', $h->equipment) }}">{{ $h->equipment?->internal_code }}</a></td>
            <td>{{ $h->previousEmployee?->full_name ?? '-' }}</td>
            <td>{{ $h->newEmployee?->full_name ?? '-' }}</td>
            <td>{{ $h->performedBy?->name ?? '-' }}</td>
            <td><small>{{ Str::limit($h->notes, 50) }}</small></td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center py-4 text-muted">Sin movimientos</td></tr>
        @endforelse
    </tbody>
</table>
</div></div>
@if($history->hasPages())<div class="card-footer">{{ $history->links() }}</div>@endif
</div>
@endsection
