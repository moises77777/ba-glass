@extends('layouts.app')
@section('title', 'Nuevo Proveedor')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Nuevo Proveedor</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Proveedores</a></li><li class="breadcrumb-item active">Nuevo</li></ol></nav>
    </div>
    <a href="{{ route('suppliers.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
</div>
<form action="{{ route('suppliers.store') }}" method="POST">@csrf @include('catalogs.suppliers._form', ['supplier' => null])</form>
@endsection
