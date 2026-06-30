@extends('layouts.app')
@section('title', 'Auditoría')
@section('content')
<div class="page-header">
    <h1 class="page-title">Registro de Auditoría</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li><li class="breadcrumb-item active">Auditoría</li></ol></nav>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('audit.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Acción, descripción...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Usuario</label>
                    <select name="user_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Desde</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Hasta</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                        <a href="{{ route('audit.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead><tr><th>Fecha</th><th>Usuario</th><th>Acción</th><th>Descripción</th><th>IP</th><th class="text-end">Detalles</th></tr></thead>
    <tbody>
        @forelse($logs as $log)
        <tr>
            <td><small>{{ $log->created_at->format('d/m/Y H:i:s') }}</small></td>
            <td>{{ $log->user?->name ?? 'Sistema' }}</td>
            <td><span class="badge bg-info">{{ $log->action }}</span></td>
            <td>{{ Str::limit($log->description, 80) }}</td>
            <td><code>{{ $log->ip_address ?? '-' }}</code></td>
            <td class="text-end">
                <a href="{{ route('audit.show', $log) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center py-4 text-muted">No hay registros de auditoría</td></tr>
        @endforelse
    </tbody>
</table>
</div></div>@if($logs->hasPages())<div class="card-footer">{{ $logs->links() }}</div>@endif</div>
@endsection
