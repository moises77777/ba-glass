<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-tag me-2"></i>Información de la Marca</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $brand->name ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sitio Web</label>
                        <input type="url" name="website" class="form-control" value="{{ old('website', $brand->website ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono Soporte</label>
                        <input type="text" name="support_phone" class="form-control" value="{{ old('support_phone', $brand->support_phone ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Soporte</label>
                        <input type="email" name="support_email" class="form-control" value="{{ old('support_email', $brand->support_email ?? '') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $brand->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4"><div class="card-body">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $brand->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label">Activo</label>
            </div>
        </div></div>
        <div class="card"><div class="card-body">
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-lg me-2"></i>Guardar</button>
                <a href="{{ route('brands.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </div></div>
    </div>
</div>
