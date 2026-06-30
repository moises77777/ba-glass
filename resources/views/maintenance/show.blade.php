@extends('layouts.app')

@section('title', 'Mantenimiento')

@section('content')
@php
    $statusColors = ['pending' => 'warning', 'in_progress' => 'info', 'completed' => 'success', 'cancelled' => 'secondary'];
    $statusNames = ['pending' => 'Pendiente', 'in_progress' => 'En Progreso', 'completed' => 'Completado', 'cancelled' => 'Cancelado'];
    $priorityColors = ['low' => 'secondary', 'medium' => 'info', 'high' => 'warning', 'urgent' => 'danger'];
    $priorityNames = ['low' => 'Baja', 'medium' => 'Media', 'high' => 'Alta', 'urgent' => 'Urgente'];
@endphp

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Mantenimiento {{ $maintenance->ticket_number ?? '#' . $maintenance->id }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('maintenance.index') }}">Mantenimientos</a></li>
                <li class="breadcrumb-item active">{{ $maintenance->ticket_number ?? '#' . $maintenance->id }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('maintenance.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
        @can('maintenance.edit')
        <a href="{{ route('maintenance.edit', $maintenance) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        @endcan
        @can('maintenance.destroy')
        <form action="{{ route('maintenance.destroy', $maintenance) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este mantenimiento?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash me-1"></i>Eliminar</button>
        </form>
        @endcan
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-tools me-2"></i>Detalles del Mantenimiento</span>
                <span class="badge bg-{{ $statusColors[$maintenance->status] ?? 'secondary' }} fs-6">
                    {{ $statusNames[$maintenance->status] ?? ucfirst($maintenance->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted">Tipo:</small>
                        <div><strong>{{ ['preventive' => 'Preventivo', 'corrective' => 'Correctivo', 'upgrade' => 'Actualización', 'cleaning' => 'Limpieza', 'inspection' => 'Inspección', 'other' => 'Otro'][$maintenance->type] ?? $maintenance->type }}</strong></div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Prioridad:</small>
                        <div>
                            <span class="badge bg-{{ $priorityColors[$maintenance->priority] ?? 'secondary' }}">
                                {{ $priorityNames[$maintenance->priority] ?? ucfirst($maintenance->priority) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Reportado:</small>
                        <div>{{ $maintenance->reported_at?->format('d/m/Y H:i') ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Programado:</small>
                        <div>{{ $maintenance->scheduled_date?->format('d/m/Y') ?? '-' }}</div>
                    </div>
                    @if($maintenance->started_at)
                    <div class="col-md-6">
                        <small class="text-muted">Iniciado:</small>
                        <div>{{ $maintenance->started_at->format('d/m/Y H:i') }}</div>
                    </div>
                    @endif
                    @if($maintenance->completed_at)
                    <div class="col-md-6">
                        <small class="text-muted">Completado:</small>
                        <div>{{ $maintenance->completed_at->format('d/m/Y H:i') }}</div>
                    </div>
                    @endif
                </div>

                @if($maintenance->problem_description)
                <hr>
                <small class="text-muted">Descripción del Problema:</small>
                <p>{{ $maintenance->problem_description }}</p>
                @endif

                @if($maintenance->diagnosis)
                <small class="text-muted">Diagnóstico:</small>
                <p>{{ $maintenance->diagnosis }}</p>
                @endif

                @if($maintenance->solution)
                <small class="text-muted">Trabajo Realizado:</small>
                <p>{{ $maintenance->solution }}</p>
                @endif

                @if($maintenance->parts_replaced)
                <small class="text-muted">Partes Utilizadas:</small>
                <p>{{ $maintenance->parts_replaced }}</p>
                @endif

                @if($maintenance->notes)
                <small class="text-muted">Notas:</small>
                <p>{{ $maintenance->notes }}</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="bi bi-laptop me-2"></i>Equipo</div>
            <div class="card-body">
                <h5>
                    <a href="{{ route('equipment.show', $maintenance->equipment) }}" class="text-decoration-none">
                        {{ $maintenance->equipment->internal_code }}
                    </a>
                </h5>
                <p class="text-muted">{{ $maintenance->equipment->brand?->name }} {{ $maintenance->equipment->model }}</p>
                <p class="mb-0"><small class="text-muted">Serie: <code>{{ $maintenance->equipment->serial_number ?? 'N/A' }}</code></small></p>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-cash-coin me-2"></i>Información Económica</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Costo:</td>
                        <td><strong>{{ $maintenance->total_cost ? '$' . number_format($maintenance->total_cost, 2) : '-' }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Costo Mano de Obra:</td>
                        <td>{{ $maintenance->labor_cost ? '$' . number_format($maintenance->labor_cost, 2) : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Costo Partes:</td>
                        <td>{{ $maintenance->parts_cost ? '$' . number_format($maintenance->parts_cost, 2) : '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="bi bi-people me-2"></i>Responsables</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Proveedor:</td>
                        <td>{{ $maintenance->supplier?->name ?? 'Interno' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Técnico Asignado:</td>
                        <td>{{ $maintenance->assignee?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Reportado por:</td>
                        <td>{{ $maintenance->reporter?->name ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
