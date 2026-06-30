@extends('layouts.app')

@section('title', 'Equipos Disponibles')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Equipos Disponibles</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('equipment.index') }}">Equipos</a></li>
                <li class="breadcrumb-item active">Disponibles</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <a href="{{ route('equipment.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
        <span class="badge bg-success fs-6">
            <i class="bi bi-check-circle me-1"></i>{{ $equipment->count() }} equipos disponibles
        </span>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Categoría</th>
                        <th>Marca / Modelo</th>
                        <th>Especificaciones</th>
                        <th>Condición</th>
                        <th>Ubicación</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipment as $eq)
                    <tr>
                        <td>
                            <a href="{{ route('equipment.show', $eq) }}" class="fw-medium text-decoration-none">
                                {{ $eq->internal_code }}
                            </a>
                            @if($eq->serial_number)
                                <br><small class="text-muted"><code>{{ $eq->serial_number }}</code></small>
                            @endif
                        </td>
                        <td>
                            <span class="badge" style="background-color: {{ $eq->category->color ?? '#6c757d' }}">
                                {{ $eq->category->name }}
                            </span>
                        </td>
                        <td>
                            <strong>{{ $eq->brand?->name ?? '-' }}</strong>
                            <br><small class="text-muted">{{ $eq->model ?? '-' }}</small>
                        </td>
                        <td>
                            @if($eq->processor || $eq->ram || $eq->storage)
                                <small>
                                    @if($eq->processor){{ $eq->processor }}<br>@endif
                                    @if($eq->ram)RAM: {{ $eq->ram }}@endif
                                    @if($eq->storage) | {{ $eq->storage }}@endif
                                </small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $eq->condition_badge_color }}">
                                {{ $eq->physical_condition_name }}
                            </span>
                        </td>
                        <td>{{ $eq->location?->name ?? '-' }}</td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('equipment.show', $eq) }}" class="btn btn-outline-primary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('assignments.create')
                                <a href="{{ route('assignments.create', ['equipment_id' => $eq->id]) }}" class="btn btn-success" title="Asignar">
                                    <i class="bi bi-person-plus"></i> Asignar
                                </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                No hay equipos disponibles para asignación
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
