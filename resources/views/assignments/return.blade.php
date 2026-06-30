@extends('layouts.app')

@section('title', 'Devolver Equipo')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Devolver Equipo</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('assignments.index') }}">Asignaciones</a></li>
                <li class="breadcrumb-item"><a href="{{ route('assignments.show', $assignment) }}">{{ $assignment->assignment_number }}</a></li>
                <li class="breadcrumb-item active">Devolver</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('assignments.show', $assignment) }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
</div>

<form action="{{ route('assignments.process-return', $assignment) }}" method="POST">
    @csrf
    
    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Current Assignment Info -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <i class="bi bi-box-arrow-in-left me-2"></i>Asignación Actual
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
                            <h6 class="text-muted">Empleado</h6>
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
                            <small class="text-muted">Fecha Asignación:</small>
                            <div>{{ $assignment->assignment_date->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Condición al Entregar:</small>
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

            <!-- Return Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-clipboard-check me-2"></i>Detalles de la Devolución
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Devolución <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="actual_return_date" class="form-control @error('actual_return_date') is-invalid @enderror" 
                                   value="{{ old('actual_return_date', now()->format('Y-m-d\TH:i')) }}" required>
                            @error('actual_return_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Condición del Equipo <span class="text-danger">*</span></label>
                            <select name="condition_at_return" class="form-select @error('condition_at_return') is-invalid @enderror" required>
                                @foreach(\App\Models\Assignment::CONDITIONS as $key => $value)
                                    <option value="{{ $key }}" {{ old('condition_at_return', $assignment->condition_at_assignment) == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('condition_at_return')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Motivo de Devolución <span class="text-danger">*</span></label>
                            <select name="return_reason" class="form-select @error('return_reason') is-invalid @enderror" required>
                                <option value="">Seleccionar...</option>
                                @foreach(\App\Models\Assignment::RETURN_REASONS as $key => $value)
                                    <option value="{{ $key }}" {{ old('return_reason') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('return_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Detalle del Motivo</label>
                            <input type="text" name="return_reason_details" class="form-control" value="{{ old('return_reason_details') }}" 
                                   placeholder="Información adicional...">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Accesorios Devueltos</label>
                            <textarea name="accessories_returned" class="form-control" rows="2" 
                                      placeholder="Lista de accesorios devueltos...">{{ old('accessories_returned', $assignment->accessories_delivered) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notas de Devolución</label>
                            <textarea name="return_notes" class="form-control" rows="3" 
                                      placeholder="Observaciones sobre el estado del equipo, daños, faltantes...">{{ old('return_notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Warning -->
            <div class="alert alert-warning">
                <h6 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Importante</h6>
                <p class="small mb-0">
                    Al procesar la devolución:
                </p>
                <ul class="small mb-0 mt-2">
                    <li>El equipo quedará disponible para nueva asignación</li>
                    <li>Se actualizará el historial del equipo</li>
                    <li>La asignación se marcará como "Devuelto"</li>
                </ul>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning btn-lg">
                            <i class="bi bi-box-arrow-in-left me-2"></i>Procesar Devolución
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
