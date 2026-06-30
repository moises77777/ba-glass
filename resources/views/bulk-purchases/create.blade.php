@extends('layouts.app')
@section('title', 'Nueva Compra Masiva')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Nueva Compra Masiva</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bulk-purchases.index') }}">Compras Masivas</a></li>
            <li class="breadcrumb-item active">Nueva</li>
        </ol></nav>
    </div>
    <a href="{{ route('bulk-purchases.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
</div>

@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<form action="{{ route('bulk-purchases.store') }}" method="POST" id="bulkForm">
    @csrf
    <div class="row g-4">
        {{-- Columna principal --}}
        <div class="col-lg-8">

            {{-- Seleccionar modelo guardado --}}
            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-box me-2"></i>Modelo de Referencia</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Modelo guardado</label>
                            <select id="equipment_model_id" name="equipment_model_id" class="form-select">
                                <option value="">— Seleccionar modelo guardado —</option>
                                @foreach($equipmentModels as $m)
                                    <option value="{{ $m->id }}"
                                        data-brand="{{ $m->brand_id }}"
                                        data-category="{{ $m->category_id }}"
                                        data-name="{{ $m->name }}"
                                        data-processor="{{ $m->processor }}"
                                        data-ram="{{ $m->ram }}"
                                        data-storage="{{ $m->storage }}"
                                        data-storage-type="{{ $m->storage_type }}"
                                        data-graphics="{{ $m->graphics_card }}"
                                        data-screen="{{ $m->screen_size }}"
                                        data-os="{{ $m->operating_system }}"
                                        data-price="{{ $m->reference_price }}"
                                        data-currency="{{ $m->currency }}"
                                        {{ old('equipment_model_id') == $m->id ? 'selected' : '' }}>
                                        {{ $m->brand?->name }} {{ $m->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Al seleccionar, se autocompletan los campos abajo.</small>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <a href="{{ route('equipment-models.create') }}" target="_blank" class="btn btn-outline-primary w-100">
                                <i class="bi bi-plus-lg me-1"></i>Nuevo Modelo
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info del equipo --}}
            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-laptop me-2"></i>Especificaciones del Equipo</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Categoría <span class="text-danger">*</span></label>
                            <select name="category_id" id="f_category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">Seleccionar</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Marca <span class="text-danger">*</span></label>
                            <select name="brand_id" id="f_brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
                                <option value="">Seleccionar</option>
                                @foreach($brands as $b)
                                    <option value="{{ $b->id }}" {{ old('brand_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Modelo <span class="text-danger">*</span></label>
                            <input type="text" name="model_name" id="f_model_name" class="form-control @error('model_name') is-invalid @enderror"
                                   value="{{ old('model_name') }}" required placeholder="Ej: ProBook 450 G9">
                            @error('model_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Procesador</label>
                            <input type="text" name="processor" id="f_processor" class="form-control" value="{{ old('processor') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">RAM</label>
                            <input type="text" name="ram" id="f_ram" class="form-control" value="{{ old('ram') }}" placeholder="8GB">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Almacenamiento</label>
                            <input type="text" name="storage" id="f_storage" class="form-control" value="{{ old('storage') }}" placeholder="256GB">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipo Almac.</label>
                            <select name="storage_type" id="f_storage_type" class="form-select">
                                <option value="">—</option>
                                @foreach(['SSD','HDD','eMMC','NVMe'] as $st)
                                    <option value="{{ $st }}" {{ old('storage_type') == $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tarjeta Gráfica</label>
                            <input type="text" name="graphics_card" id="f_graphics_card" class="form-control" value="{{ old('graphics_card') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Pantalla</label>
                            <input type="text" name="screen_size" id="f_screen_size" class="form-control" value="{{ old('screen_size') }}" placeholder="15.6">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sistema Operativo</label>
                            <input type="text" name="operating_system" id="f_operating_system" class="form-control" value="{{ old('operating_system') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cond. Física <span class="text-danger">*</span></label>
                            <select name="physical_condition" class="form-select" required>
                                <option value="excellent" {{ old('physical_condition','excellent')=='excellent'?'selected':'' }}>Excelente</option>
                                <option value="good" {{ old('physical_condition','excellent')=='good'?'selected':'' }}>Bueno</option>
                                <option value="fair">Regular</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Estado Operativo <span class="text-danger">*</span></label>
                            <select name="operational_status" class="form-select" required>
                                <option value="pending_setup" {{ old('operational_status','pending_setup')=='pending_setup'?'selected':'' }}>Pendiente de configurar</option>
                                <option value="operational" {{ old('operational_status')=='operational'?'selected':'' }}>Operativo</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabla de unidades --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-list-ol me-2"></i>Unidades a Registrar</span>
                    <button type="button" class="btn btn-sm btn-primary" id="addRow">
                        <i class="bi bi-plus-lg me-1"></i>Agregar fila
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0" id="unitsTable">
                            <thead><tr>
                                <th style="width:40px">#</th>
                                <th>No. Serie</th>
                                <th>Asset Tag</th>
                                <th>Código Interno</th>
                                <th>Ubicación Específica</th>
                                <th>Observaciones</th>
                                <th style="width:40px"></th>
                            </tr></thead>
                            <tbody id="unitsBody">
                                <tr data-index="0">
                                    <td class="text-center fw-bold row-num">1</td>
                                    <td><input type="text" name="items[0][serial_number]" class="form-control form-control-sm" placeholder="S/N"></td>
                                    <td><input type="text" name="items[0][asset_tag]" class="form-control form-control-sm"></td>
                                    <td><input type="text" name="items[0][internal_code]" class="form-control form-control-sm" placeholder="Auto"></td>
                                    <td><input type="text" name="items[0][specific_location]" class="form-control form-control-sm"></td>
                                    <td><input type="text" name="items[0][observations]" class="form-control form-control-sm"></td>
                                    <td><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-trash"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-muted small">
                    <i class="bi bi-info-circle me-1"></i>El Código Interno se genera automáticamente si se deja vacío.
                    Total: <strong id="totalCount">1</strong> unidad(es)
                </div>
            </div>
        </div>

        {{-- Columna lateral --}}
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-receipt me-2"></i>Información de Compra</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Proveedor</label>
                        <select name="supplier_id" class="form-select">
                            <option value="">Seleccionar...</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Orden de Compra</label>
                        <input type="text" name="purchase_order" class="form-control" value="{{ old('purchase_order') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Factura</label>
                        <input type="text" name="invoice_number" class="form-control" value="{{ old('invoice_number') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha de Compra</label>
                        <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date') }}">
                    </div>
                    <div class="row g-2">
                        <div class="col-8">
                            <label class="form-label">Precio Unitario</label>
                            <input type="number" name="unit_price" id="f_unit_price" class="form-control" value="{{ old('unit_price', 0) }}" step="0.01" min="0">
                        </div>
                        <div class="col-4">
                            <label class="form-label">Moneda</label>
                            <select name="currency" id="f_currency" class="form-select">
                                <option value="MXN" {{ old('currency','MXN')=='MXN'?'selected':'' }}>MXN</option>
                                <option value="USD" {{ old('currency')=='USD'?'selected':'' }}>USD</option>
                                <option value="EUR" {{ old('currency')=='EUR'?'selected':'' }}>EUR</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-shield-check me-2"></i>Garantía</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Inicio Garantía</label>
                        <input type="date" name="warranty_start_date" class="form-control" value="{{ old('warranty_start_date') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fin Garantía</label>
                        <input type="date" name="warranty_end_date" class="form-control" value="{{ old('warranty_end_date') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo de Garantía</label>
                        <input type="text" name="warranty_type" class="form-control" value="{{ old('warranty_type') }}" placeholder="Ej: ProSupport 3 años">
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-geo-alt me-2"></i>Ubicación</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Ubicación</label>
                        <select name="location_id" class="form-select">
                            <option value="">Seleccionar...</option>
                            @foreach($locations as $l)
                                <option value="{{ $l->id }}" {{ old('location_id') == $l->id ? 'selected' : '' }}>{{ $l->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notas</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-lg me-1"></i>Registrar Compra
                    </button>
                    <a href="{{ route('bulk-purchases.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
// ─── Autocomplete desde modelo guardado ───────────────────────────────────────
document.getElementById('equipment_model_id').addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    if (!opt.value) return;

    setVal('f_brand_id',         opt.dataset.brand);
    setVal('f_category_id',      opt.dataset.category);
    setVal('f_model_name',       opt.dataset.name);
    setVal('f_processor',        opt.dataset.processor);
    setVal('f_ram',              opt.dataset.ram);
    setVal('f_storage',          opt.dataset.storage);
    setVal('f_storage_type',     opt.dataset.storageType);
    setVal('f_graphics_card',    opt.dataset.graphics);
    setVal('f_screen_size',      opt.dataset.screen);
    setVal('f_operating_system', opt.dataset.os);
    setVal('f_unit_price',       opt.dataset.price);
    setVal('f_currency',         opt.dataset.currency);
});

function setVal(id, val) {
    const el = document.getElementById(id);
    if (!el || val === undefined || val === null || val === '') return;
    el.value = val;
}

// ─── Tabla dinámica de unidades ───────────────────────────────────────────────
let rowCount = 1;

document.getElementById('addRow').addEventListener('click', () => {
    const idx = rowCount++;
    const tr = document.createElement('tr');
    tr.dataset.index = idx;
    tr.innerHTML = `
        <td class="text-center fw-bold row-num">${document.querySelectorAll('#unitsBody tr').length + 1}</td>
        <td><input type="text" name="items[${idx}][serial_number]" class="form-control form-control-sm" placeholder="S/N"></td>
        <td><input type="text" name="items[${idx}][asset_tag]" class="form-control form-control-sm"></td>
        <td><input type="text" name="items[${idx}][internal_code]" class="form-control form-control-sm" placeholder="Auto"></td>
        <td><input type="text" name="items[${idx}][specific_location]" class="form-control form-control-sm"></td>
        <td><input type="text" name="items[${idx}][observations]" class="form-control form-control-sm"></td>
        <td><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-trash"></i></button></td>
    `;
    document.getElementById('unitsBody').appendChild(tr);
    renumber();
});

document.getElementById('unitsBody').addEventListener('click', e => {
    if (e.target.closest('.remove-row')) {
        const rows = document.querySelectorAll('#unitsBody tr');
        if (rows.length <= 1) return;
        e.target.closest('tr').remove();
        renumber();
    }
});

function renumber() {
    const rows = document.querySelectorAll('#unitsBody tr');
    rows.forEach((tr, i) => {
        tr.querySelector('.row-num').textContent = i + 1;
    });
    document.getElementById('totalCount').textContent = rows.length;
}
</script>
@endpush
