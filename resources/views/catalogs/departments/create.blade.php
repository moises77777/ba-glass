@extends('layouts.app')
@section('title', 'Nuevo Departamento')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Nuevo Departamento</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('departments.index') }}">Departamentos</a></li><li class="breadcrumb-item active">Nuevo</li></ol></nav>
    </div>
    <a href="{{ route('departments.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
</div>
<form action="{{ route('departments.store') }}" method="POST">
    @csrf
    @include('catalogs.departments._form', ['department' => null])
</form>
@endsection
