@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Inicio</li>
            </ol>
        </nav>
    </div>
    <div>
        <span class="text-muted">
            <i class="bi bi-calendar3 me-1"></i>
            {{ now()->format('d/m/Y H:i') }}
        </span>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card primary">
            <i class="bi bi-laptop stat-icon"></i>
            <div class="stat-value">{{ number_format($stats['total_equipment']) }}</div>
            <div class="stat-label">Total de Equipos</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card success">
            <i class="bi bi-check-circle stat-icon"></i>
            <div class="stat-value">{{ number_format($stats['available_equipment']) }}</div>
            <div class="stat-label">Equipos Disponibles</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card info">
            <i class="bi bi-person-check stat-icon"></i>
            <div class="stat-value">{{ number_format($stats['assigned_equipment']) }}</div>
            <div class="stat-label">Equipos Asignados</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card warning">
            <i class="bi bi-tools stat-icon"></i>
            <div class="stat-value">{{ number_format($stats['maintenance_equipment']) }}</div>
            <div class="stat-label">En Mantenimiento</div>
        </div>
    </div>
</div>

<!-- Second Row Stats -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                    <i class="bi bi-people text-primary fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Empleados Activos</div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['total_employees']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                    <i class="bi bi-arrow-left-right text-success fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Asignaciones Activas</div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['active_assignments']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                    <i class="bi bi-wrench text-warning fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Mantenimientos Pendientes</div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['pending_maintenance']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                    <i class="bi bi-shield-exclamation text-danger fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Garantías por Vencer</div>
                    <div class="fs-4 fw-bold">{{ $warrantyExpiring->count() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Equipment by Status Chart -->
    <div class="col-lg-3">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-pie-chart me-2"></i>Por Estado</span>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="230"></canvas>
            </div>
        </div>
    </div>

    <!-- Assignments by Month Chart -->
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-bar-chart-line me-2"></i>Asignaciones por Mes</span>
            </div>
            <div class="card-body">
                <canvas id="assignmentsChart" height="160"></canvas>
            </div>
        </div>
    </div>

    <!-- Equipment by Category + Department -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-building me-2"></i>Equipos por Departamento</span>
            </div>
            <div class="card-body">
                @forelse($equipmentByDepartment as $dept)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-truncate me-2">{{ $dept->name }}</span>
                    <div class="d-flex align-items-center gap-2">
                        <div class="progress flex-grow-1" style="width: 100px; height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ ($dept->total / max($equipmentByDepartment->max('total'), 1)) * 100 }}%"></div>
                        </div>
                        <span class="badge bg-secondary">{{ $dept->total }}</span>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center mb-0">No hay equipos asignados</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <!-- Recent Assignments -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>Asignaciones Recientes</span>
                <a href="{{ route('assignments.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Equipo</th>
                                <th>Empleado</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAssignments as $assignment)
                            <tr>
                                <td>
                                    <a href="{{ route('equipment.show', $assignment->equipment) }}" class="text-decoration-none">
                                        {{ $assignment->equipment->internal_code }}
                                    </a>
                                </td>
                                <td>{{ $assignment->employee->full_name }}</td>
                                <td>{{ $assignment->assignment_date->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $assignment->status_badge_color }}">
                                        {{ $assignment->status_name }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No hay asignaciones recientes</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent History -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-activity me-2"></i>Últimos Movimientos</span>
                <a href="{{ route('history.index') }}" class="btn btn-sm btn-outline-primary">Ver todo</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentHistory as $history)
                    <div class="list-group-item">
                        <div class="d-flex align-items-start">
                            <div class="rounded-circle bg-{{ $history->movement_color }} bg-opacity-10 p-2 me-3">
                                <i class="bi {{ $history->movement_icon }} text-{{ $history->movement_color }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <strong class="small">{{ $history->title }}</strong>
                                    <small class="text-muted">{{ $history->performed_at->diffForHumans() }}</small>
                                </div>
                                <div class="small text-muted">
                                    {{ $history->equipment->internal_code }}
                                    @if($history->newEmployee)
                                        - {{ $history->newEmployee->full_name }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item text-center text-muted py-4">
                        No hay movimientos recientes
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <!-- Warranty Expiring -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-exclamation-triangle text-warning me-2"></i>Garantías por Vencer (30 días)</span>
                <a href="{{ route('reports.warranty-expiring') }}" class="btn btn-sm btn-outline-warning">Ver reporte</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Equipo</th>
                                <th>Marca/Modelo</th>
                                <th>Vencimiento</th>
                                <th>Días</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($warrantyExpiring as $eq)
                            <tr>
                                <td>
                                    <a href="{{ route('equipment.show', $eq) }}" class="text-decoration-none">
                                        {{ $eq->internal_code }}
                                    </a>
                                </td>
                                <td>{{ $eq->brand?->name }} {{ $eq->model }}</td>
                                <td>{{ $eq->warranty_end_date->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $eq->warranty_days_remaining <= 7 ? 'danger' : 'warning' }}">
                                        {{ $eq->warranty_days_remaining }} días
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No hay garantías próximas a vencer</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Maintenance -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-tools text-info me-2"></i>Mantenimientos Pendientes</span>
                <a href="{{ route('maintenance.index') }}" class="btn btn-sm btn-outline-info">Ver todos</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Ticket</th>
                                <th>Equipo</th>
                                <th>Prioridad</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingMaintenance as $maintenance)
                            <tr>
                                <td>
                                    <a href="{{ route('maintenance.show', $maintenance) }}" class="text-decoration-none">
                                        {{ $maintenance->ticket_number }}
                                    </a>
                                </td>
                                <td>{{ $maintenance->equipment->internal_code }}</td>
                                <td>
                                    <span class="badge bg-{{ $maintenance->priority_badge_color }}">
                                        {{ $maintenance->priority_name }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $maintenance->status_badge_color }}">
                                        {{ $maintenance->status_name }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No hay mantenimientos pendientes</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: [
                @foreach($equipmentByStatus as $status)
                    '{{ $status->status_name }}',
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($equipmentByStatus as $status)
                        {{ $status->total }},
                    @endforeach
                ],
                backgroundColor: [
                    '#10b981', // available - green
                    '#3b82f6', // assigned - blue
                    '#f59e0b', // maintenance - yellow
                    '#6b7280', // retired - gray
                    '#ef4444', // lost/stolen - red
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true
                    }
                }
            },
            cutout: '65%'
        }
    });

    // Assignments by Month Chart
    const assignmentsCanvas = document.getElementById('assignmentsChart');
    if (assignmentsCanvas) {
        const assignmentsCtx = assignmentsCanvas.getContext('2d');
        new Chart(assignmentsCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($assignmentsByMonth as $item)
                        '{{ $item->label }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Asignaciones',
                    data: [
                        @foreach($assignmentsByMonth as $item)
                            {{ $item->total }},
                        @endforeach
                    ],
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#4f46e5',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
    }
</script>
@endpush
