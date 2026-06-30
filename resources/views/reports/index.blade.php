@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="page-header">
    <h1 class="page-title">Reportes</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Reportes</li>
        </ol>
    </nav>
</div>

<div class="row g-4">
    <!-- Equipment Reports -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-header" style="background:#4f46e5 !important;color:#fff;">
                <i class="bi bi-laptop me-2"></i>Reportes de Equipos
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('reports.equipment-summary') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-list-ul me-2"></i>Resumen de Equipos
                            <br><small class="text-muted">Listado completo con filtros</small>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="{{ route('reports.available-equipment') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-check-circle me-2"></i>Equipos Disponibles
                            <br><small class="text-muted">Equipos listos para asignar</small>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="{{ route('reports.assigned-equipment') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-person-check me-2"></i>Equipos Asignados
                            <br><small class="text-muted">Equipos en uso actualmente</small>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="{{ route('reports.warranty-expiring') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-exclamation-triangle me-2"></i>Garantías por Vencer
                            <br><small class="text-muted">Equipos con garantía próxima a expirar</small>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Organization Reports -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-header" style="background:#059669 !important;color:#fff;">
                <i class="bi bi-building me-2"></i>Reportes por Organización
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('reports.equipment-by-employee') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-person me-2"></i>Equipos por Empleado
                            <br><small class="text-muted">Equipos asignados a cada empleado</small>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="{{ route('reports.equipment-by-department') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-diagram-3 me-2"></i>Equipos por Departamento
                            <br><small class="text-muted">Distribución por área</small>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- History & Maintenance Reports -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-header" style="background:#0891b2 !important;color:#fff;">
                <i class="bi bi-clock-history me-2"></i>Historial y Mantenimiento
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('reports.movement-history') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-arrow-left-right me-2"></i>Historial de Movimientos
                            <br><small class="text-muted">Asignaciones, devoluciones, transferencias</small>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="{{ route('reports.maintenance') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-tools me-2"></i>Reporte de Mantenimiento
                            <br><small class="text-muted">Historial de reparaciones</small>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-bar-chart me-2"></i>Estadísticas Rápidas
            </div>
            <div class="card-body">
                <div class="row g-4 text-center">
                    <div class="col-md-2">
                        <div class="border rounded p-3">
                            <div class="fs-3 fw-bold text-primary">{{ \App\Models\Equipment::count() }}</div>
                            <small class="text-muted">Total Equipos</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="border rounded p-3">
                            <div class="fs-3 fw-bold text-success">{{ \App\Models\Equipment::where('availability_status', 'available')->count() }}</div>
                            <small class="text-muted">Disponibles</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="border rounded p-3">
                            <div class="fs-3 fw-bold text-info">{{ \App\Models\Equipment::where('availability_status', 'assigned')->count() }}</div>
                            <small class="text-muted">Asignados</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="border rounded p-3">
                            <div class="fs-3 fw-bold text-warning">{{ \App\Models\Equipment::where('availability_status', 'in_maintenance')->count() }}</div>
                            <small class="text-muted">En Mantenimiento</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="border rounded p-3">
                            <div class="fs-3 fw-bold text-secondary">{{ \App\Models\Employee::where('status', 'active')->count() }}</div>
                            <small class="text-muted">Empleados Activos</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="border rounded p-3">
                            <div class="fs-3 fw-bold text-danger">{{ \App\Models\Assignment::where('status', 'active')->count() }}</div>
                            <small class="text-muted">Asignaciones Activas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
