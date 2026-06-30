@extends('layouts.app')
@section('title', 'Detalle de Auditoría')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Registro de Auditoría #{{ $audit->id }}</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('audit.index') }}">Auditoría</a></li><li class="breadcrumb-item active">{{ $audit->id }}</li></ol></nav>
    </div>
    <a href="{{ route('audit.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
</div>

<div class="card mb-4">
    <div class="card-header"><i class="bi bi-info-circle me-2"></i>Información del Registro</div>
    <div class="card-body">
        <table class="table table-sm">
            <tr><td class="text-muted" width="25%">Fecha:</td><td>{{ $audit->created_at->format('d/m/Y H:i:s') }}</td></tr>
            <tr><td class="text-muted">Usuario:</td><td>{{ $audit->user?->name ?? 'Sistema' }} ({{ $audit->user?->email ?? '-' }})</td></tr>
            <tr><td class="text-muted">Acción:</td><td><span class="badge bg-info">{{ $audit->action }}</span></td></tr>
            <tr><td class="text-muted">Modelo:</td><td><code>{{ $audit->model_type }}</code> #{{ $audit->model_id }}</td></tr>
            <tr><td class="text-muted">IP:</td><td><code>{{ $audit->ip_address }}</code></td></tr>
            <tr><td class="text-muted">User Agent:</td><td><small>{{ $audit->user_agent }}</small></td></tr>
            <tr><td class="text-muted">URL:</td><td><code>{{ $audit->url }}</code></td></tr>
            <tr><td class="text-muted">Método:</td><td>{{ $audit->method }}</td></tr>
            <tr><td class="text-muted">Descripción:</td><td>{{ $audit->description }}</td></tr>
        </table>
    </div>
</div>

@if($audit->old_values)
<div class="card mb-4">
    <div class="card-header bg-warning"><i class="bi bi-arrow-counterclockwise me-2"></i>Valores Anteriores</div>
    <div class="card-body">
        <pre class="mb-0">{{ json_encode($audit->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    </div>
</div>
@endif

@if($audit->new_values)
<div class="card">
    <div class="card-header bg-success text-white"><i class="bi bi-arrow-clockwise me-2"></i>Valores Nuevos</div>
    <div class="card-body">
        <pre class="mb-0">{{ json_encode($audit->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    </div>
</div>
@endif
@endsection
