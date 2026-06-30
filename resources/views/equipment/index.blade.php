@extends('layouts.app')

@section('title', 'Equipos')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Equipos</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Equipos</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('equipment.available') }}" class="btn btn-outline-success">
            <i class="bi bi-check-circle me-1"></i>Disponibles
        </a>
        @can('equipment.create')
        <a href="{{ route('equipment.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Nuevo Equipo
        </a>
        @endcan
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('equipment.index') }}" id="filterForm">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="search" class="form-control" placeholder="Código, serie, modelo..." value="{{ request('search') }}" id="liveSearch">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Categoría</label>
                    <select name="category_id" class="form-select">
                        <option value="">Todas</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Marca</label>
                    <select name="brand_id" class="form-select">
                        <option value="">Todas</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Disponibilidad</label>
                    <select name="availability_status" class="form-select">
                        <option value="">Todos</option>
                        @foreach(\App\Models\Equipment::AVAILABILITY_STATUSES as $key => $value)
                            <option value="{{ $key }}" {{ request('availability_status') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Condición</label>
                    <select name="physical_condition" class="form-select">
                        <option value="">Todas</option>
                        @foreach(\App\Models\Equipment::PHYSICAL_CONDITIONS as $key => $value)
                            <option value="{{ $key }}" {{ request('physical_condition') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                        <a href="{{ route('equipment.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Equipment Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Categoría</th>
                        <th>Marca / Modelo</th>
                        <th>No. Serie</th>
                        <th>Asignado a</th>
                        <th>Disponibilidad</th>
                        <th>Condición</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody id="equipmentTableBody">
                    @forelse($equipment as $eq)
                    <tr>
                        <td>
                            <a href="{{ route('equipment.show', $eq) }}" class="fw-medium text-decoration-none">
                                {{ $eq->internal_code }}
                            </a>
                            @if($eq->asset_tag)
                                <br><small class="text-muted">{{ $eq->asset_tag }}</small>
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
                            <code>{{ $eq->serial_number ?? '-' }}</code>
                        </td>
                        <td>
                            @if($eq->currentEmployee)
                                <a href="{{ route('employees.show', $eq->currentEmployee) }}" class="text-decoration-none">
                                    {{ $eq->currentEmployee->full_name }}
                                </a>
                                <br><small class="text-muted">{{ $eq->currentEmployee->department->name }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $eq->availability_badge_color }}">
                                {{ $eq->availability_status_name }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $eq->condition_badge_color }}">
                                {{ $eq->physical_condition_name }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('equipment.show', $eq) }}" class="btn btn-outline-primary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('equipment.edit')
                                <a href="{{ route('equipment.edit', $eq) }}" class="btn btn-outline-secondary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                                @if($eq->isAvailable())
                                    @can('assignments.create')
                                    <a href="{{ route('assignments.create', ['equipment_id' => $eq->id]) }}" class="btn btn-outline-success" title="Asignar">
                                        <i class="bi bi-person-plus"></i>
                                    </a>
                                    @endcan
                                @endif
                                @can('equipment.destroy')
                                <form action="{{ route('equipment.destroy', $eq) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este equipo?')">
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
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                No se encontraron equipos
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($equipment->hasPages())
    <div class="card-footer" id="paginationFooter">
        {{ $equipment->links() }}
    </div>
    @endif
</div>

<!-- Stats Summary -->
<div class="row g-3 mt-3">
    <div class="col-auto">
        <span class="badge bg-light text-dark border">
            <i class="bi bi-laptop me-1"></i>
            Total: <span id="totalCount">{{ $equipment->total() }}</span>
        </span>
    </div>
</div>
@endsection

@push('scripts')
<script>
let searchTimeout;

$(document).ready(function() {
    // Búsqueda en vivo con debounce
    $('#liveSearch').on('input', function() {
        clearTimeout(searchTimeout);
        const searchTerm = $(this).val();

        if (searchTerm.length === 0) {
            // Si está vacío, recargar la página
            window.location.href = '{{ route('equipment.index') }}';
            return;
        }

        searchTimeout = setTimeout(function() {
            performSearch(searchTerm);
        }, 500);
    });

    // Búsqueda también cuando cambian los filtros
    $('select[name="category_id"], select[name="availability_status"]').on('change', function() {
        const searchTerm = $('#liveSearch').val();
        if (searchTerm.length > 0) {
            performSearch(searchTerm);
        }
    });
});

function performSearch(searchTerm) {
    const categoryId = $('select[name="category_id"]').val();
    const availabilityStatus = $('select[name="availability_status"]').val();

    // Mostrar skeleton loader
    $('#equipmentTableBody').html(buildSkeletonRows(8, 6));

    $.ajax({
        url: '{{ route('equipment.search') }}',
        method: 'GET',
        data: {
            search: searchTerm,
            category_id: categoryId,
            availability_status: availabilityStatus
        },
        success: function(response) {
            $('#equipmentTableBody').html(response.data);
            $('#paginationFooter').html(response.pagination);
            $('#totalCount').text(response.total);
        },
        error: function() {
            $('#equipmentTableBody').html(`
                <tr>
                    <td colspan="8" class="text-center py-5 text-danger">
                        <i class="bi bi-exclamation-triangle fs-1 d-block mb-3"></i>
                        Error al cargar los resultados
                    </td>
                </tr>
            `);
        }
    });
}
</script>
@endpush
