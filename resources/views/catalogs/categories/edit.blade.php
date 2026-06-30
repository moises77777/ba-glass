@extends('layouts.app')
@section('title', 'Editar Categoría')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Editar: {{ $category->name }}</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categorías</a></li><li class="breadcrumb-item active">Editar</li></ol></nav>
    </div>
    <a href="{{ route('categories.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
</div>
<form action="{{ route('categories.update', $category) }}" method="POST">@csrf @method('PUT') @include('catalogs.categories._form')</form>
@endsection
