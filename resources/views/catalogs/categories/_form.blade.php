<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-collection me-2"></i>Información de la Categoría</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Código <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $category->code ?? '') }}" required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Categoría Padre</label>
                        <select name="parent_id" class="form-select">
                            <option value="">Ninguna</option>
                            @foreach($parents as $p)
                                <option value="{{ $p->id }}" {{ old('parent_id', $category->parent_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Icono (Bootstrap)</label>
                        <input type="text" name="icon" class="form-control" value="{{ old('icon', $category->icon ?? '') }}" placeholder="bi-laptop">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Color</label>
                        <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', $category->color ?? '#0d6efd') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Orden</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="requires_serial" value="1" {{ old('requires_serial', $category->requires_serial ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Requiere Número de Serie</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4"><div class="card-body">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label">Activo</label>
            </div>
        </div></div>
        <div class="card"><div class="card-body">
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-lg me-2"></i>Guardar</button>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </div></div>
    </div>
</div>
