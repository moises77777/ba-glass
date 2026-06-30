@extends('layouts.app')
@section('title', 'Editar Modelo')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Editar Modelo</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('equipment-models.index') }}">Modelos de Equipo</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol></nav>
    </div>
    <a href="{{ route('equipment-models.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Volver</a>
</div>

<form action="{{ route('equipment-models.update', $equipmentModel) }}" method="POST">
    @csrf @method('PUT')
    <div class="card">
        <div class="card-header"><i class="bi bi-box me-2"></i>Datos del Modelo</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Marca</label>
                    <select name="brand_id" class="form-select">
                        <option value="">Seleccionar...</option>
                        @foreach($brands as $b)
                            <option value="{{ $b->id }}" {{ old('brand_id', $equipmentModel->brand_id) == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Categoría</label>
                    <select name="category_id" class="form-select">
                        <option value="">Seleccionar...</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ old('category_id', $equipmentModel->category_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Modelo <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $equipmentModel->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Part Number</label>
                    <input type="text" name="part_number" class="form-control" value="{{ old('part_number', $equipmentModel->part_number) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Procesador</label>
                    <input type="text" name="processor" class="form-control" value="{{ old('processor', $equipmentModel->processor) }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">RAM</label>
                    <input type="text" name="ram" class="form-control" value="{{ old('ram', $equipmentModel->ram) }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Almacenamiento</label>
                    <input type="text" name="storage" class="form-control" value="{{ old('storage', $equipmentModel->storage) }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tipo Almac.</label>
                    <select name="storage_type" class="form-select">
                        <option value="">—</option>
                        @foreach(['SSD','HDD','eMMC','NVMe'] as $st)
                            <option value="{{ $st }}" {{ old('storage_type', $equipmentModel->storage_type) == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tarjeta Gráfica</label>
                    <input type="text" name="graphics_card" class="form-control" value="{{ old('graphics_card', $equipmentModel->graphics_card) }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tamaño Pantalla</label>
                    <input type="text" name="screen_size" class="form-control" value="{{ old('screen_size', $equipmentModel->screen_size) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sistema Operativo</label>
                    <input type="text" name="operating_system" class="form-control" value="{{ old('operating_system', $equipmentModel->operating_system) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Precio de Referencia</label>
                    <input type="number" name="reference_price" class="form-control" value="{{ old('reference_price', $equipmentModel->reference_price) }}" step="0.01" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Moneda</label>
                    <select name="currency" class="form-select">
                        @foreach(['MXN','USD','EUR'] as $cur)
                            <option value="{{ $cur }}" {{ old('currency', $equipmentModel->currency) == $cur ? 'selected' : '' }}>{{ $cur }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Notas</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $equipmentModel->notes) }}</textarea>
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $equipmentModel->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Modelo activo</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Guardar Cambios</button>
            <a href="{{ route('equipment-models.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </div>
</form>
@endsection
