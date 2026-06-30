@extends('layouts.app')
@section('title', 'Nuevo Modelo de Equipo')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Nuevo Modelo de Equipo</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('equipment-models.index') }}">Modelos de Equipo</a></li>
            <li class="breadcrumb-item active">Nuevo</li>
        </ol></nav>
    </div>
    <a href="{{ route('equipment-models.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Volver</a>
</div>

<form action="{{ route('equipment-models.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-header"><i class="bi bi-box me-2"></i>Datos del Modelo</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Marca</label>
                    <select name="brand_id" class="form-select">
                        <option value="">Seleccionar...</option>
                        @foreach($brands as $b)
                            <option value="{{ $b->id }}" {{ old('brand_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Categoría</label>
                    <select name="category_id" class="form-select">
                        <option value="">Seleccionar...</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Modelo <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="Ej: Galaxy A54, iPhone 14..." required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Part Number</label>
                    <input type="text" name="part_number" class="form-control" value="{{ old('part_number') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Procesador</label>
                    <input type="text" name="processor" class="form-control" value="{{ old('processor') }}" placeholder="Ej: Intel Core i5-1235U">
                </div>
                <div class="col-md-2">
                    <label class="form-label">RAM</label>
                    <input type="text" name="ram" class="form-control" value="{{ old('ram') }}" placeholder="Ej: 8GB">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Almacenamiento</label>
                    <input type="text" name="storage" class="form-control" value="{{ old('storage') }}" placeholder="Ej: 256GB">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tipo Almac.</label>
                    <select name="storage_type" class="form-select">
                        <option value="">—</option>
                        <option value="SSD" {{ old('storage_type') == 'SSD' ? 'selected' : '' }}>SSD</option>
                        <option value="HDD" {{ old('storage_type') == 'HDD' ? 'selected' : '' }}>HDD</option>
                        <option value="eMMC" {{ old('storage_type') == 'eMMC' ? 'selected' : '' }}>eMMC</option>
                        <option value="NVMe" {{ old('storage_type') == 'NVMe' ? 'selected' : '' }}>NVMe</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tarjeta Gráfica</label>
                    <input type="text" name="graphics_card" class="form-control" value="{{ old('graphics_card') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tamaño Pantalla</label>
                    <input type="text" name="screen_size" class="form-control" value="{{ old('screen_size') }}" placeholder="Ej: 15.6">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sistema Operativo</label>
                    <input type="text" name="operating_system" class="form-control" value="{{ old('operating_system') }}" placeholder="Ej: Windows 11 Pro">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Precio de Referencia</label>
                    <input type="number" name="reference_price" class="form-control" value="{{ old('reference_price', 0) }}" step="0.01" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Moneda</label>
                    <select name="currency" class="form-select">
                        <option value="MXN" {{ old('currency', 'MXN') == 'MXN' ? 'selected' : '' }}>MXN</option>
                        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Notas</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="Observaciones del modelo...">{{ old('notes') }}</textarea>
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', '1') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Modelo activo</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Guardar</button>
            <a href="{{ route('equipment-models.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </div>
</form>
@endsection
