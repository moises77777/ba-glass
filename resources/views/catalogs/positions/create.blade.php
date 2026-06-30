@extends('layouts.app')
@section('title', 'Nuevo Puesto')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Nuevo Puesto</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('positions.index') }}">Puestos</a></li><li class="breadcrumb-item active">Nuevo</li></ol></nav>
    </div>
    <a href="{{ route('positions.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
</div>
<form action="{{ route('positions.store') }}" method="POST">@csrf @include('catalogs.positions._form', ['position' => null])</form>
@endsection
