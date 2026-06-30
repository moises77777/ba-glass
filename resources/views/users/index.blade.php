@extends('layouts.app')
@section('title', 'Usuarios')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Usuarios del Sistema</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li><li class="breadcrumb-item active">Usuarios</li></ol></nav>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo Usuario</a>
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead><tr><th>Usuario</th><th>Email</th><th>Rol</th><th>Último Acceso</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
        @forelse($users as $user)
        <tr>
            <td>
                <div class="d-flex align-items-center">
                    <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" class="rounded-circle me-2" width="32" height="32" style="object-fit:cover">
                    <strong>{{ $user->name }}</strong>
                </div>
            </td>
            <td>{{ $user->email }}</td>
            <td>
                @foreach($user->roles as $role)
                    <span class="badge bg-primary">{{ $role->name }}</span>
                @endforeach
            </td>
            <td>{{ $user->last_login_at?->format('d/m/Y H:i') ?? 'Nunca' }}</td>
            <td>@if($user->is_active)<span class="badge bg-success">Activo</span>@else<span class="badge bg-secondary">Inactivo</span>@endif</td>
            <td class="text-end">
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                    @if($user->id !== auth()->id())
                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar usuario?')">
                        @csrf @method('DELETE')<button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                    @endif
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center py-4 text-muted">No hay usuarios</td></tr>
        @endforelse
    </tbody>
</table>
</div></div>@if($users->hasPages())<div class="card-footer">{{ $users->links() }}</div>@endif</div>
@endsection
