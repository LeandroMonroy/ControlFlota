@php $vehiculo = $vehiculo ?? null; @endphp
<div class="row g-3">
    <div class="col-md-3">
        <label class="form-label">PPU (patente)</label>
        <input type="text" name="ppu" maxlength="10" required class="form-control text-uppercase" value="{{ old('ppu', $vehiculo->ppu ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Tipo</label>
        <select name="tipo" required class="form-select">
            @foreach ($tipos as $t)
                <option @selected(old('tipo', $vehiculo->tipo ?? 'Camioneta') === $t)>{{ $t }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Tipo de combustible</label>
        <select name="tipo_combustible" class="form-select">
            <option value="">Sin especificar</option>
            @foreach ($combustibles as $c)
                <option @selected(old('tipo_combustible', $vehiculo->tipo_combustible ?? '') === $c)>{{ $c }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Año</label>
        <input type="number" name="anio" min="1980" max="2035" required class="form-control" value="{{ old('anio', $vehiculo->anio ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Marca</label>
        <input type="text" name="marca" required class="form-control" value="{{ old('marca', $vehiculo->marca ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Modelo</label>
        <input type="text" name="modelo" required class="form-control" value="{{ old('modelo', $vehiculo->modelo ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Color</label>
        <input type="text" name="color" class="form-control" value="{{ old('color', $vehiculo->color ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">N° de motor</label>
        <input type="text" name="numero_motor" class="form-control" value="{{ old('numero_motor', $vehiculo->numero_motor ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">N° de chasis</label>
        <input type="text" name="numero_chasis" class="form-control" value="{{ old('numero_chasis', $vehiculo->numero_chasis ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Kilometraje actual</label>
        <input type="number" name="kilometraje_actual" min="0" class="form-control" value="{{ old('kilometraje_actual', $vehiculo->kilometraje_actual ?? 0) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select">
            @foreach ($estados as $e)
                <option @selected(old('estado', $vehiculo->estado ?? 'Operativo') === $e)>{{ $e }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Área asignada</label>
        <input type="text" name="area_asignada" class="form-control" placeholder="Ej: SKYVAULT, Central de Cámaras…" value="{{ old('area_asignada', $vehiculo->area_asignada ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Responsable</label>
        <input type="text" name="responsable" class="form-control" value="{{ old('responsable', $vehiculo->responsable ?? '') }}">
    </div>
    <div class="col-12">
        <label class="form-label">Observaciones</label>
        <textarea name="observaciones" rows="2" class="form-control">{{ old('observaciones', $vehiculo->observaciones ?? '') }}</textarea>
    </div>
</div>
