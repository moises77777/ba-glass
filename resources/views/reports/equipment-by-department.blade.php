@extends('layouts.app')
@section('title', 'Equipos por Departamento')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Equipos por Departamento</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reportes</a></li><li class="breadcrumb-item active">Por Departamento</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger"><i class="bi bi-file-earmark-pdf me-1"></i>Exportar PDF</a>
    </div>
</div>

<div class="row g-3">
    @forelse($departmentStats as $stat)
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header" style="background:#4f46e5 !important; color:#fff;">
                <i class="bi bi-building me-2"></i><strong>{{ $stat['department']->name }}</strong>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-4 text-center"><h4>{{ $stat['department']->employees_count }}</h4><small>Empleados</small></div>
                    <div class="col-4 text-center"><h4 class="text-primary">{{ $stat['total_equipment'] }}</h4><small>Equipos</small></div>
                    <div class="col-4 text-center"><h4 class="text-success">${{ number_format($stat['total_value'], 0) }}</h4><small>Valor</small></div>
                </div>
                @if($stat['by_category']->isNotEmpty())
                    <h6>Por Categoría:</h6>
                    <ul class="list-unstyled mb-0">
                        @foreach($stat['by_category'] as $catName => $count)
                            <li><i class="bi bi-dot"></i>{{ $catName ?? 'Sin categoría' }}: <strong>{{ $count }}</strong></li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12"><div class="alert alert-info">No hay datos</div></div>
    @endforelse
</div>
@endsection
