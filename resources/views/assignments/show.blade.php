@extends('layouts.app')

@section('title', 'Asignación: ' . $assignment->assignment_number)

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">{{ $assignment->assignment_number }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('assignments.index') }}">Asignaciones</a></li>
                <li class="breadcrumb-item active">{{ $assignment->assignment_number }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('assignments.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
        <a href="{{ route('assignments.pdf', $assignment) }}" class="btn btn-danger">
            <i class="bi bi-file-pdf me-1"></i>Descargar PDF
        </a>
        <a href="{{ route('assignments.pdf.preview', $assignment) }}" class="btn btn-outline-danger" target="_blank">
            <i class="bi bi-eye me-1"></i>Vista Previa
        </a>
        @if($assignment->isActive())
            @can('assignments.return')
            <a href="{{ route('assignments.return', $assignment) }}" class="btn btn-warning">
                <i class="bi bi-box-arrow-in-left me-1"></i>Devolver
            </a>
            @endcan
            @can('assignments.transfer')
            <a href="{{ route('assignments.transfer', $assignment) }}" class="btn btn-info">
                <i class="bi bi-arrow-left-right me-1"></i>Transferir
            </a>
            @endcan
        @endif
        @can('assignments.destroy')
        <form action="{{ route('assignments.destroy', $assignment) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta asignación?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash me-1"></i>Eliminar</button>
        </form>
        @endcan
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Assignment Info -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clipboard-check me-2"></i>Información de la Asignación</span>
                <span class="badge bg-{{ $assignment->status_badge_color }} fs-6">
                    {{ $assignment->status_name }}
                </span>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted" width="40%">No. Asignación:</td>
                                <td><strong>{{ $assignment->assignment_number }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Folio Responsiva:</td>
                                <td><strong class="text-primary">{{ $assignment->custody_letter_folio ?? 'Pendiente' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Fecha Asignación:</td>
                                <td>{{ $assignment->assignment_date->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Fecha Devolución Esperada:</td>
                                <td>{{ $assignment->expected_return_date?->format('d/m/Y') ?? 'No especificada' }}</td>
                            </tr>
                            @if($assignment->actual_return_date)
                            <tr>
                                <td class="text-muted">Fecha Devolución Real:</td>
                                <td>{{ $assignment->actual_return_date->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted" width="40%">Condición Entrega:</td>
                                <td>
                                    <span class="badge bg-{{ $assignment->condition_at_assignment == 'excellent' || $assignment->condition_at_assignment == 'good' ? 'success' : 'warning' }}">
                                        {{ $assignment->condition_at_assignment_name }}
                                    </span>
                                </td>
                            </tr>
                            @if($assignment->condition_at_return)
                            <tr>
                                <td class="text-muted">Condición Devolución:</td>
                                <td>
                                    <span class="badge bg-{{ $assignment->condition_at_return == 'excellent' || $assignment->condition_at_return == 'good' ? 'success' : 'warning' }}">
                                        {{ $assignment->condition_at_return_name }}
                                    </span>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td class="text-muted">Asignado por:</td>
                                <td>{{ $assignment->assignedBy->name }}</td>
                            </tr>
                            @if($assignment->receivedBy)
                            <tr>
                                <td class="text-muted">Recibido por:</td>
                                <td>{{ $assignment->receivedBy->name }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="text-muted">Duración:</td>
                                <td>{{ $assignment->duration }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($assignment->location || $assignment->work_area)
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted">Ubicación:</small>
                        <div>{{ $assignment->location?->full_path ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Área de Trabajo:</small>
                        <div>{{ $assignment->work_area ?? '-' }}</div>
                    </div>
                </div>
                @endif

                @if($assignment->accessories_delivered)
                <hr>
                <small class="text-muted">Accesorios Entregados:</small>
                <div>{{ $assignment->accessories_delivered }}</div>
                @endif

                @if($assignment->assignment_notes)
                <hr>
                <small class="text-muted">Notas de Asignación:</small>
                <div>{{ $assignment->assignment_notes }}</div>
                @endif

                @if($assignment->return_reason)
                <hr>
                <div class="alert alert-info mb-0">
                    <strong>Motivo de Devolución:</strong> {{ $assignment->return_reason_name }}
                    @if($assignment->return_reason_details)
                        <br><small>{{ $assignment->return_reason_details }}</small>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Equipment Info -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-laptop me-2"></i>Equipo Asignado
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        @if($assignment->equipment->images->count() > 0)
                            <img src="{{ $assignment->equipment->primary_image_url }}" alt="Equipo" class="img-fluid rounded" style="max-height: 100px;">
                        @else
                            <div class="bg-light rounded p-4">
                                <i class="bi bi-laptop fs-1 text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-10">
                        <h5 class="mb-1">
                            <a href="{{ route('equipment.show', $assignment->equipment) }}" class="text-decoration-none">
                                {{ $assignment->equipment->internal_code }}
                            </a>
                        </h5>
                        <p class="text-muted mb-2">
                            {{ $assignment->equipment->brand?->name }} {{ $assignment->equipment->model }}
                            <span class="badge" style="background-color: {{ $assignment->equipment->category->color ?? '#6c757d' }}">
                                {{ $assignment->equipment->category->name }}
                            </span>
                        </p>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted" width="20%">No. Serie:</td>
                                <td><code>{{ $assignment->equipment->serial_number ?? 'N/A' }}</code></td>
                                <td class="text-muted" width="20%">Procesador:</td>
                                <td>{{ $assignment->equipment->processor ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">RAM:</td>
                                <td>{{ $assignment->equipment->ram ?? 'N/A' }}</td>
                                <td class="text-muted">Almacenamiento:</td>
                                <td>{{ $assignment->equipment->storage ?? 'N/A' }} {{ $assignment->equipment->storage_type }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee Info -->
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person me-2"></i>Empleado Responsable
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width:80px;height:80px;">
                            <i class="bi bi-person-fill fs-1 text-primary"></i>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <h5 class="mb-1">
                            <a href="{{ route('employees.show', $assignment->employee) }}" class="text-decoration-none">
                                {{ $assignment->employee->full_name }}
                            </a>
                        </h5>
                        <p class="text-muted mb-2">{{ $assignment->employee->position->name }}</p>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted" width="20%">No. Empleado:</td>
                                <td>{{ $assignment->employee->employee_number }}</td>
                                <td class="text-muted" width="20%">Departamento:</td>
                                <td>{{ $assignment->employee->department->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email:</td>
                                <td>{{ $assignment->employee->email }}</td>
                                <td class="text-muted">Teléfono:</td>
                                <td>{{ $assignment->employee->phone ?? $assignment->employee->mobile ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-lightning me-2"></i>Acciones Rápidas
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('assignments.pdf', $assignment) }}" class="btn btn-danger">
                        <i class="bi bi-file-pdf me-2"></i>Descargar Responsiva PDF
                    </a>
                    <a href="{{ route('equipment.show', $assignment->equipment) }}" class="btn btn-outline-primary">
                        <i class="bi bi-laptop me-2"></i>Ver Equipo
                    </a>
                    <a href="{{ route('employees.show', $assignment->employee) }}" class="btn btn-outline-primary">
                        <i class="bi bi-person me-2"></i>Ver Empleado
                    </a>
                    <a href="{{ route('history.by-equipment', $assignment->equipment) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-clock-history me-2"></i>Historial del Equipo
                    </a>
                </div>
            </div>
        </div>

        <!-- Status Timeline -->
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2"></i>Línea de Tiempo
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <div class="fw-medium">Asignación Creada</div>
                            <small class="text-muted">{{ $assignment->assignment_date->format('d/m/Y H:i') }}</small>
                            <br><small class="text-muted">Por: {{ $assignment->assignedBy->name }}</small>
                        </div>
                    </div>
                    @if($assignment->custody_letter_generated_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <div class="fw-medium">Responsiva Generada</div>
                            <small class="text-muted">{{ $assignment->custody_letter_generated_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                    @endif
                    @if($assignment->actual_return_date)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <div class="fw-medium">Equipo Devuelto</div>
                            <small class="text-muted">{{ $assignment->actual_return_date->format('d/m/Y H:i') }}</small>
                            @if($assignment->receivedBy)
                                <br><small class="text-muted">Por: {{ $assignment->receivedBy->name }}</small>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    padding-bottom: 20px;
}
.timeline-item:before {
    content: '';
    position: absolute;
    left: -24px;
    top: 8px;
    bottom: -12px;
    width: 2px;
    background: #e2e8f0;
}
.timeline-item:last-child:before {
    display: none;
}
.timeline-marker {
    position: absolute;
    left: -30px;
    top: 4px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px currentColor;
}
</style>
@endsection
