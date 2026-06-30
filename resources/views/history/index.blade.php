@extends('layouts.app')

@section('title', 'Historial de Movimientos')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Historial de Movimientos</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Historial</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('history.index') }}">
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Buscar Equipo</label>
                    <input type="text" name="search" class="form-control" placeholder="Código, serie..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tipo de Movimiento</label>
                    <select name="movement_type" class="form-select">
                        <option value="">Todos</option>
                        @foreach($movementTypes as $key => $value)
                            <option value="{{ $key }}" {{ request('movement_type') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Empleado</label>
                    <select name="employee_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->full_name }}
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
                <div class="col-md-2 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Buscar</button>
                        <a href="{{ route('history.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- History List -->
<div class="card">
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @forelse($history as $h)
            <div class="list-group-item">
                <div class="d-flex align-items-start">
                    <div class="rounded-circle bg-{{ $h->movement_color }} bg-opacity-10 p-3 me-3">
                        <i class="bi {{ $h->movement_icon }} text-{{ $h->movement_color }} fs-5"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">{{ $h->title }}</h6>
                                <p class="mb-1 text-muted">{{ $h->description }}</p>
                                <div class="small">
                                    <span class="badge bg-secondary me-2">{{ $h->movement_type_name }}</span>
                                    @if($h->equipment)
                                    <a href="{{ route('equipment.show', $h->equipment) }}" class="text-decoration-none me-2">
                                        <i class="bi bi-laptop"></i> {{ $h->equipment->internal_code }}
                                    </a>
                                    @else
                                    <span class="text-muted me-2"><i class="bi bi-laptop"></i> Equipo eliminado</span>
                                    @endif
                                    @if($h->newEmployee)
                                        <a href="{{ route('employees.show', $h->newEmployee) }}" class="text-decoration-none">
                                            <i class="bi bi-person"></i> {{ $h->newEmployee->full_name }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="text-end">
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
                    No se encontraron movimientos
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
