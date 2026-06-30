@extends('layouts.app')
@section('title', 'Editar Puesto')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Editar: {{ $position->name }}</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('positions.index') }}">Puestos</a></li><li class="breadcrumb-item active">Editar</li></ol></nav>
    </div>
    <a href="{{ route('positions.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
</div>
<form action="{{ route('positions.update', $position) }}" method="POST">@csrf @method('PUT') @include('catalogs.positions._form')</form>
@endsection
