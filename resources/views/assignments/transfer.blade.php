@extends('layouts.app')

@section('title', 'Transferir Equipo')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Transferir Equipo</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('assignments.index') }}">Asignaciones</a></li>
                <li class="breadcrumb-item"><a href="{{ route('assignments.show', $assignment) }}">{{ $assignment->assignment_number }}</a></li>
                <li class="breadcrumb-item active">Transferir</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('assignments.show', $assignment) }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
</div>

<form action="{{ route('assignments.process-transfer', $assignment) }}" method="POST">
    @csrf
    
    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Current Assignment Info -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <i class="bi bi-arrow-left-right me-2"></i>Asignacion Actual
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Equipo</h6>
                            <p class="mb-2">
                                <strong>{{ $assignment->equipment->internal_code }}</strong><br>
                                {{ $assignment->equipment->brand?->name }} {{ $assignment->equipment->model }}<br>
                                <small class="text-muted">Serie: {{ $assignment->equipment->serial_number ?? 'N/A' }}</small>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Empleado Actual</h6>
                            <p class="mb-2">
                                <strong>{{ $assignment->employee->full_name }}</strong><br>
                                {{ $assignment->employee->position->name }}<br>
                                <small class="text-muted">{{ $assignment->employee->department->name }}</small>
                            </p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <small class="text-muted">Fecha Asignacion:</small>
                            <div>{{ $assignment->assignment_date->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Condicion al Entregar:</small>
                            <div>
                                <span class="badge bg-{{ $assignment->condition_at_assignment == 'excellent' || $assignment->condition_at_assignment == 'good' ? 'success' : 'warning' }}">
                                    {{ $assignment->condition_at_assignment_name }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Tiempo Asignado:</small>
                            <div>{{ $assignment->duration }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transfer Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-clipboard-check me-2"></i>Detalles de la Transferencia
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nuevo Empleado <span class="text-danger">*</span></label>
                            <select name="new_employee_id" class="form-select @error('new_employee_id') is-invalid @enderror" required>
                                <option value="">Seleccionar...</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ old('new_employee_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->full_name }} - {{ $emp->department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('new_employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Transferencia <span class="text-danger">*</span></label>
                            <input type="date" name="transfer_date" class="form-control @error('transfer_date') is-invalid @enderror" 
                                   value="{{ old('transfer_date', now()->format('Y-m-d')) }}" required>
                            @error('transfer_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Condicion del Equipo <span class="text-danger">*</span></label>
                            <select name="condition_at_transfer" class="form-select @error('condition_at_transfer') is-invalid @enderror" required>
                                @foreach(\App\Models\Assignment::CONDITIONS as $key => $value)
                                    <option value="{{ $key }}" {{ old('condition_at_transfer', $assignment->condition_at_assignment) == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('condition_at_transfer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ubicacion</label>
                            <select name="location_id" class="form-select @error('location_id') is-invalid @enderror">
                                <option value="">Seleccionar...</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('location_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Area de Trabajo</label>
                            <input type="text" name="work_area" class="form-control @error('work_area') is-invalid @enderror" 
                                   value="{{ old('work_area') }}" placeholder="Ej: Oficina de Sistemas">
                            @error('work_area')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notas de Transferencia</label>
                            <textarea name="transfer_notes" class="form-control @error('transfer_notes') is-invalid @enderror" rows="3" 
                                      placeholder="Observaciones sobre la transferencia...">{{ old('transfer_notes') }}</textarea>
                            @error('transfer_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Warning -->
            <div class="alert alert-info">
                <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Importante</h6>
                <p class="small mb-0">
                    Al procesar la transferencia:
                </p>
                <ul class="small mb-0 mt-2">
                    <li>La asignacion actual se cerrara</li>
                    <li>Se creara una nueva asignacion al empleado destino</li>
                    <li>Se actualizara el historial del equipo</li>
                </ul>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-info btn-lg text-white">
                            <i class="bi bi-arrow-left-right me-2"></i>Procesar Transferencia
                        </button>
                        <a href="{{ route('assignments.show', $assignment) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-2"></i>Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
