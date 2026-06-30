@extends('layouts.app')

@section('title', 'Historial del Empleado: ' . $employee->full_name)

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Historial del Empleado</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Empleados</a></li>
                <li class="breadcrumb-item"><a href="{{ route('employees.show', $employee) }}">{{ $employee->full_name }}</a></li>
                <li class="breadcrumb-item active">Historial</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('employees.show', $employee) }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
</div>

<!-- Employee Summary -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="mb-1">{{ $employee->full_name }}</h5>
                <div class="text-muted small">
                    <span class="me-3"><i class="bi bi-person-badge me-1"></i>{{ $employee->employee_number }}</span>
                    @if($employee->department)
                    <span class="me-3"><i class="bi bi-building me-1"></i>{{ $employee->department->name }}</span>
                    @endif
                    @if($employee->position)
                    <span><i class="bi bi-briefcase me-1"></i>{{ $employee->position->name }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- History List -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock-history me-2"></i>Movimientos registrados</span>
        <span class="badge bg-secondary">{{ $history->total() }} total</span>
    </div>
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @forelse($history as $h)
            <div class="list-group-item">
                <div class="d-flex align-items-start">
                    <div class="rounded-circle bg-{{ $h->movement_color }} bg-opacity-10 p-3 me-3 flex-shrink-0">
                        <i class="bi {{ $h->movement_icon }} text-{{ $h->movement_color }} fs-5"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div>
                                <h6 class="mb-1">{{ $h->title }}</h6>
                                <p class="mb-1 text-muted small">{{ $h->description }}</p>
                                <div class="small">
                                    <span class="badge bg-secondary me-2">{{ $h->movement_type_name }}</span>
                                    @if($h->equipment)
                                    <a href="{{ route('equipment.show', $h->equipment) }}" class="text-decoration-none me-2">
                                        <i class="bi bi-laptop"></i> {{ $h->equipment->internal_code }}
                                        @if($h->equipment->brand)
                                            — {{ $h->equipment->brand->name }}
                                        @endif
                                        @if($h->equipment->model)
                                            {{ $h->equipment->model }}
                                        @endif
                                    </a>
                                    @else
                                    <span class="text-muted me-2"><i class="bi bi-laptop"></i> Equipo eliminado</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-end flex-shrink-0">
                                <div class="text-muted small">{{ $h->performed_at->format('d/m/Y H:i') }}</div>
                                <div class="text-muted small">Por: {{ $h->performedBy?->name ?? 'Sistema' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="list-group-item text-center py-5">
                <div class="text-muted">
                    <i class="bi bi-clock-history fs-1 d-block mb-3"></i>
                    No hay movimientos registrados para este empleado
                </div>
            </div>
            @endforelse
        </div>
    </div>
    @if($history->hasPages())
    <div class="card-footer">
        {{ $history->links() }}
    </div>
    @endif
</div>
@endsection
