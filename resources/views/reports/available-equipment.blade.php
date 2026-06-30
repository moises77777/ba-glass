@extends('layouts.app')
@section('title', 'Equipos Disponibles')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Equipos Disponibles</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reportes</a></li><li class="breadcrumb-item active">Disponibles</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success"><i class="bi bi-file-earmark-excel me-1"></i>Exportar Excel</a>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger"><i class="bi bi-file-earmark-pdf me-1"></i>Exportar PDF</a>
    </div>
</div>

<div class="card mb-4"><div class="card-body">
    <form method="GET" class="row g-3">
        <div class="col-md-5">
            <label class="form-label">Categoría</label>
            <select name="category_id" class="form-select">
                <option value="">Todas</option>
                @foreach($categories as $c)<option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-5">
            <label class="form-label">Estado Operativo</label>
            <select name="operational_status" class="form-select">
                <option value="">Todos</option>
                <option value="operational" {{ request('operational_status') == 'operational' ? 'selected' : '' }}>Operativo</option>
                <option value="needs_repair" {{ request('operational_status') == 'needs_repair' ? 'selected' : '' }}>Requiere Reparación</option>
                <option value="broken" {{ request('operational_status') == 'broken' ? 'selected' : '' }}>Dañado</option>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
        </div>
    </form>
</div></div>

<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead><tr><th>Código</th><th>Categoría</th><th>Marca/Modelo</th><th>Serie</th><th>Condición</th><th>Ubicación</th></tr></thead>
    <tbody>
        @forelse($equipment as $eq)
        <tr>
            <td><a href="{{ route('equipment.show', $eq) }}">{{ $eq->internal_code }}</a></td>
            <td>{{ $eq->category?->name }}</td>
            <td>{{ $eq->brand?->name }} {{ $eq->model }}</td>
            <td><code>{{ $eq->serial_number }}</code></td>
            <td>{{ ucfirst($eq->physical_condition) }}</td>
            <td>{{ $eq->location?->name ?? '-' }}</td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center py-4 text-muted">No hay equipos disponibles</td></tr>
        @endforelse
    </tbody>
</table>
</div></div></div>
@endsection
