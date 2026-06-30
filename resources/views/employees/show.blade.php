@extends('layouts.app')

@section('title', 'Empleado: ' . $employee->full_name)

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">{{ $employee->full_name }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Empleados</a></li>
                <li class="breadcrumb-item active">{{ $employee->full_name }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('employees.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
        @if($employee->status == 'active')
            @can('assignments.create')
            <a href="{{ route('assignments.create', ['employee_id' => $employee->id]) }}" class="btn btn-success">
                <i class="bi bi-laptop me-1"></i>Asignar Equipo
            </a>
            @endcan
        @endif
        @can('employees.edit')
        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        @endcan
        @can('employees.destroy')
        <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este empleado?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash me-1"></i>Eliminar</button>
        </form>
        @endcan
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <!-- Profile Card -->
        <div class="card mb-4">
            <div class="card-body text-center">
                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:120px;height:120px;">
                    <i class="bi bi-person-fill fs-1 text-primary"></i>
                </div>
                <h4 class="mb-1">{{ $employee->full_name }}</h4>
                <p class="text-muted mb-2">{{ $employee->position->name }}</p>
                <p class="text-muted mb-3">{{ $employee->department->name }}</p>
                
                @if($employee->status == 'active')
                    <span class="badge bg-success fs-6">Activo</span>
                @elseif($employee->status == 'inactive')
                    <span class="badge bg-secondary fs-6">Inactivo</span>
                @elseif($employee->status == 'on_leave')
                    <span class="badge bg-warning fs-6">Licencia</span>
                @else
                    <span class="badge bg-danger fs-6">Baja</span>
                @endif
            </div>
            <div class="card-footer bg-transparent">
                <div class="row text-center">
                    <div class="col">
                        <div class="fs-4 fw-bold text-primary">{{ $employee->currentEquipment->count() }}</div>
                        <small class="text-muted">Equipos Asignados</small>
                    </div>
                    <div class="col">
                        <div class="fs-4 fw-bold text-secondary">{{ $assignments->count() }}</div>
                        <small class="text-muted">Total Asignaciones</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-person-lines-fill me-2"></i>Información de Contacto
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted"><i class="bi bi-envelope me-2"></i>Email:</td>
                        <td><a href="mailto:{{ $employee->email }}">{{ $employee->email }}</a></td>
                    </tr>
                    <tr>
                        <td class="text-muted"><i class="bi bi-telephone me-2"></i>Teléfono:</td>
                        <td>{{ $employee->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted"><i class="bi bi-phone me-2"></i>Móvil:</td>
                        <td>{{ $employee->mobile ?? '-' }}</td>
                    </tr>
                    @if($employee->address)
                    <tr>
                        <td class="text-muted"><i class="bi bi-geo-alt me-2"></i>Dirección:</td>
                        <td>{{ $employee->address }}, {{ $employee->city }}, {{ $employee->state }} {{ $employee->postal_code }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Employment Info -->
        <div class="card">
            <div class="card-header">
                <i class="bi bi-briefcase me-2"></i>Información Laboral
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">No. Empleado:</td>
                        <td><strong>{{ $employee->employee_number }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Fecha Ingreso:</td>
                        <td>{{ $employee->hire_date->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Antigüedad:</td>
                        <td>{{ $employee->hire_date->diffForHumans(null, true) }}</td>
                    </tr>
                    @if($employee->supervisor)
                    <tr>
                        <td class="text-muted">Supervisor:</td>
                        <td>
                            <a href="{{ route('employees.show', $employee->supervisor) }}" class="text-decoration-none">
                                {{ $employee->supervisor->full_name }}
                            </a>
                        </td>
                    </tr>
                    @endif
                    @if($employee->curp)
                    <tr>
                        <td class="text-muted">CURP:</td>
                        <td><code>{{ $employee->curp }}</code></td>
                    </tr>
                    @endif
                    @if($employee->rfc)
                    <tr>
                        <td class="text-muted">RFC:</td>
                        <td><code>{{ $employee->rfc }}</code></td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Current Equipment -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-laptop me-2"></i>Equipos Asignados Actualmente</span>
                <span class="badge bg-primary">{{ $employee->currentEquipment->count() }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Categoría</th>
                                <th>Marca / Modelo</th>
                                <th>Fecha Asignación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->currentEquipment as $eq)
                            <tr>
                                <td>
                                    <a href="{{ route('equipment.show', $eq) }}" class="fw-medium text-decoration-none">
                                        {{ $eq->internal_code }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge" style="background-color: {{ $eq->category->color ?? '#6c757d' }}">
                                        {{ $eq->category->name }}
                                    </span>
                                </td>
                                <td>{{ $eq->brand?->name }} {{ $eq->model }}</td>
                                <td>{{ $eq->assignment_date?->format('d/m/Y') ?? '-' }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('equipment.show', $eq) }}" class="btn btn-outline-primary" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($eq->activeAssignment)
                                        <a href="{{ route('assignments.pdf', $eq->activeAssignment) }}" class="btn btn-outline-danger" title="PDF">
                                            <i class="bi bi-file-pdf"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No tiene equipos asignados actualmente
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Assignment History -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>Historial de Asignaciones</span>
                <span class="badge bg-secondary">{{ $assignments->count() }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Equipo</th>
                                <th>Fecha Asignación</th>
                                <th>Fecha Devolución</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignments as $assignment)
                            <tr>
                                <td>
                                    <a href="{{ route('assignments.show', $assignment) }}" class="text-decoration-none">
                                        {{ $assignment->assignment_number }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('equipment.show', $assignment->equipment) }}" class="text-decoration-none">
                                        {{ $assignment->equipment->internal_code }}
                                    </a>
                                    <br><small class="text-muted">{{ $assignment->equipment->brand?->name }} {{ $assignment->equipment->model }}</small>
                                </td>
                                <td>{{ $assignment->assignment_date->format('d/m/Y') }}</td>
                                <td>{{ $assignment->actual_return_date?->format('d/m/Y') ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $assignment->status_badge_color }}">
                                        {{ $assignment->status_name }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('assignments.pdf', $assignment) }}" class="btn btn-sm btn-outline-danger" title="PDF">
                                        <i class="bi bi-file-pdf"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No tiene historial de asignaciones
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Equipment History -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-activity me-2"></i>Últimos Movimientos</span>
                <a href="{{ route('history.by-employee', $employee) }}" class="btn btn-sm btn-outline-primary">Ver todo</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($equipmentHistory as $history)
                    <div class="list-group-item">
                        <div class="d-flex align-items-start">
                            <div class="rounded-circle bg-{{ $history->movement_color }} bg-opacity-10 p-2 me-3">
                                <i class="bi {{ $history->movement_icon }} text-{{ $history->movement_color }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $history->title }}</strong>
                                    <small class="text-muted">{{ $history->performed_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <div class="small text-muted">
                                    {{ $history->equipment->internal_code }} - {{ $history->description }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item text-center text-muted py-4">
                        No hay movimientos registrados
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
