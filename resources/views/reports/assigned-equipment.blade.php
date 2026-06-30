@extends('layouts.app')
@section('title', 'Equipos Asignados')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Equipos Asignados</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reportes</a></li><li class="breadcrumb-item active">Equipos Asignados</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success"><i class="bi bi-file-earmark-excel me-1"></i>Exportar Excel</a>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger"><i class="bi bi-file-earmark-pdf me-1"></i>Exportar PDF</a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Departamento</label>
                <select name="department_id" class="form-select">
                    <option value="">Todos</option>
                    @foreach($departments as $d)<option value="{{ $d->id }}" {{ request('department_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Categoría</label>
                <select name="category_id" class="form-select">
                    <option value="">Todas</option>
                    @foreach($categories as $c)<option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search"></i> Filtrar</button>
                <a href="{{ route('reports.assigned-equipment') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

@foreach($byDepartment as $deptName => $items)
<div class="card mb-3">
    <div class="card-header" style="background:#4f46e5 !important; color:#fff;">
        <i class="bi bi-building me-2"></i>{{ $deptName }} <span class="badge bg-light text-dark ms-2">{{ $items->count() }}</span>
    </div>
    <div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead><tr><th>Código</th><th>Equipo</th><th>Empleado</th><th>Puesto</th><th>Asignación</th></tr></thead>
        <tbody>
            @foreach($items as $eq)
            <tr>
                <td><a href="{{ route('equipment.show', $eq) }}">{{ $eq->internal_code }}</a></td>
                <td>{{ $eq->brand?->name }} {{ $eq->model }} <br><small class="text-muted">{{ $eq->category?->name }}</small></td>
                <td>{{ $eq->currentEmployee?->full_name }}</td>
                <td>{{ $eq->currentEmployee?->position?->name }}</td>
                <td>{{ $eq->activeAssignment?->assignment_date?->format('d/m/Y') ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div></div>
</div>
@endforeach

@if($equipment->isEmpty())
    <div class="alert alert-info">No hay equipos asignados</div>
@endif
@endsection
