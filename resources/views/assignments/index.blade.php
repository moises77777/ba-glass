@extends('layouts.app')

@section('title', 'Asignaciones')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Asignaciones</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Asignaciones</li>
            </ol>
        </nav>
    </div>
    @can('assignments.create')
    <a href="{{ route('assignments.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Nueva Asignación
    </a>
    @endcan
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('assignments.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="search" class="form-control" placeholder="Folio, equipo, empleado..." value="{{ request('search') }}" id="liveSearch">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        @foreach(\App\Models\Assignment::STATUSES as $key => $value)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Empleado</label>
                    <select name="employee_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Desde</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Hasta</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                        <a href="{{ route('assignments.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Assignments Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Equipo</th>
                        <th>Empleado</th>
                        <th>Departamento</th>
                        <th>Fecha Asignación</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody id="assignmentsTableBody">
                    @forelse($assignments as $assignment)
                    <tr>
                        <td>
                            <a href="{{ route('assignments.show', $assignment) }}" class="fw-medium text-decoration-none">
                                {{ $assignment->assignment_number }}
                            </a>
                            @if($assignment->custody_letter_folio)
                                <br><small class="text-muted">{{ $assignment->custody_letter_folio }}</small>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('equipment.show', $assignment->equipment) }}" class="text-decoration-none">
                                {{ $assignment->equipment->internal_code }}
                            </a>
                            <br><small class="text-muted">{{ $assignment->equipment->brand?->name }} {{ $assignment->equipment->model }}</small>
                        </td>
                        <td>
                            <a href="{{ route('employees.show', $assignment->employee) }}" class="text-decoration-none">
                                {{ $assignment->employee->full_name }}
                            </a>
                        </td>
                        <td>{{ $assignment->employee->department->name }}</td>
                        <td>
                            {{ $assignment->assignment_date->format('d/m/Y H:i') }}
                            @if($assignment->actual_return_date)
                                <br><small class="text-muted">Dev: {{ $assignment->actual_return_date->format('d/m/Y') }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $assignment->status_badge_color }}">
                                {{ $assignment->status_name }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('assignments.show', $assignment) }}" class="btn btn-outline-primary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('assignments.pdf', $assignment) }}" class="btn btn-outline-danger" title="PDF">
                                    <i class="bi bi-file-pdf"></i>
                                </a>
                                @if($assignment->isActive())
                                    @can('assignments.return')
                                    <a href="{{ route('assignments.return', $assignment) }}" class="btn btn-outline-warning" title="Devolver">
                                        <i class="bi bi-box-arrow-in-left"></i>
                                    </a>
                                    @endcan
                                    @can('assignments.transfer')
                                    <a href="{{ route('assignments.transfer', $assignment) }}" class="btn btn-outline-info" title="Transferir">
                                        <i class="bi bi-arrow-left-right"></i>
                                    </a>
                                    @endcan
                                @endif
                                @can('assignments.destroy')
                                <form action="{{ route('assignments.destroy', $assignment) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta asignación?')">
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
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                No se encontraron asignaciones
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($assignments->hasPages())
    <div class="card-footer" id="paginationFooter">
        {{ $assignments->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
let searchTimeout;

$(document).ready(function() {
    $('#liveSearch').on('input', function() {
        clearTimeout(searchTimeout);
        const searchTerm = $(this).val();

        if (searchTerm.length === 0) {
            window.location.href = '{{ route('assignments.index') }}';
            return;
        }

        searchTimeout = setTimeout(function() {
            performSearch(searchTerm);
        }, 500);
    });

    $('select[name="status"], select[name="employee_id"]').on('change', function() {
        const searchTerm = $('#liveSearch').val();
        if (searchTerm.length > 0) {
            performSearch(searchTerm);
        }
    });
});

function performSearch(searchTerm) {
    const status = $('select[name="status"]').val();
    const employeeId = $('select[name="employee_id"]').val();

    $('#assignmentsTableBody').html(buildSkeletonRows(7, 6));

    $.ajax({
        url: '{{ route('assignments.search') }}',
        method: 'GET',
        data: {
            search: searchTerm,
            status: status,
            employee_id: employeeId
        },
        success: function(response) {
            $('#assignmentsTableBody').html(response.data);
            $('#paginationFooter').html(response.pagination);
        },
        error: function() {
            $('#assignmentsTableBody').html(`
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
