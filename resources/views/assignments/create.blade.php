@extends('layouts.app')

@section('title', 'Nueva Asignación')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Nueva Asignación</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('assignments.index') }}">Asignaciones</a></li>
                <li class="breadcrumb-item active">Nueva</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('assignments.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
</div>

<form action="{{ route('assignments.store') }}" method="POST">
    @csrf
    
    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Equipment Selection -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-laptop me-2"></i>Seleccionar Equipo
                </div>
                <div class="card-body">
                    @if($equipment)
                        <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">
                        <div class="alert alert-info mb-0">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-laptop fs-3 me-3"></i>
                                <div>
                                    <strong>{{ $equipment->internal_code }}</strong>
                                    <br>{{ $equipment->brand?->name }} {{ $equipment->model }}
                                    <br><small class="text-muted">Serie: {{ $equipment->serial_number ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mb-3">
                            <label class="form-label">Equipo Disponible <span class="text-danger">*</span></label>
                            <select name="equipment_id" class="form-select @error('equipment_id') is-invalid @enderror" required>
                                <option value="">Seleccionar equipo...</option>
                                @foreach($availableEquipment as $eq)
                                    <option value="{{ $eq->id }}" {{ old('equipment_id') == $eq->id ? 'selected' : '' }}>
                                        {{ $eq->internal_code }} - {{ $eq->brand?->name }} {{ $eq->model }} 
                                        ({{ $eq->category->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('equipment_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                </div>
            </div>

            <!-- Employee Selection -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-person me-2"></i>Seleccionar Empleado
                </div>
                <div class="card-body">
                    @if($employee)
                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                        <div class="alert alert-info mb-0">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center me-3" style="width:50px;height:50px;">
                                    <i class="bi bi-person-fill fs-4 text-primary"></i>
                                </div>
                                <div>
                                    <strong>{{ $employee->full_name }}</strong>
                                    <br>{{ $employee->position->name }}
                                    <br><small class="text-muted">{{ $employee->department->name }}</small>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mb-3">
                            <label class="form-label">Empleado <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                                <option value="">Seleccionar empleado...</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->full_name }} - {{ $emp->department->name }} ({{ $emp->position->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                </div>
            </div>

            <!-- Assignment Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-clipboard-check me-2"></i>Detalles de la Asignación
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Asignación <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="assignment_date" class="form-control @error('assignment_date') is-invalid @enderror" 
                                   value="{{ old('assignment_date', now()->format('Y-m-d\TH:i')) }}" required>
                            @error('assignment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha Esperada de Devolución</label>
                            <input type="date" name="expected_return_date" class="form-control @error('expected_return_date') is-invalid @enderror" 
                                   value="{{ old('expected_return_date') }}">
                            @error('expected_return_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Condición del Equipo <span class="text-danger">*</span></label>
                            <select name="condition_at_assignment" class="form-select @error('condition_at_assignment') is-invalid @enderror" required>
                                @foreach(\App\Models\Assignment::CONDITIONS as $key => $value)
                                    <option value="{{ $key }}" {{ old('condition_at_assignment', 'good') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('condition_at_assignment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ubicación de Uso</label>
                            <select name="location_id" class="form-select">
                                <option value="">Seleccionar...</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                        {{ $location->full_path }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Área de Trabajo</label>
                            <input type="text" name="work_area" class="form-control" value="{{ old('work_area') }}" 
                                   placeholder="Ej: Escritorio 5, Cubículo A3...">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Accesorios Entregados</label>
                            <textarea name="accessories_delivered" class="form-control" rows="2" 
                                      placeholder="Ej: Cargador original, mouse inalámbrico, funda...">{{ old('accessories_delivered') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notas de Asignación</label>
                            <textarea name="assignment_notes" class="form-control" rows="3" 
                                      placeholder="Observaciones adicionales...">{{ old('assignment_notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Summary -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-info-circle me-2"></i>Resumen
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        Al completar esta asignación se generará automáticamente una carta responsiva con folio único.
                    </p>
                    <ul class="list-unstyled small text-muted">
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>El equipo quedará marcado como "Asignado"</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Se registrará en el historial del equipo</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Se generará PDF de responsiva</li>
                    </ul>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-lg me-2"></i>Realizar Asignación
                        </button>
                        <a href="{{ route('assignments.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-2"></i>Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
