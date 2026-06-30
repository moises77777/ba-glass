<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-briefcase me-2"></i>Información del Puesto</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Código <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $position->code ?? '') }}" required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $position->name ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Departamento <span class="text-danger">*</span></label>
                        <select name="department_id" class="form-select" required>
                            <option value="">Seleccionar...</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}" {{ old('department_id', $position->department_id ?? '') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nivel</label>
                        <input type="text" name="level" class="form-control" value="{{ old('level', $position->level ?? '') }}" placeholder="Ej: Junior, Senior, Manager">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $position->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4"><div class="card-body">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $position->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label">Activo</label>
            </div>
        </div></div>
        <div class="card"><div class="card-body">
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-lg me-2"></i>Guardar</button>
                <a href="{{ route('positions.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </div></div>
    </div>
</div>
