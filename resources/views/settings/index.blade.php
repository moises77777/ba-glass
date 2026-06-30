@extends('layouts.app')
@section('title', 'Configuración del Sistema')
@section('content')
<div class="page-header">
    <h1 class="page-title">Configuración del Sistema</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li><li class="breadcrumb-item active">Configuración</li></ol></nav>
</div>

<form action="{{ route('settings.update') }}" method="POST">
    @csrf
    @forelse($settings as $group => $items)
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-gear me-2"></i>{{ ucfirst($group ?? 'General') }}
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($items as $setting)
                <div class="col-md-6">
                    <label class="form-label">{{ $setting->label ?? Str::title(str_replace('_', ' ', $setting->key)) }}</label>
                    @if($setting->type === 'boolean')
                        <div class="form-check form-switch">
                            <input type="hidden" name="settings[{{ $setting->key }}]" value="0">
                            <input type="checkbox" class="form-check-input" name="settings[{{ $setting->key }}]" value="1" {{ $setting->value ? 'checked' : '' }}>
                        </div>
                    @elseif($setting->type === 'textarea')
                        <textarea name="settings[{{ $setting->key }}]" class="form-control" rows="3">{{ $setting->value }}</textarea>
                    @elseif($setting->type === 'number')
                        <input type="number" name="settings[{{ $setting->key }}]" class="form-control" value="{{ $setting->value }}">
                    @else
                        <input type="text" name="settings[{{ $setting->key }}]" class="form-control" value="{{ $setting->value }}">
                    @endif
                    @if($setting->description)
                        <small class="form-text text-muted">{{ $setting->description }}</small>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>No hay configuraciones registradas en el sistema.
    </div>
    @endforelse

    @if($settings->isNotEmpty())
    <div class="card">
        <div class="card-body text-end">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-check-lg me-2"></i>Guardar Configuración
            </button>
        </div>
    </div>
    @endif
</form>
@endsection
