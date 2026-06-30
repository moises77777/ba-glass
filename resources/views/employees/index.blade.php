@extends('layouts.app')

@section('title', 'Empleados')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Empleados</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Empleados</li>
            </ol>
        </nav>
    </div>
    @can('employees.create')
    <a href="{{ route('employees.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Nuevo Empleado
    </a>
    @endcan
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('employees.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="search" class="form-control" placeholder="Nombre, número, email..." value="{{ request('search') }}" id="liveSearch">
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
                <div class="col-md-3">
                    <label class="form-label">Puesto</label>
                    <select name="position_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($positions as $position)
                            <option value="{{ $position->id }}" {{ request('position_id') == $position->id ? 'selected' : '' }}>
                                {{ $position->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                        <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>Licencia</option>
                        <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Baja</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Employees Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>No. Empleado</th>
                        <th>Departamento</th>
                        <th>Puesto</th>
                        <th>Equipos</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody id="employeesTableBody">
                    @forelse($employees as $employee)
                    <tr>
                        <td>
                            <a href="{{ route('employees.show', $employee) }}" class="fw-medium text-decoration-none">
                                {{ $employee->full_name }}
                            </a>
                            <br><small class="text-muted">{{ $employee->email }}</small>
                        </td>
                        <td>{{ $employee->employee_number }}</td>
                        <td>{{ $employee->department->name }}</td>
                        <td>{{ $employee->position->name }}</td>
                        <td>
                            @if($employee->currentEquipment->count() > 0)
                                <span class="badge bg-primary">{{ $employee->currentEquipment->count() }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($employee->status == 'active')
                                <span class="badge bg-success">Activo</span>
                            @elseif($employee->status == 'inactive')
                                <span class="badge bg-secondary">Inactivo</span>
                            @elseif($employee->status == 'on_leave')
                                <span class="badge bg-warning">Licencia</span>
                            @else
                                <span class="badge bg-danger">Baja</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('employees.show', $employee) }}" class="btn btn-outline-primary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('employees.edit')
                                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-outline-secondary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                                @if($employee->status == 'active')
                                    @can('assignments.create')
                                    <a href="{{ route('assignments.create', ['employee_id' => $employee->id]) }}" class="btn btn-outline-success" title="Asignar Equipo">
                                        <i class="bi bi-laptop"></i>
                                    </a>
                                    @endcan
                                @endif
                                @can('employees.destroy')
                                <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este empleado?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-people fs-1 d-block mb-3"></i>
                                No se encontraron empleados
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($employees->hasPages())
    <div class="card-footer" id="paginationFooter">
        {{ $employees->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
let searchTimeout;

$(document).ready(function() {
    // Búsqueda en vivo con debounce
    $('#liveSearch').on('input', function() {
        clearTimeout(searchTimeout);
        const searchTerm = $(this).val();

        if (searchTerm.length === 0) {
            window.location.href = '{{ route('employees.index') }}';
            return;
        }

        searchTimeout = setTimeout(function() {
            performSearch(searchTerm);
        }, 500);
    });

    // Búsqueda también cuando cambian los filtros
    $('select[name="department_id"], select[name="status"]').on('change', function() {
        const searchTerm = $('#liveSearch').val();
        if (searchTerm.length > 0) {
            performSearch(searchTerm);
        }
    });
});

function performSearch(searchTerm) {
    const departmentId = $('select[name="department_id"]').val();
    const status = $('select[name="status"]').val();

    $('#employeesTableBody').html(buildSkeletonRows(7, 6));

    $.ajax({
        url: '{{ route('employees.search') }}',
        method: 'GET',
        data: {
            search: searchTerm,
            department_id: departmentId,
            status: status
        },
        success: function(response) {
            $('#employeesTableBody').html(response.data);
            $('#paginationFooter').html(response.pagination);
        },
        error: function() {
            $('#employeesTableBody').html(`
                <tr>
                    <td colspan="7" class="text-center py-5 text-danger">
                        <i class="bi bi-exclamation-triangle fs-1 d-block mb-3"></i>
                        Error al cargar los resultados
                    </td>
                </tr>
            `);
        }
    });
}
</script>
@endpush
