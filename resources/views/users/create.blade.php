@extends('layouts.app')
@section('title', 'Nuevo Usuario')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Nuevo Usuario</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li><li class="breadcrumb-item active">Nuevo</li></ol></nav>
    </div>
    <a href="{{ route('users.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
</div>
<form action="{{ route('users.store') }}" method="POST">@csrf @include('users._form', ['user' => null])</form>
@endsection
