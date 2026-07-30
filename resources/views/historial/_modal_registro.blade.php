@php
    $modalId = $modalId ?? 'modalRegistro';
    $presetVehiculoId = $presetVehiculoId ?? null;
    $presetTipoId = $presetTipoId ?? null;
@endphp
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('historial.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Registrar mantención realizada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Vehículo</label>
                        <select name="vehiculo_id" class="form-select" required>
                            <option value="">Seleccionar…</option>
                            @foreach ($vehiculos as $v)
                                <option value="{{ $v->id }}" @selected($presetVehiculoId == $v->id)>{{ $v->ppu }} — {{ $v->marca }} {{ $v->modelo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tipo de mantención</label>
                        <select name="tipo_mantencion_id" class="form-select" required>
                            <option value="">Seleccionar…</option>
                            @foreach ($tipos as $t)
                                <option value="{{ $t->id }}" @selected($presetTipoId == $t->id)>{{ $t->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha realizada</label>
                        <input type="date" name="fecha_realizada" class="form-control" value="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kilometraje al momento</label>
                        <input type="number" min="0" name="km_realizada" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Costo</label>
                        <input type="number" min="0" step="1" name="costo" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Taller / lugar</label>
                        <input type="text" name="taller" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-12 form-check">
                        <input type="checkbox" name="crear_plan" value="1" class="form-check-input" id="crearPlan-{{ $modalId }}" checked>
                        <label class="form-check-label" for="crearPlan-{{ $modalId }}">
                            Crear el ítem en el plan si aún no existe para este vehículo
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-warning fw-semibold">Registrar mantención</button>
            </div>
        </form>
    </div>
</div>
