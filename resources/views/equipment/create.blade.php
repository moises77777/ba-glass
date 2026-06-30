@extends('layouts.app')

@section('title', 'Nuevo Equipo')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Nuevo Equipo</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('equipment.index') }}">Equipos</a></li>
                <li class="breadcrumb-item active">Nuevo</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('equipment.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
</div>

<form id="equipmentForm" action="{{ route('equipment.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Identification -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-tag me-2"></i>Identificación
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Categoría <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">Seleccionar...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Marca</label>
                            <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                                <option value="">Seleccionar...</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Modelo</label>
                            <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" 
                                   value="{{ old('model') }}">
                            @error('model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Número de Serie</label>
                            <input type="text" name="serial_number" class="form-control @error('serial_number') is-invalid @enderror" 
                                   value="{{ old('serial_number') }}">
                            @error('serial_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technical Specs -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-cpu me-2"></i>Especificaciones Técnicas
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Procesador</label>
                            <input type="text" name="processor" class="form-control" value="{{ old('processor') }}" 
                                   placeholder="Ej: Intel Core i7-1365U">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">RAM</label>
                            <input type="text" name="ram" class="form-control" value="{{ old('ram') }}" 
                                   placeholder="Ej: 16 GB DDR5">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Almacenamiento</label>
                            <input type="text" name="storage" class="form-control" value="{{ old('storage') }}" 
                                   placeholder="Ej: 512 GB">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipo Almacenamiento</label>
                            <select name="storage_type" class="form-select">
                                <option value="">Seleccionar...</option>
                                <option value="SSD" {{ old('storage_type') == 'SSD' ? 'selected' : '' }}>SSD</option>
                                <option value="NVMe SSD" {{ old('storage_type') == 'NVMe SSD' ? 'selected' : '' }}>NVMe SSD</option>
                                <option value="HDD" {{ old('storage_type') == 'HDD' ? 'selected' : '' }}>HDD</option>
                                <option value="Hybrid" {{ old('storage_type') == 'Hybrid' ? 'selected' : '' }}>Híbrido</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tarjeta Gráfica</label>
                            <input type="text" name="graphics_card" class="form-control" value="{{ old('graphics_card') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tamaño Pantalla</label>
                            <input type="text" name="screen_size" class="form-control" value="{{ old('screen_size') }}" 
                                   placeholder="Ej: 15.6&quot;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Resolución</label>
                            <input type="text" name="screen_resolution" class="form-control" value="{{ old('screen_resolution') }}" 
                                   placeholder="Ej: 1920x1080">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sistema Operativo</label>
                            <input type="text" name="operating_system" class="form-control" value="{{ old('operating_system') }}" 
                                   placeholder="Ej: Windows 11 Pro">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Versión SO</label>
                            <input type="text" name="os_version" class="form-control" value="{{ old('os_version') }}" 
                                   placeholder="Ej: 23H2">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Licencia SO</label>
                            <input type="text" name="os_license_key" class="form-control" value="{{ old('os_license_key') }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Purchase Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-receipt me-2"></i>Información de Compra
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Proveedor</label>
                            <select name="supplier_id" class="form-select">
                                <option value="">Seleccionar...</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Orden de Compra</label>
                            <input type="text" name="purchase_order" class="form-control" value="{{ old('purchase_order') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">No. Factura</label>
                            <input type="text" name="invoice_number" class="form-control" value="{{ old('invoice_number') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Fecha de Compra</label>
                            <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Precio</label>
                            <input type="number" name="purchase_price" class="form-control" value="{{ old('purchase_price') }}" 
                                   step="0.01" min="0">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Moneda</label>
                            <select name="currency" class="form-select">
                                <option value="MXN" {{ old('currency', 'MXN') == 'MXN' ? 'selected' : '' }}>MXN</option>
                                <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tipo de Garantía</label>
                            <input type="text" name="warranty_type" class="form-control" value="{{ old('warranty_type') }}" 
                                   placeholder="Ej: ProSupport 3 años">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Inicio Garantía</label>
                            <input type="date" name="warranty_start_date" class="form-control" value="{{ old('warranty_start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Fin Garantía</label>
                            <input type="date" name="warranty_end_date" class="form-control" value="{{ old('warranty_end_date') }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-card-text me-2"></i>Notas Adicionales
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                        </div>
                        <div class="col-12">
                            <h6 class="fw-bold mb-3"><i class="bi bi-box-seam me-2"></i>Accesorios Entregados</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="has_charger" id="has_charger" value="1" {{ old('has_charger') ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_charger"><i class="bi bi-plug me-1"></i>Cargador</label>
                            </div>
                            <input type="text" name="charger_details" class="form-control form-control-sm mt-1" placeholder="Marca/modelo del cargador..." value="{{ old('charger_details') }}" style="display:{{ old('has_charger') ? 'block' : 'none' }}" id="charger_details_input">
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="has_mouse" id="has_mouse" value="1" {{ old('has_mouse') ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_mouse"><i class="bi bi-mouse me-1"></i>Mouse</label>
                            </div>
                            <input type="text" name="mouse_details" class="form-control form-control-sm mt-1" placeholder="Marca/modelo del mouse..." value="{{ old('mouse_details') }}" style="display:{{ old('has_mouse') ? 'block' : 'none' }}" id="mouse_details_input">
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_keyboard" id="has_keyboard" value="1" {{ old('has_keyboard') ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_keyboard"><i class="bi bi-keyboard me-1"></i>Teclado</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_power_strip" id="has_power_strip" value="1" {{ old('has_power_strip') ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_power_strip"><i class="bi bi-outlet me-1"></i>Multicontacto</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_bag_case" id="has_bag_case" value="1" {{ old('has_bag_case') ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_bag_case"><i class="bi bi-bag me-1"></i>Funda / Mochila</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Adaptadores</label>
                            <input type="text" name="adapters" class="form-control" placeholder="Ej: HDMI a VGA, USB-C a HDMI..." value="{{ old('adapters') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Otros Accesorios</label>
                            <input type="text" name="other_accessories" class="form-control" placeholder="Especifica otros accesorios entregados..." value="{{ old('other_accessories') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Observaciones</label>
                            <textarea name="observations" class="form-control" rows="2">{{ old('observations') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-toggle-on me-2"></i>Estado del Equipo
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Condición Física <span class="text-danger">*</span></label>
                        <select name="physical_condition" class="form-select @error('physical_condition') is-invalid @enderror" required>
                            @foreach(\App\Models\Equipment::PHYSICAL_CONDITIONS as $key => $value)
                                <option value="{{ $key }}" {{ old('physical_condition', 'good') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        @error('physical_condition')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado Operativo <span class="text-danger">*</span></label>
                        <select name="operational_status" class="form-select @error('operational_status') is-invalid @enderror" required>
                            @foreach(\App\Models\Equipment::OPERATIONAL_STATUSES as $key => $value)
                                <option value="{{ $key }}" {{ old('operational_status', 'operational') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        @error('operational_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-lg me-2"></i>Guardar Equipo
                        </button>
                        <a href="{{ route('equipment.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-2"></i>Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@push('scripts')
<script>
    document.getElementById('equipmentForm').addEventListener('submit', function(e) {
        if (!confirm('¿Estás seguro de registrar este equipo? Verifica que los datos sean correctos antes de continuar.')) {
            e.preventDefault();
        }
    });

    function toggleAccessory(checkboxId, inputId) {
        const cb = document.getElementById(checkboxId);
        const input = document.getElementById(inputId);
        if (cb && input) {
            input.style.display = cb.checked ? 'block' : 'none';
            if (!cb.checked) input.value = '';
        }
    }
    ['has_charger','has_mouse'].forEach(function(id) {
        const cb = document.getElementById(id);
        if (cb) cb.addEventListener('change', function() {
            toggleAccessory(id, id.replace('has_','') + '_details_input');
        });
    });
</script>
@endpush
@endsection
