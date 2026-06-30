@forelse($employees as $emp)
<tr>
    <td>
        <a href="{{ route('employees.show', $emp) }}" class="fw-medium text-decoration-none">
            {{ $emp->employee_number }}
        </a>
    </td>
    <td>
        <a href="{{ route('employees.show', $emp) }}" class="text-decoration-none fw-medium">
            {{ $emp->full_name }}
        </a>
        <br><small class="text-muted">{{ $emp->email }}</small>
    </td>
    <td>{{ $emp->department?->name ?? '-' }}</td>
    <td>{{ $emp->position?->name ?? '-' }}</td>
    <td>
        <span class="badge bg-{{ $emp->status === 'active' ? 'success' : 'secondary' }}">
            {{ $emp->status === 'active' ? 'Activo' : 'Inactivo' }}
        </span>
    </td>
    <td>
        <span class="badge bg-info">{{ $emp->equipment_count }}</span>
    </td>
    <td class="text-end">
        <div class="btn-group btn-group-sm">
            <a href="{{ route('employees.show', $emp) }}" class="btn btn-outline-primary" title="Ver">
                <i class="bi bi-eye"></i>
            </a>
            @can('employees.edit')
            <a href="{{ route('employees.edit', $emp) }}" class="btn btn-outline-secondary" title="Editar">
                <i class="bi bi-pencil"></i>
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
            No se encontraron empleados
        </div>
    </td>
</tr>
@endforelse
