@extends('layouts.app')

@section('title', 'Directorio de Equipos Asignados')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Directorio de Equipos Asignados - BA Glass Mexico</h1>
        <p class="text-muted mb-0">Generado: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('employees.directory.pdf', request()->only(['search', 'department_id'])) }}" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf me-1"></i>Exportar PDF
        </a>
        <a href="{{ route('employees.directory.excel', request()->only(['search', 'department_id'])) }}" class="btn btn-success">
            <i class="bi bi-file-earmark-excel me-1"></i>Exportar Excel
        </a>
        <a href="{{ route('employees.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-people me-1"></i>Ver Empleados
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('employees.directory') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="search" class="form-control" placeholder="Nombre, numero, email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Departamento</label>
                    <select name="department_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Filtrar
                    </button>
                    <a href="{{ route('employees.directory') }}" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabla general de empleados con equipos -->
<div class="card mb-4">
    <div class="card-header" style="background: #4f46e5 !important; color: #fff;">
        <h5 class="mb-0" style="color:#fff;">Empleados con Equipos Asignados</h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0">
            <thead class="table-primary">
                <tr>
                    <th style="background:#4f46e5;color:#fff;">No. Empleado</th>
                    <th style="background:#4f46e5;color:#fff;">Nombre</th>
                    <th style="background:#4f46e5;color:#fff;">Departamento</th>
                    <th style="background:#4f46e5;color:#fff;">Puesto</th>
                    <th style="background:#4f46e5;color:#fff;">Equipo Asignado</th>
                    <th style="background:#4f46e5;color:#fff;">Codigo</th>
                    <th style="background:#4f46e5;color:#fff;">Marca / Modelo</th>
                    <th style="background:#4f46e5;color:#fff;">Fecha Asignacion</th>
                </tr>
            </thead>
            <tbody>
                @php $hasData = false; @endphp
                @foreach($employees as $employee)
                    @php
                        $activeAssignments = $employee->assignments->where('status', 'active');
                    @endphp
                    @if($activeAssignments->count() > 0)
                        @php $hasData = true; @endphp
                        @foreach($activeAssignments as $assignment)
                        <tr>
                            <td>{{ $employee->employee_number ?? '-' }}</td>
                            <td><strong>{{ $employee->full_name }}</strong></td>
                            <td>{{ $employee->department->name ?? '-' }}</td>
                            <td>{{ $employee->position->name ?? '-' }}</td>
                            <td>{{ $assignment->equipment->category->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('equipment.show', $assignment->equipment) }}">
                                    {{ $assignment->equipment->internal_code }}
                                </a>
                            </td>
                            <td>{{ $assignment->equipment->brand->name ?? '-' }} {{ $assignment->equipment->model ?? '' }}</td>
                            <td>{{ $assignment->assignment_date ? $assignment->assignment_date->format('d/m/Y') : '-' }}</td>
                        </tr>
                        @endforeach
                    @endif
                @endforeach
                @if(!$hasData)
                <tr>
                    <td colspan="8" class="text-center text-muted py-3">No hay empleados con equipos asignados.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Empleados sin equipo -->
<div class="card mb-4">
    <div class="card-header" style="background: #6b7280 !important; color: #fff;">
        <h5 class="mb-0" style="color:#fff;">Empleados sin Equipo Asignado</h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0">
            <thead class="table-secondary">
                <tr>
                    <th style="background:#6b7280;color:#fff;">No. Empleado</th>
                    <th style="background:#6b7280;color:#fff;">Nombre</th>
                    <th style="background:#6b7280;color:#fff;">Departamento</th>
                    <th style="background:#6b7280;color:#fff;">Puesto</th>
                    <th style="background:#6b7280;color:#fff;">Telefono</th>
                </tr>
            </thead>
            <tbody>
                @php $hasNoEquipment = false; @endphp
                @foreach($employees as $employee)
                    @php
                        $activeAssignments = $employee->assignments->where('status', 'active');
                    @endphp
                    @if($activeAssignments->count() === 0)
                        @php $hasNoEquipment = true; @endphp
                        <tr>
                            <td>{{ $employee->employee_number ?? '-' }}</td>
                            <td><strong>{{ $employee->full_name }}</strong></td>
                            <td>{{ $employee->department->name ?? '-' }}</td>
                            <td>{{ $employee->position->name ?? '-' }}</td>
                            <td>{{ $employee->phone ?? '-' }}</td>
                        </tr>
                    @endif
                @endforeach
                @if(!$hasNoEquipment)
                <tr>
                    <td colspan="5" class="text-center text-muted py-3">Todos los empleados tienen al menos un equipo asignado.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $employees->links() }}
</div>
@endsection
