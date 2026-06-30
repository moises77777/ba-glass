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
