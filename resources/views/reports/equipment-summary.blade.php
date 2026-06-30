@extends('layouts.app')
@section('title', 'Resumen de Equipos')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Resumen de Equipos</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reportes</a></li><li class="breadcrumb-item active">Resumen de Equipos</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success"><i class="bi bi-file-earmark-excel me-1"></i>Exportar Excel</a>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger"><i class="bi bi-file-earmark-pdf me-1"></i>Exportar PDF</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><h3 class="text-primary">{{ $stats['total'] }}</h3><small>Total Equipos</small></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><h3 class="text-success">${{ number_format($stats['total_value'], 0) }}</h3><small>Valor Total</small></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><h3 class="text-info">{{ $stats['by_status']->count() }}</h3><small>Estados</small></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><h3 class="text-warning">{{ $stats['by_category']->count() }}</h3><small>Categorías</small></div></div></div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Categoría</label>
                <select name="category_id" class="form-select">
                    <option value="">Todas</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Estado</label>
                <select name="availability_status" class="form-select">
                    <option value="">Todos</option>
                    <option value="available" {{ request('availability_status') == 'available' ? 'selected' : '' }}>Disponible</option>
                    <option value="assigned" {{ request('availability_status') == 'assigned' ? 'selected' : '' }}>Asignado</option>
                    <option value="maintenance" {{ request('availability_status') == 'maintenance' ? 'selected' : '' }}>En Mantenimiento</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Condición</label>
                <select name="physical_condition" class="form-select">
                    <option value="">Todas</option>
                    <option value="new" {{ request('physical_condition') == 'new' ? 'selected' : '' }}>Nuevo</option>
                    <option value="good" {{ request('physical_condition') == 'good' ? 'selected' : '' }}>Bueno</option>
                    <option value="fair" {{ request('physical_condition') == 'fair' ? 'selected' : '' }}>Regular</option>
                    <option value="poor" {{ request('physical_condition') == 'poor' ? 'selected' : '' }}>Malo</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search"></i> Filtrar</button>
                <a href="{{ route('reports.equipment-summary') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead><tr><th>Código</th><th>Categoría</th><th>Marca/Modelo</th><th>Serie</th><th>Estado</th><th>Condición</th><th>Empleado</th><th class="text-end">Valor</th></tr></thead>
        <tbody>
            @forelse($equipment as $eq)
            <tr>
                <td><a href="{{ route('equipment.show', $eq) }}">{{ $eq->internal_code }}</a></td>
                <td>{{ $eq->category?->name }}</td>
                <td>{{ $eq->brand?->name }} {{ $eq->model }}</td>
                <td><code>{{ $eq->serial_number }}</code></td>
                <td>{{ ucfirst($eq->availability_status) }}</td>
                <td>{{ ucfirst($eq->physical_condition) }}</td>
                <td>{{ $eq->currentEmployee?->full_name ?? '-' }}</td>
                <td class="text-end">${{ number_format($eq->purchase_price ?? 0, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-4 text-muted">No se encontraron equipos</td></tr>
            @endforelse
        </tbody>
    </table>
    </div></div>
</div>
@endsection
