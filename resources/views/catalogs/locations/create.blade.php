@extends('layouts.app')
@section('title', 'Nueva Ubicación')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Nueva Ubicación</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('locations.index') }}">Ubicaciones</a></li><li class="breadcrumb-item active">Nueva</li></ol></nav>
    </div>
    <a href="{{ route('locations.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
</div>
<form action="{{ route('locations.store') }}" method="POST">@csrf @include('catalogs.locations._form', ['location' => null])</form>
@endsection
