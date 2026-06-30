@extends('layouts.app')
@section('title', 'Modelos de Equipo')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Modelos de Equipo</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Modelos de Equipo</li>
        </ol></nav>
    </div>
    <a href="{{ route('equipment-models.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Nuevo Modelo
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-10">
                <input type="text" name="q" class="form-control" placeholder="Buscar modelo o marca..." value="{{ request('q') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
                @if(request('q'))
                    <a href="{{ route('equipment-models.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr>
                    <th>MARCA</th>
                    <th>MODELO</th>
                    <th>CATEGORÍA</th>
                    <th>PROCESADOR</th>
                    <th>RAM / ALMAC.</th>
                    <th>PRECIO REF.</th>
                    <th>MONEDA</th>
                    <th>ESTADO</th>
                    <th class="text-end">ACCIONES</th>
                </tr></thead>
                <tbody>
                    @forelse($models as $m)
                    <tr>
                        <td>{{ $m->brand?->name ?? '—' }}</td>
                        <td><strong>{{ $m->name }}</strong></td>
                        <td>{{ $m->category?->name ?? '—' }}</td>
                        <td><small>{{ $m->processor ?? '—' }}</small></td>
                        <td><small>{{ $m->ram ? $m->ram : '—' }}{{ $m->storage ? ' / '.$m->storage : '' }}</small></td>
                        <td>${{ number_format($m->reference_price, 2) }}</td>
                        <td>{{ $m->currency }}</td>
                        <td>
                            @if($m->is_active)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('equipment-models.edit', $m) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('equipment-models.destroy', $m) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este modelo?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">No hay modelos registrados</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $models->links() }}</div>
@endsection
