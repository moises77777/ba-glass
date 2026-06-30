@extends('layouts.app')
@section('title', 'Proveedores')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Proveedores</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li><li class="breadcrumb-item active">Proveedores</li></ol></nav>
    </div>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo Proveedor</a>
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead><tr><th>Nombre</th><th>RFC</th><th>Contacto</th><th>Email/Teléfono</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
        @forelse($suppliers as $s)
        <tr>
            <td><strong>{{ $s->name }}</strong></td>
            <td><code>{{ $s->rfc ?? '-' }}</code></td>
            <td>{{ $s->contact_name ?? '-' }}</td>
            <td>{{ $s->email ?? '-' }}<br><small>{{ $s->phone ?? '' }}</small></td>
            <td>@if($s->is_active)<span class="badge bg-success">Activo</span>@else<span class="badge bg-secondary">Inactivo</span>@endif</td>
            <td class="text-end">
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('suppliers.edit', $s) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('suppliers.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                        @csrf @method('DELETE')<button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center py-4 text-muted">No hay proveedores</td></tr>
        @endforelse
    </tbody>
</table>
</div></div>@if($suppliers->hasPages())<div class="card-footer">{{ $suppliers->links() }}</div>@endif</div>
@endsection
