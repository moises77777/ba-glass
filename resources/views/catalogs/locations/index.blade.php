@extends('layouts.app')
@section('title', 'Ubicaciones')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Ubicaciones</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li><li class="breadcrumb-item active">Ubicaciones</li></ol></nav>
    </div>
    <a href="{{ route('locations.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nueva Ubicación</a>
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead><tr><th>Código</th><th>Nombre</th><th>Tipo</th><th>Padre</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
        @forelse($locations as $loc)
        <tr>
            <td><code>{{ $loc->code }}</code></td>
            <td><strong>{{ $loc->name }}</strong></td>
            <td>{{ ucfirst($loc->type ?? '-') }}</td>
            <td>{{ $loc->parent?->name ?? '-' }}</td>
            <td>@if($loc->is_active)<span class="badge bg-success">Activo</span>@else<span class="badge bg-secondary">Inactivo</span>@endif</td>
            <td class="text-end">
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('locations.edit', $loc) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('locations.destroy', $loc) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                        @csrf @method('DELETE')<button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center py-4 text-muted">No hay ubicaciones</td></tr>
        @endforelse
    </tbody>
</table>
</div></div>@if($locations->hasPages())<div class="card-footer">{{ $locations->links() }}</div>@endif</div>
@endsection
