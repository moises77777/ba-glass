@extends('layouts.app')

@section('title', 'Equipo: ' . $equipment->internal_code)

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">{{ $equipment->internal_code }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('equipment.index') }}">Equipos</a></li>
                <li class="breadcrumb-item active">{{ $equipment->internal_code }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('equipment.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Regresar</a>
        @if($equipment->isAvailable())
            @can('assignments.create')
            <a href="{{ route('assignments.create', ['equipment_id' => $equipment->id]) }}" class="btn btn-success">
                <i class="bi bi-person-plus me-1"></i>Asignar
            </a>
            @endcan
        @elseif($equipment->isAssigned() && $equipment->activeAssignment)
            @can('assignments.return')
            <a href="{{ route('assignments.return', $equipment->activeAssignment) }}" class="btn btn-warning">
                <i class="bi bi-box-arrow-in-left me-1"></i>Devolver
            </a>
            @endcan
            @can('assignments.transfer')
            <a href="{{ route('assignments.transfer', $equipment->activeAssignment) }}" class="btn btn-info">
                <i class="bi bi-arrow-left-right me-1"></i>Transferir
            </a>
            @endcan
        @endif
        @can('equipment.edit')
        <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        @endcan
        @can('equipment.destroy')
        <form action="{{ route('equipment.destroy', $equipment) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este equipo?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash me-1"></i>Eliminar</button>
        </form>
        @endcan
        <a href="{{ route('history.by-equipment', $equipment) }}" class="btn btn-outline-secondary">
            <i class="bi bi-clock-history me-1"></i>Historial
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Main Info -->
    <div class="col-lg-8">
        <!-- Equipment Details -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-laptop me-2"></i>Información del Equipo</span>
                <div class="d-flex gap-2">
                    <span class="badge bg-{{ $equipment->availability_badge_color }}">
                        {{ $equipment->availability_status_name }}
                    </span>
                    <span class="badge bg-{{ $equipment->condition_badge_color }}">
                        {{ $equipment->physical_condition_name }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Identificación</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted" width="40%">Código Interno:</td>
                                <td><strong>{{ $equipment->internal_code }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Etiqueta Activo:</td>
                                <td>{{ $equipment->asset_tag ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Categoría:</td>
                                <td>
                                    <span class="badge" style="background-color: {{ $equipment->category->color ?? '#6c757d' }}">
                                        {{ $equipment->category->name }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Marca:</td>
                                <td>{{ $equipment->brand?->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Modelo:</td>
                                <td>{{ $equipment->model ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">No. Serie:</td>
                                <td><code>{{ $equipment->serial_number ?? '-' }}</code></td>
                            </tr>
                            <tr>
                                <td class="text-muted">No. Parte:</td>
                                <td>{{ $equipment->part_number ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Especificaciones Técnicas</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted" width="40%">Procesador:</td>
                                <td>{{ $equipment->processor ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">RAM:</td>
                                <td>{{ $equipment->ram ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Almacenamiento:</td>
                                <td>{{ $equipment->storage ?? '-' }} {{ $equipment->storage_type }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tarjeta Gráfica:</td>
                                <td>{{ $equipment->graphics_card ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Pantalla:</td>
                                <td>{{ $equipment->screen_size ?? '-' }} {{ $equipment->screen_resolution }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Sistema Operativo:</td>
                                <td>{{ $equipment->operating_system ?? '-' }} {{ $equipment->os_version }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($equipment->hostname || $equipment->ip_address || $equipment->mac_address)
                <hr>
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Información de Red</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted" width="40%">Hostname:</td>
                                <td><code>{{ $equipment->hostname ?? '-' }}</code></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Dirección IP:</td>
                                <td><code>{{ $equipment->ip_address ?? '-' }}</code></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Dirección MAC:</td>
                                <td><code>{{ $equipment->mac_address ?? '-' }}</code></td>
                            </tr>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Purchase & Warranty -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-receipt me-2"></i>Compra y Garantía
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Información de Compra</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted" width="40%">Proveedor:</td>
                                <td>{{ $equipment->supplier?->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Orden de Compra:</td>
                                <td>{{ $equipment->purchase_order ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">No. Factura:</td>
                                <td>{{ $equipment->invoice_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Fecha de Compra:</td>
                                <td>{{ $equipment->purchase_date?->format('d/m/Y') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Precio:</td>
                                <td>
                                    @if($equipment->purchase_price)
                                        <strong>${{ number_format($equipment->purchase_price, 2) }} {{ $equipment->currency }}</strong>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Garantía</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted" width="40%">Tipo:</td>
                                <td>{{ $equipment->warranty_type ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Inicio:</td>
                                <td>{{ $equipment->warranty_start_date?->format('d/m/Y') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Vencimiento:</td>
                                <td>
                                    @if($equipment->warranty_end_date)
                                        {{ $equipment->warranty_end_date->format('d/m/Y') }}
                                        @if($equipment->hasActiveWarranty())
                                            <span class="badge bg-success ms-2">Vigente</span>
                                            <br><small class="text-muted">{{ $equipment->warranty_days_remaining }} días restantes</small>
                                        @else
                                            <span class="badge bg-danger ms-2">Vencida</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignment History -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people me-2"></i>Historial de Asignaciones</span>
                <span class="badge bg-secondary">{{ $assignments->count() }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Empleado</th>
                                <th>Fecha Asignación</th>
                                <th>Fecha Devolución</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignments as $assignment)
                            <tr>
                                <td>
                                    <a href="{{ route('assignments.show', $assignment) }}" class="text-decoration-none">
                                        {{ $assignment->assignment_number }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('employees.show', $assignment->employee) }}" class="text-decoration-none">
                                        {{ $assignment->employee->full_name }}
                                    </a>
                                </td>
                                <td>{{ $assignment->assignment_date->format('d/m/Y H:i') }}</td>
                                <td>{{ $assignment->actual_return_date?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $assignment->status_badge_color }}">
                                        {{ $assignment->status_name }}
                                    </span>
                                </td>
                                <td>
                                    @if($assignment->hasCustodyLetter() || $assignment->isActive())
                                    <a href="{{ route('assignments.pdf', $assignment) }}" class="btn btn-sm btn-outline-danger" title="Descargar PDF">
                                        <i class="bi bi-file-pdf"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Este equipo no tiene historial de asignaciones
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent History Timeline -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>Línea de Tiempo del Equipo</span>
                <a href="{{ route('history.by-equipment', $equipment) }}" class="btn btn-sm btn-outline-primary">Ver todo</a>
            </div>
            <div class="card-body">
                @if($history->count() > 0)
                <div class="timeline">
                    @foreach($history as $h)
                    <div class="timeline-item">
                        <span class="timeline-dot" style="background: var(--bs-{{ $h->movement_color }}, #64748b);">
                            <i class="bi {{ $h->movement_icon }}"></i>
                        </span>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-start">
                                <strong>{{ $h->title }}</strong>
                                <span class="timeline-date">{{ $h->performed_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($h->description)
                            <div class="small text-muted mt-1">{{ $h->description }}</div>
                            @endif
                            @if($h->newEmployee)
                            <div class="small mt-1"><i class="bi bi-person me-1"></i>{{ $h->newEmployee->full_name }}</div>
                            @endif
                            <div class="small text-muted mt-1">Por: {{ $h->performedBy?->name ?? 'Sistema' }} · {{ $h->performed_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted text-center mb-0 py-4">No hay movimientos registrados</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Current Assignment -->
        @if($equipment->currentEmployee)
        <div class="card mb-4 border-primary">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-person-check me-2"></i>Asignado Actualmente
            </div>
            <div class="card-body text-center">
                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;">
                    <i class="bi bi-person-fill fs-2 text-primary"></i>
                </div>
                <h5 class="mb-1">
                    <a href="{{ route('employees.show', $equipment->currentEmployee) }}" class="text-decoration-none">
                        {{ $equipment->currentEmployee->full_name }}
                    </a>
                </h5>
                <p class="text-muted mb-2">{{ $equipment->currentEmployee->position->name }}</p>
                <p class="text-muted small mb-3">{{ $equipment->currentEmployee->department->name }}</p>
                
                @if($equipment->activeAssignment)
                <div class="border-top pt-3">
                    <small class="text-muted">Asignado desde:</small>
                    <div class="fw-medium">{{ $equipment->activeAssignment->assignment_date->format('d/m/Y') }}</div>
                    <small class="text-muted">{{ $equipment->activeAssignment->duration }}</small>
                </div>
                @endif
            </div>
            @if($equipment->activeAssignment)
            <div class="card-footer bg-transparent">
                <div class="d-grid gap-2">
                    <a href="{{ route('assignments.pdf', $equipment->activeAssignment) }}" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-file-pdf me-1"></i>Descargar Responsiva
                    </a>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Location -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-geo-alt me-2"></i>Ubicación
            </div>
            <div class="card-body">
                @if($equipment->location)
                    <strong>{{ $equipment->location->name }}</strong>
                    <br><small class="text-muted">{{ $equipment->location->full_path }}</small>
                    @if($equipment->specific_location)
                        <br><small class="text-muted">{{ $equipment->specific_location }}</small>
                    @endif
                @else
                    <span class="text-muted">Sin ubicación asignada</span>
                @endif
            </div>
        </div>

        <!-- QR Code -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-qr-code me-2"></i>Código QR
            </div>
            <div class="card-body text-center">
                <div class="d-inline-block p-2 bg-white rounded border">
                    <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code {{ $equipment->internal_code }}" style="width: 180px; height: 180px;">
                </div>
                <p class="text-muted small mt-2 mb-0">Escanea para abrir la ficha del equipo</p>
                <strong class="small">{{ $equipment->internal_code }}</strong>
                <div class="mt-2">
                    <a href="data:image/svg+xml;base64,{{ $qrCode }}" download="qr_{{ $equipment->internal_code }}.svg" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-download me-1"></i>Descargar QR
                    </a>
                </div>
            </div>
        </div>

        <!-- Accessories -->
        @if($equipment->has_charger || $equipment->has_mouse || $equipment->has_keyboard || $equipment->has_power_strip || $equipment->has_bag_case || $equipment->adapters || $equipment->other_accessories || $equipment->description || $equipment->observations)
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-box-seam me-2"></i>Accesorios y Notas
            </div>
            <div class="card-body">
                @if($equipment->has_charger || $equipment->has_mouse || $equipment->has_keyboard || $equipment->has_power_strip || $equipment->has_bag_case || $equipment->adapters || $equipment->other_accessories)
                <div class="mb-3">
                    <small class="text-muted d-block mb-2">Accesorios Entregados:</small>
                    <div class="row g-2">
                        @if($equipment->has_charger)
                        <div class="col-auto">
                            <span class="badge badge-soft-primary"><i class="bi bi-plug me-1"></i>Cargador{{ $equipment->charger_details ? ': ' . $equipment->charger_details : '' }}</span>
                        </div>
                        @endif
                        @if($equipment->has_mouse)
                        <div class="col-auto">
                            <span class="badge badge-soft-primary"><i class="bi bi-mouse me-1"></i>Mouse{{ $equipment->mouse_details ? ': ' . $equipment->mouse_details : '' }}</span>
                        </div>
                        @endif
                        @if($equipment->has_keyboard)
                        <div class="col-auto">
                            <span class="badge badge-soft-primary"><i class="bi bi-keyboard me-1"></i>Teclado</span>
                        </div>
                        @endif
                        @if($equipment->has_power_strip)
                        <div class="col-auto">
                            <span class="badge badge-soft-primary"><i class="bi bi-outlet me-1"></i>Multicontacto</span>
                        </div>
                        @endif
                        @if($equipment->has_bag_case)
                        <div class="col-auto">
                            <span class="badge badge-soft-primary"><i class="bi bi-bag me-1"></i>Funda / Mochila</span>
                        </div>
                        @endif
                        @if($equipment->adapters)
                        <div class="col-auto">
                            <span class="badge badge-soft-info"><i class="bi bi-usb me-1"></i>Adaptadores: {{ $equipment->adapters }}</span>
                        </div>
                        @endif
                        @if($equipment->other_accessories)
                        <div class="col-auto">
                            <span class="badge badge-soft-secondary"><i class="bi bi-box me-1"></i>Otros: {{ $equipment->other_accessories }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                @if($equipment->description)
                <div class="mb-3">
                    <small class="text-muted d-block">Descripción:</small>
                    {{ $equipment->description }}
                </div>
                @endif
                @if($equipment->observations)
                <div>
                    <small class="text-muted d-block">Observaciones:</small>
                    {{ $equipment->observations }}
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Metadata -->
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle me-2"></i>Información del Registro
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Creado:</td>
                        <td>{{ $equipment->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Por:</td>
                        <td>{{ $equipment->creator?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Actualizado:</td>
                        <td>{{ $equipment->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Por:</td>
                        <td>{{ $equipment->updater?->name ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
