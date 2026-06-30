@extends('layouts.app')
@section('title', 'Equipos por Empleado')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Equipos por Empleado</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reportes</a></li><li class="breadcrumb-item active">Por Empleado</li></ol></nav>
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
            <label class="form-label">Departamento</label>
            <select name="department_id" class="form-select">
                <option value="">Todos</option>
                @foreach($departments as $d)<option value="{{ $d->id }}" {{ request('department_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
        </div>
    </form>
</div></div>

@forelse($employees as $emp)
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <strong>{{ $emp->full_name }}</strong>
            <small class="text-muted">- {{ $emp->department?->name }} / {{ $emp->position?->name }}</small>
        </div>
        <span class="badge bg-info">{{ $emp->currentEquipment->count() }} equipo(s)</span>
    </div>
    <div class="card-body p-0"><div class="table-responsive">
    <table class="table table-sm mb-0">
        <thead><tr><th>Código</th><th>Categoría</th><th>Marca/Modelo</th><th>Serie</th></tr></thead>
        <tbody>
            @foreach($emp->currentEquipment as $eq)
            <tr>
                <td><a href="{{ route('equipment.show', $eq) }}">{{ $eq->internal_code }}</a></td>
                <td>{{ $eq->category?->name }}</td>
                <td>{{ $eq->brand?->name }} {{ $eq->model }}</td>
                <td><code>{{ $eq->serial_number }}</code></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div></div>
</div>
@empty
<div class="alert alert-info">No hay empleados con equipos asignados</div>
@endforelse
@endsection
