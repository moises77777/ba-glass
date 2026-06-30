@extends('layouts.app')
@section('title', 'Compra ' . $bulkPurchase->folio)
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Compra {{ $bulkPurchase->folio }}</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bulk-purchases.index') }}">Compras Masivas</a></li>
            <li class="breadcrumb-item active">{{ $bulkPurchase->folio }}</li>
        </ol></nav>
    </div>
    <a href="{{ route('bulk-purchases.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-receipt me-2"></i>Resumen de Compra</div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><th class="text-muted" style="width:45%">Folio</th><td><strong>{{ $bulkPurchase->folio }}</strong></td></tr>
                    <tr><th class="text-muted">Marca</th><td>{{ $bulkPurchase->brand?->name ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Modelo</th><td><strong>{{ $bulkPurchase->model_name }}</strong></td></tr>
                    <tr><th class="text-muted">Categoría</th><td>{{ $bulkPurchase->category?->name ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Cantidad</th><td><span class="badge bg-primary">{{ $bulkPurchase->quantity }}</span></td></tr>
                    <tr><th class="text-muted">Precio Unit.</th><td>${{ number_format($bulkPurchase->unit_price, 2) }} {{ $bulkPurchase->currency }}</td></tr>
                    <tr><th class="text-muted">Total</th><td><strong>${{ number_format($bulkPurchase->unit_price * $bulkPurchase->quantity, 2) }}</strong></td></tr>
                    <tr><th class="text-muted">Proveedor</th><td>{{ $bulkPurchase->supplier?->name ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Orden Compra</th><td>{{ $bulkPurchase->purchase_order ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Factura</th><td>{{ $bulkPurchase->invoice_number ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Fecha Compra</th><td>{{ $bulkPurchase->purchase_date?->format('d/m/Y') ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Garantía</th><td>{{ $bulkPurchase->warranty_start_date?->format('d/m/Y') ?? '—' }} – {{ $bulkPurchase->warranty_end_date?->format('d/m/Y') ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Registrado por</th><td>{{ $bulkPurchase->createdBy?->name ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Fecha registro</th><td>{{ $bulkPurchase->created_at->format('d/m/Y H:i') }}</td></tr>
                </table>
                @if($bulkPurchase->notes)
                    <hr>
                    <small class="text-muted">{{ $bulkPurchase->notes }}</small>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-laptop me-2"></i>Equipos Registrados ({{ $equipment->count() }})</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>No. Serie</th>
                            <th>Asset Tag</th>
                            <th>Estado</th>
                            <th>Asignado a</th>
                            <th></th>
                        </tr></thead>
                        <tbody>
                            @forelse($equipment as $i => $eq)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><a href="{{ route('equipment.show', $eq) }}" class="fw-semibold text-primary">{{ $eq->internal_code }}</a></td>
                                <td>{{ $eq->serial_number ?? '—' }}</td>
                                <td>{{ $eq->asset_tag ?? '—' }}</td>
                                <td>
                                    @if($eq->availability_status === 'available')
                                        <span class="badge bg-success">Disponible</span>
                                    @elseif($eq->availability_status === 'assigned')
                                        <span class="badge bg-primary">Asignado</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($eq->availability_status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $eq->currentEmployee?->full_name ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('equipment.show', $eq) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center text-muted py-3">Sin equipos asociados</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
