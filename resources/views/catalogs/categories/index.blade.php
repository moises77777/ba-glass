@extends('layouts.app')
@section('title', 'Categorías de Equipos')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Categorías de Equipos</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li><li class="breadcrumb-item active">Categorías</li></ol></nav>
    </div>
    <a href="{{ route('categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nueva Categoría</a>
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead><tr><th>Código</th><th>Nombre</th><th>Padre</th><th>Serie?</th><th>Color</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
        @forelse($categories as $c)
        <tr>
            <td><code>{{ $c->code }}</code></td>
            <td>
                @if($c->icon)<i class="bi {{ $c->icon }} me-1"></i>@endif
                <strong>{{ $c->name }}</strong>
            </td>
            <td>{{ $c->parent?->name ?? '-' }}</td>
            <td>@if($c->requires_serial)<span class="badge bg-info">Sí</span>@else<span class="text-muted">No</span>@endif</td>
            <td>@if($c->color)<span class="badge" style="background-color: {{ $c->color }}">{{ $c->color }}</span>@else -@endif</td>
            <td>@if($c->is_active)<span class="badge bg-success">Activo</span>@else<span class="badge bg-secondary">Inactivo</span>@endif</td>
            <td class="text-end">
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('categories.edit', $c) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('categories.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                        @csrf @method('DELETE')<button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center py-4 text-muted">No hay categorías</td></tr>
        @endforelse
    </tbody>
</table>
</div></div>@if($categories->hasPages())<div class="card-footer">{{ $categories->links() }}</div>@endif</div>
@endsection
