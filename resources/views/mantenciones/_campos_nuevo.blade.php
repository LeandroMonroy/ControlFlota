@php $presetVehiculoId = $presetVehiculoId ?? null; @endphp
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Vehículo</label>
        <select name="vehiculo_id" required class="form-select">
            <option value="">Seleccionar…</option>
            @foreach ($vehiculos as $v)
                <option value="{{ $v->id }}" @selected($presetVehiculoId == $v->id)>{{ $v->ppu }} — {{ $v->marca }} {{ $v->modelo }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Tipo de mantención <span class="text-secondary small">— autocompleta el intervalo</span></label>
        <select name="tipo_mantencion_id" required class="form-select js-tipo-mantencion">
            <option value="">Seleccionar…</option>
            @foreach ($tipos as $t)
                <option value="{{ $t->id }}" data-km="{{ $t->intervalo_km_defecto }}" data-meses="{{ $t->intervalo_meses_defecto }}">{{ $t->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Intervalo (km)</label>
        <input type="number" min="0" name="intervalo_km" class="form-control js-intervalo-km">
    </div>
    <div class="col-md-6">
        <label class="form-label">Intervalo (meses)</label>
        <input type="number" min="0" name="intervalo_meses" class="form-control js-intervalo-meses">
    </div>
    <div class="col-md-6">
        <label class="form-label">Kilometraje de última mantención</label>
        <input type="number" min="0" name="km_ultima" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Fecha de última mantención</label>
        <input type="date" name="fecha_ultima" class="form-control" value="{{ now()->toDateString() }}">
    </div>
    <div class="col-12">
        <label class="form-label">Observaciones</label>
        <textarea name="observaciones" rows="2" class="form-control"></textarea>
    </div>
</div>
