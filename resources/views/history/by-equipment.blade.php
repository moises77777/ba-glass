@extends('layouts.app')

@section('title', 'Historial del Equipo: ' . $equipment->internal_code)

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Historial del Equipo</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('equipment.index') }}">Equipos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('equipment.show', $equipment) }}">{{ $equipment->internal_code }}</a></li>
                <li class="breadcrumb-item active">Historial</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('equipment.show', $equipment) }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
</div>

<!-- Equipment Summary -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="mb-1">{{ $equipment->full_name }}</h5>
                <div class="text-muted small">
                    <span class="me-3"><i class="bi bi-tag me-1"></i>{{ $equipment->internal_code }}</span>
                    @if($equipment->serial_number)
                    <span class="me-3"><i class="bi bi-upc me-1"></i>{{ $equipment->serial_number }}</span>
                    @endif
                    @if($equipment->category)
                    <span><i class="bi bi-folder me-1"></i>{{ $equipment->category->name }}</span>
                    @endif
                </div>
            </div>
            <div class="col-md-4 text-md-end mt-2 mt-md-0">
                <span class="badge bg-{{ $equipment->availability_badge_color }} fs-6">
                    {{ $equipment->availability_status_name }}
                </span>
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
                                    @if($h->newEmployee)
                                        <span class="me-2">
                                            <i class="bi bi-person-check text-success"></i>
                                            <a href="{{ route('employees.show', $h->newEmployee) }}" class="text-decoration-none">
                                                {{ $h->newEmployee->full_name }}
                                            </a>
                                        </span>
                                    @endif
                                    @if($h->previousEmployee)
                                        <span class="me-2 text-muted">
                                            <i class="bi bi-person-dash"></i>
                                            {{ $h->previousEmployee->full_name }}
                                        </span>
                                    @endif
                                    @if($h->newLocation)
                                        <span class="me-2 text-muted">
                                            <i class="bi bi-geo-alt"></i>
                                            {{ $h->newLocation->full_path ?? $h->newLocation->name }}
                                        </span>
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
                    No hay movimientos registrados para este equipo
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
