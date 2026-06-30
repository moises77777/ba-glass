@extends('layouts.app')
@section('title', 'Marcas')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Marcas</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li><li class="breadcrumb-item active">Marcas</li></ol></nav>
    </div>
    <a href="{{ route('brands.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nueva Marca</a>
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead><tr><th>Nombre</th><th>Sitio Web</th><th>Soporte</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
        @forelse($brands as $b)
        <tr>
            <td><strong>{{ $b->name }}</strong></td>
            <td>@if($b->website)<a href="{{ $b->website }}" target="_blank">{{ $b->website }}</a>@else -@endif</td>
            <td>{{ $b->support_phone ?? '-' }}<br><small>{{ $b->support_email ?? '' }}</small></td>
            <td>@if($b->is_active)<span class="badge bg-success">Activo</span>@else<span class="badge bg-secondary">Inactivo</span>@endif</td>
            <td class="text-end">
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('brands.edit', $b) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('brands.destroy', $b) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                        @csrf @method('DELETE')<button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center py-4 text-muted">No hay marcas</td></tr>
        @endforelse
    </tbody>
</table>
</div></div>@if($brands->hasPages())<div class="card-footer">{{ $brands->links() }}</div>@endif</div>
@endsection
