@extends('layouts.app')
@section('title', 'Editar Departamento')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Editar: {{ $department->name }}</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('departments.index') }}">Departamentos</a></li><li class="breadcrumb-item active">Editar</li></ol></nav>
    </div>
    <a href="{{ route('departments.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
</div>
<form action="{{ route('departments.update', $department) }}" method="POST">
    @csrf @method('PUT')
    @include('catalogs.departments._form')
</form>
@endsection
