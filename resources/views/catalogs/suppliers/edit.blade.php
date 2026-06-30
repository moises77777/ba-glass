@extends('layouts.app')
@section('title', 'Editar Proveedor')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Editar: {{ $supplier->name }}</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Proveedores</a></li><li class="breadcrumb-item active">Editar</li></ol></nav>
    </div>
    <a href="{{ route('suppliers.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
</div>
<form action="{{ route('suppliers.update', $supplier) }}" method="POST">@csrf @method('PUT') @include('catalogs.suppliers._form')</form>
@endsection
