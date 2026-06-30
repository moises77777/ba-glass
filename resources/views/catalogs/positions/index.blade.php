@extends('layouts.app')
@section('title', 'Puestos')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Puestos</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li><li class="breadcrumb-item active">Puestos</li></ol></nav>
    </div>
    <a href="{{ route('positions.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo Puesto</a>
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead><tr><th>Código</th><th>Nombre</th><th>Departamento</th><th>Nivel</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
        @forelse($positions as $p)
        <tr>
            <td><code>{{ $p->code }}</code></td>
            <td><strong>{{ $p->name }}</strong></td>
            <td>{{ $p->department?->name ?? '-' }}</td>
            <td>{{ $p->level ?? '-' }}</td>
            <td>@if($p->is_active)<span class="badge bg-success">Activo</span>@else<span class="badge bg-secondary">Inactivo</span>@endif</td>
            <td class="text-end">
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('positions.edit', $p) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('positions.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center py-4 text-muted">No hay puestos</td></tr>
        @endforelse
    </tbody>
</table>
</div></div>@if($positions->hasPages())<div class="card-footer">{{ $positions->links() }}</div>@endif</div>
@endsection
