@forelse($assignments as $assign)
<tr>
    <td>
        <a href="{{ route('assignments.show', $assign) }}" class="fw-medium text-decoration-none">
            {{ $assign->assignment_number }}
        </a>
        <br><small class="text-muted">{{ $assign->custody_letter_folio }}</small>
    </td>
    <td>
        <a href="{{ route('equipment.show', $assign->equipment) }}" class="text-decoration-none">
            <strong>{{ $assign->equipment->internal_code }}</strong>
            <br><small class="text-muted">{{ $assign->equipment->brand?->name }} {{ $assign->equipment->model }}</small>
        </a>
    </td>
    <td>
        <a href="{{ route('employees.show', $assign->employee) }}" class="text-decoration-none">
            {{ $assign->employee->full_name }}
        </a>
    </td>
    <td>{{ $assign->employee->department->name }}</td>
    <td>
        {{ $assign->assignment_date->format('d/m/Y H:i') }}
        @if($assign->actual_return_date)
            <br><small class="text-muted">Dev: {{ $assign->actual_return_date->format('d/m/Y') }}</small>
        @endif
    </td>
    <td>
        <span class="badge bg-{{ $assign->status_badge_color }}">
            {{ $assign->status_name }}
        </span>
    </td>
    <td class="text-end">
        <div class="btn-group btn-group-sm">
            <a href="{{ route('assignments.show', $assign) }}" class="btn btn-outline-primary" title="Ver">
                <i class="bi bi-eye"></i>
            </a>
            <a href="{{ route('assignments.pdf', $assign) }}" class="btn btn-outline-secondary" title="PDF">
                <i class="bi bi-file-pdf"></i>
            </a>
            @if($assign->status === 'active')
                <a href="{{ route('assignments.return', $assign) }}" class="btn btn-outline-warning" title="Devolver">
                    <i class="bi bi-arrow-return-left"></i>
                </a>
            @endif
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center py-5">
        <div class="text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
            No se encontraron asignaciones
        </div>
    </td>
</tr>
@endforelse
