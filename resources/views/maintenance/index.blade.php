@extends('layouts.app')

@section('title', 'Mantenimientos')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Mantenimientos</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Mantenimientos</li>
            </ol>
        </nav>
    </div>
    @can('maintenance.create')
    <a href="{{ route('maintenance.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Nuevo Mantenimiento
    </a>
    @endcan
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('maintenance.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="search" class="form-control" placeholder="Folio, equipo, descripción..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tipo</label>
                    <select name="type" class="form-select">
                        <option value="">Todos</option>
                        <option value="preventive" {{ request('type') == 'preventive' ? 'selected' : '' }}>Preventivo</option>
                        <option value="corrective" {{ request('type') == 'corrective' ? 'selected' : '' }}>Correctivo</option>
                        <option value="upgrade" {{ request('type') == 'upgrade' ? 'selected' : '' }}>Actualización</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Prioridad</label>
                    <select name="priority" class="form-select">
                        <option value="">Todas</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Baja</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Media</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Alta</option>
                        <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgente</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Buscar</button>
                        <a href="{{ route('maintenance.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Equipo</th>
                        <th>Tipo</th>
                        <th>Prioridad</th>
                        <th>Reportado</th>
                        <th>Estado</th>
                        <th>Costo</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                    <tr>
                        <td>
                            <a href="{{ route('maintenance.show', $record) }}" class="fw-medium text-decoration-none">
                                {{ $record->folio ?? '#' . $record->id }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('equipment.show', $record->equipment) }}" class="text-decoration-none">
                                {{ $record->equipment->internal_code }}
                            </a>
                            <br><small class="text-muted">{{ $record->equipment->brand?->name }} {{ $record->equipment->model }}</small>
                        </td>
                        <td>
                            @php
                                $typeColors = ['preventive' => 'info', 'corrective' => 'warning', 'upgrade' => 'primary'];
                                $typeNames = ['preventive' => 'Preventivo', 'corrective' => 'Correctivo', 'upgrade' => 'Actualización'];
                            @endphp
                            <span class="badge bg-{{ $typeColors[$record->type] ?? 'secondary' }}">
                                {{ $typeNames[$record->type] ?? ucfirst($record->type) }}
                            </span>
                        </td>
                        <td>
                            @php
                                $priorityColors = ['low' => 'secondary', 'medium' => 'info', 'high' => 'warning', 'urgent' => 'danger'];
                                $priorityNames = ['low' => 'Baja', 'medium' => 'Media', 'high' => 'Alta', 'urgent' => 'Urgente'];
                            @endphp
                            <span class="badge bg-{{ $priorityColors[$record->priority] ?? 'secondary' }}">
                                {{ $priorityNames[$record->priority] ?? ucfirst($record->priority) }}
                            </span>
                        </td>
                        <td>{{ $record->reported_at?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td>
                            @php
                                $statusColors = ['pending' => 'warning', 'in_progress' => 'info', 'completed' => 'success', 'cancelled' => 'secondary'];
                                $statusNames = ['pending' => 'Pendiente', 'in_progress' => 'En Progreso', 'completed' => 'Completado', 'cancelled' => 'Cancelado'];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$record->status] ?? 'secondary' }}">
                                {{ $statusNames[$record->status] ?? ucfirst($record->status) }}
                            </span>
                        </td>
                        <td>
                            @if($record->cost)
                                ${{ number_format($record->cost, 2) }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('maintenance.show', $record) }}" class="btn btn-outline-primary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('maintenance.edit')
                                <a href="{{ route('maintenance.edit', $record) }}" class="btn btn-outline-secondary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                                @can('maintenance.destroy')
                                <form action="{{ route('maintenance.destroy', $record) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este mantenimiento?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-tools fs-1 d-block mb-3"></i>
                                No se encontraron registros de mantenimiento
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($records->hasPages())
    <div class="card-footer">
        {{ $records->links() }}
    </div>
    @endif
</div>
@endsection
