@extends('layouts.app')

@section('title', 'Nuevo Mantenimiento')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Nuevo Mantenimiento</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('maintenance.index') }}">Mantenimientos</a></li>
                <li class="breadcrumb-item active">Nuevo</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('maintenance.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
</div>

<form action="{{ route('maintenance.store') }}" method="POST">
    @csrf
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-tools me-2"></i>Información del Mantenimiento</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Equipo <span class="text-danger">*</span></label>
                            <select name="equipment_id" class="form-select @error('equipment_id') is-invalid @enderror" required>
                                <option value="">Seleccionar equipo...</option>
                                @foreach($equipment as $eq)
                                    <option value="{{ $eq->id }}" {{ old('equipment_id', $selectedEquipment?->id) == $eq->id ? 'selected' : '' }}>
                                        {{ $eq->internal_code }} - {{ $eq->brand?->name }} {{ $eq->model }}
                                    </option>
                                @endforeach
                            </select>
                            @error('equipment_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Título <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}" required placeholder="Ej: Revisión general del equipo">
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tipo <span class="text-danger">*</span></label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="preventive" {{ old('type') == 'preventive' ? 'selected' : '' }}>Preventivo</option>
                                <option value="corrective" {{ old('type', 'corrective') == 'corrective' ? 'selected' : '' }}>Correctivo</option>
                                <option value="upgrade" {{ old('type') == 'upgrade' ? 'selected' : '' }}>Actualización</option>
                                <option value="cleaning" {{ old('type') == 'cleaning' ? 'selected' : '' }}>Limpieza</option>
                                <option value="inspection" {{ old('type') == 'inspection' ? 'selected' : '' }}>Inspección</option>
                                <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Prioridad <span class="text-danger">*</span></label>
                            <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Baja</option>
                                <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Media</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Alta</option>
                                <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Crítica</option>
                            </select>
                            @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Condición Antes</label>
                            <select name="condition_before" class="form-select">
                                <option value="">— Sin especificar —</option>
                                <option value="excellent" {{ old('condition_before') == 'excellent' ? 'selected' : '' }}>Excelente</option>
                                <option value="good" {{ old('condition_before') == 'good' ? 'selected' : '' }}>Bueno</option>
                                <option value="fair" {{ old('condition_before') == 'fair' ? 'selected' : '' }}>Regular</option>
                                <option value="poor" {{ old('condition_before') == 'poor' ? 'selected' : '' }}>Malo</option>
                                <option value="damaged" {{ old('condition_before') == 'damaged' ? 'selected' : '' }}>Dañado</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción del Problema <span class="text-danger">*</span></label>
                            <textarea name="problem_description" class="form-control @error('problem_description') is-invalid @enderror" rows="3" required>{{ old('problem_description') }}</textarea>
                            @error('problem_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notas adicionales</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-cash-coin me-2"></i>Proveedor y Técnico</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Proveedor</label>
                            <select name="supplier_id" class="form-select">
                                <option value="">— Interno —</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
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
                                    <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
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
                        <input type="date" name="scheduled_date" class="form-control" value="{{ old('scheduled_date') }}">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-lg me-2"></i>Registrar Mantenimiento
                        </button>
                        <a href="{{ route('maintenance.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-2"></i>Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
