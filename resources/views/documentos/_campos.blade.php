@php
    $documento = $documento ?? null;
    $presetVehiculoId = $presetVehiculoId ?? null;
@endphp
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Vehículo</label>
        <select name="vehiculo_id" required class="form-select">
            <option value="">Seleccionar…</option>
            @foreach ($vehiculos as $v)
                <option value="{{ $v->id }}" @selected((int) old('vehiculo_id', $documento->vehiculo_id ?? $presetVehiculoId ?? 0) === $v->id)>{{ $v->ppu }} — {{ $v->marca }} {{ $v->modelo }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Tipo de documento</label>
        <select name="tipo_documento" required class="form-select">
            @foreach ($tiposDoc as $t)
                <option @selected(old('tipo_documento', $documento->tipo_documento ?? '') === $t)>{{ $t }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">N° de documento</label>
        <input type="text" name="numero_documento" class="form-control" value="{{ old('numero_documento', $documento->numero_documento ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Entidad emisora</label>
        <input type="text" name="entidad_emisora" class="form-control" value="{{ old('entidad_emisora', $documento->entidad_emisora ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Costo</label>
        <input type="number" name="costo" min="0" step="1" class="form-control" value="{{ old('costo', $documento->costo ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Fecha de emisión</label>
        <input type="date" name="fecha_emision" class="form-control" value="{{ old('fecha_emision', optional($documento?->fecha_emision)->toDateString()) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Fecha de vencimiento</label>
        <input type="date" name="fecha_vencimiento" required class="form-control" value="{{ old('fecha_vencimiento', optional($documento?->fecha_vencimiento)->toDateString()) }}">
    </div>
    <div class="col-12">
        <label class="form-label">Observaciones</label>
        <textarea name="observaciones" rows="2" class="form-control">{{ old('observaciones', $documento->observaciones ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Documento (PDF)</label>
        @if ($documento?->archivo_link)
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ $documento->archivo_link }}" target="_blank" rel="noopener">Ver archivo actual</a>
                <div class="form-check">
                    <input type="checkbox" name="eliminar_archivo" value="1" class="form-check-input" id="eliminarArchivo{{ $documento->id }}">
                    <label class="form-check-label small" for="eliminarArchivo{{ $documento->id }}">Eliminar archivo actual</label>
                </div>
            </div>
        @endif
        <input type="file" name="archivo" accept="application/pdf" class="form-control">
    </div>
</div>
