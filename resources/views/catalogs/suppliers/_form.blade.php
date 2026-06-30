<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-truck me-2"></i>Información del Proveedor</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $supplier->name ?? '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">RFC</label>
                        <input type="text" name="rfc" class="form-control" value="{{ old('rfc', $supplier->rfc ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contacto</label>
                        <input type="text" name="contact_name" class="form-control" value="{{ old('contact_name', $supplier->contact_name ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $supplier->email ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $supplier->phone ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sitio Web</label>
                        <input type="url" name="website" class="form-control" value="{{ old('website', $supplier->website ?? '') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Dirección</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address', $supplier->address ?? '') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notas</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes', $supplier->notes ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4"><div class="card-body">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $supplier->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label">Activo</label>
            </div>
        </div></div>
        <div class="card"><div class="card-body">
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-lg me-2"></i>Guardar</button>
                <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </div></div>
    </div>
</div>
