@extends('layouts.app')

@section('title', 'Editar Mantenimiento')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Editar Mantenimiento</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('maintenance.index') }}">Mantenimientos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('maintenance.show', $maintenance) }}">{{ $maintenance->ticket_number ?? '#' . $maintenance->id }}</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('maintenance.show', $maintenance) }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
</div>

<form action="{{ route('maintenance.update', $maintenance) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-tools me-2"></i>Información del Mantenimiento</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Equipo</label>
                            <input type="text" class="form-control" value="{{ $maintenance->equipment->internal_code ?? '' }} - {{ $maintenance->equipment->brand?->name }} {{ $maintenance->equipment->model ?? '' }}" readonly>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Título <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $maintenance->title) }}" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tipo <span class="text-danger">*</span></label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="preventive" {{ old('type', $maintenance->type) == 'preventive' ? 'selected' : '' }}>Preventivo</option>
                                <option value="corrective" {{ old('type', $maintenance->type) == 'corrective' ? 'selected' : '' }}>Correctivo</option>
                                <option value="upgrade" {{ old('type', $maintenance->type) == 'upgrade' ? 'selected' : '' }}>Actualización</option>
                                <option value="cleaning" {{ old('type', $maintenance->type) == 'cleaning' ? 'selected' : '' }}>Limpieza</option>
                                <option value="inspection" {{ old('type', $maintenance->type) == 'inspection' ? 'selected' : '' }}>Inspección</option>
                                <option value="other" {{ old('type', $maintenance->type) == 'other' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Prioridad <span class="text-danger">*</span></label>
                            <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                <option value="low" {{ old('priority', $maintenance->priority) == 'low' ? 'selected' : '' }}>Baja</option>
                                <option value="medium" {{ old('priority', $maintenance->priority) == 'medium' ? 'selected' : '' }}>Media</option>
                                <option value="high" {{ old('priority', $maintenance->priority) == 'high' ? 'selected' : '' }}>Alta</option>
                                <option value="critical" {{ old('priority', $maintenance->priority) == 'critical' ? 'selected' : '' }}>Crítica</option>
                            </select>
                            @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Estado <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="pending" {{ old('status', $maintenance->status) == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="in_progress" {{ old('status', $maintenance->status) == 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                                <option value="completed" {{ old('status', $maintenance->status) == 'completed' ? 'selected' : '' }}>Completado</option>
                                <option value="cancelled" {{ old('status', $maintenance->status) == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                <option value="on_hold" {{ old('status', $maintenance->status) == 'on_hold' ? 'selected' : '' }}>En Espera</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción del Problema <span class="text-danger">*</span></label>
                            <textarea name="problem_description" class="form-control @error('problem_description') is-invalid @enderror" rows="3" required>{{ old('problem_description', $maintenance->problem_description) }}</textarea>
                            @error('problem_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Diagnóstico</label>
                            <textarea name="diagnosis" class="form-control" rows="2">{{ old('diagnosis', $maintenance->diagnosis) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Trabajo Realizado / Solución</label>
                            <textarea name="solution" class="form-control" rows="3">{{ old('solution', $maintenance->solution) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Partes Reemplazadas</label>
                            <textarea name="parts_replaced" class="form-control" rows="2">{{ old('parts_replaced', $maintenance->parts_replaced) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Condición Antes</label>
                            <select name="condition_before" class="form-select">
                                <option value="">— Sin especificar —</option>
                                <option value="excellent" {{ old('condition_before', $maintenance->condition_before) == 'excellent' ? 'selected' : '' }}>Excelente</option>
                                <option value="good" {{ old('condition_before', $maintenance->condition_before) == 'good' ? 'selected' : '' }}>Bueno</option>
                                <option value="fair" {{ old('condition_before', $maintenance->condition_before) == 'fair' ? 'selected' : '' }}>Regular</option>
                                <option value="poor" {{ old('condition_before', $maintenance->condition_before) == 'poor' ? 'selected' : '' }}>Malo</option>
                                <option value="damaged" {{ old('condition_before', $maintenance->condition_before) == 'damaged' ? 'selected' : '' }}>Dañado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Condición Después</label>
                            <select name="condition_after" class="form-select">
                                <option value="">— Sin especificar —</option>
                                <option value="excellent" {{ old('condition_after', $maintenance->condition_after) == 'excellent' ? 'selected' : '' }}>Excelente</option>
                                <option value="good" {{ old('condition_after', $maintenance->condition_after) == 'good' ? 'selected' : '' }}>Bueno</option>
                                <option value="fair" {{ old('condition_after', $maintenance->condition_after) == 'fair' ? 'selected' : '' }}>Regular</option>
                                <option value="poor" {{ old('condition_after', $maintenance->condition_after) == 'poor' ? 'selected' : '' }}>Malo</option>
                                <option value="damaged" {{ old('condition_after', $maintenance->condition_after) == 'damaged' ? 'selected' : '' }}>Dañado</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notas adicionales</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes', $maintenance->notes) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-people me-2"></i>Proveedor y Técnico</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Proveedor</label>
                            <select name="supplier_id" class="form-select">
                                <option value="">— Interno —</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $maintenance->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Técnico Asignado</label>
                            <select name="assigned_to" class="form-select">
                                <option value="">— Sin asignar —</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to', $maintenance->assigned_to) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombre del Técnico Externo</label>
                            <input type="text" name="technician_name" class="form-control" value="{{ old('technician_name', $maintenance->technician_name) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono del Técnico</label>
                            <input type="text" name="technician_phone" class="form-control" value="{{ old('technician_phone', $maintenance->technician_phone) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-calendar-check me-2"></i>Programación</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Fecha Programada</label>
                        <input type="date" name="scheduled_date" class="form-control"
                               value="{{ old('scheduled_date', $maintenance->scheduled_date?->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-cash-coin me-2"></i>Costos</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Costo Mano de Obra</label>
                        <input type="number" name="labor_cost" class="form-control" value="{{ old('labor_cost', $maintenance->labor_cost) }}" step="0.01" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Costo de Partes</label>
                        <input type="number" name="parts_cost" class="form-control" value="{{ old('parts_cost', $maintenance->parts_cost) }}" step="0.01" min="0">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-lg me-2"></i>Guardar Cambios
                        </button>
                        <a href="{{ route('maintenance.show', $maintenance) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-2"></i>Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
