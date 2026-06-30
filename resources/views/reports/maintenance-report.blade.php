@extends('layouts.app')
@section('title', 'Reporte de Mantenimiento')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Reporte de Mantenimiento</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reportes</a></li><li class="breadcrumb-item active">Mantenimiento</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success"><i class="bi bi-file-earmark-excel me-1"></i>Exportar Excel</a>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger"><i class="bi bi-file-earmark-pdf me-1"></i>Exportar PDF</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><h3 class="text-primary">{{ $stats['total'] }}</h3><small>Total</small></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><h3 class="text-warning">{{ $stats['pending'] }}</h3><small>Pendientes</small></div></div></div>
    <div class="col-md-2"><div class="card text-center"><div class="card-body"><h3 class="text-info">{{ $stats['in_progress'] }}</h3><small>En Progreso</small></div></div></div>
    <div class="col-md-2"><div class="card text-center"><div class="card-body"><h3 class="text-success">{{ $stats['completed'] }}</h3><small>Completados</small></div></div></div>
    <div class="col-md-2"><div class="card text-center"><div class="card-body"><h3 class="text-success">${{ number_format($stats['total_cost'] ?? 0, 0) }}</h3><small>Costo Total</small></div></div></div>
</div>

<div class="card mb-4"><div class="card-body">
    <form method="GET" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Estado</label>
            <select name="status" class="form-select">
                <option value="">Todos</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Tipo</label>
            <select name="type" class="form-select">
                <option value="">Todos</option>
                <option value="preventive" {{ request('type') == 'preventive' ? 'selected' : '' }}>Preventivo</option>
                <option value="corrective" {{ request('type') == 'corrective' ? 'selected' : '' }}>Correctivo</option>
            </select>
        </div>
        <div class="col-md-2"><label class="form-label">Desde</label><input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}"></div>
        <div class="col-md-2"><label class="form-label">Hasta</label><input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}"></div>
        <div class="col-md-2 d-flex align-items-end"><button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button></div>
    </form>
</div></div>

<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead><tr><th>Folio</th><th>Equipo</th><th>Tipo</th><th>Reportado</th><th>Estado</th><th class="text-end">Costo</th></tr></thead>
    <tbody>
        @forelse($records as $r)
        <tr>
            <td><a href="{{ route('maintenance.show', $r) }}">{{ $r->folio ?? '#' . $r->id }}</a></td>
            <td>{{ $r->equipment?->internal_code }} - {{ $r->equipment?->category?->name }}</td>
            <td>{{ ucfirst($r->type) }}</td>
            <td>{{ $r->reported_at?->format('d/m/Y') }}</td>
            <td><span class="badge bg-secondary">{{ ucfirst($r->status) }}</span></td>
            <td class="text-end">${{ number_format($r->total_cost ?? $r->cost ?? 0, 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center py-4 text-muted">No hay registros</td></tr>
        @endforelse
    </tbody>
</table>
</div></div>
@if($records->hasPages())<div class="card-footer">{{ $records->links() }}</div>@endif
</div>
@endsection
