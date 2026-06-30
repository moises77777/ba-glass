@extends('layouts.app')
@section('title', 'Compras Masivas')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Compras Masivas</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Compras Masivas</li>
        </ol></nav>
    </div>
    <a href="{{ route('bulk-purchases.create') }}" class="btn btn-primary">
        <i class="bi bi-cart-plus me-1"></i>Nueva Compra
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr>
                    <th>FOLIO</th>
                    <th>MODELO</th>
                    <th>CANT.</th>
                    <th>PRECIO UNIT.</th>
                    <th>PROVEEDOR</th>
                    <th>FECHA COMPRA</th>
                    <th>REGISTRADO POR</th>
                    <th class="text-end">ACCIONES</th>
                </tr></thead>
                <tbody>
                    @forelse($purchases as $p)
                    <tr>
                        <td><a href="{{ route('bulk-purchases.show', $p) }}" class="fw-semibold text-primary">{{ $p->folio }}</a></td>
                        <td>
                            {{ $p->brand?->name }} <strong>{{ $p->model_name }}</strong>
                            @if($p->equipmentModel)
                                <br><small class="text-muted">Modelo: {{ $p->equipmentModel->name }}</small>
                            @endif
                        </td>
                        <td><span class="badge bg-primary">{{ $p->quantity }}</span></td>
                        <td>${{ number_format($p->unit_price, 2) }} {{ $p->currency }}</td>
                        <td>{{ $p->supplier?->name ?? '—' }}</td>
                        <td>{{ $p->purchase_date?->format('d/m/Y') ?? '—' }}</td>
                        <td>{{ $p->createdBy?->name ?? '—' }}</td>
                        <td class="text-end">
                            <a href="{{ route('bulk-purchases.show', $p) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No hay compras masivas registradas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $purchases->links() }}</div>
@endsection
