@php
    $nivelLabel = ['vencido' => 'Vencido', 'urgente' => 'Urgente', 'proximo' => 'Próximo', 'ok' => 'Al día'];
@endphp
<x-app-layout>
    <div class="view-header">
        <div>
            <h2>Plan de mantenciones preventivas</h2>
            <p class="sub">Cambio de aceite, frenos, neumáticos y otros ítems según kilometraje o tiempo.</p>
        </div>
        @can('mantenciones.editar')
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMantencionCreate">
                + Nuevo ítem
            </button>
        @endcan
    </div>

    <form method="GET" action="{{ route('mantenciones.index') }}" class="d-flex gap-2 mb-3">
        <select name="vehiculo_id" class="form-select" style="max-width:280px" onchange="this.form.submit()">
            <option value="">Todos los vehículos</option>
            @foreach ($vehiculos as $v)
                <option value="{{ $v->id }}" @selected((string) $vehiculoFiltro === (string) $v->id)>{{ $v->ppu }} — {{ $v->marca }} {{ $v->modelo }}</option>
            @endforeach
        </select>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white">
            <thead>
                <tr><th>PPU</th><th>Mantención</th><th>Último → Objetivo</th><th>Restante</th><th>Fecha objetivo</th><th>Estado</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($mantenciones as $m)
                    <tr>
                        <td><x-ppu-plate :ppu="$m->vehiculo->ppu" /></td>
                        <td>{{ $m->tipoMantencion->nombre }}</td>
                        <td class="font-mono">
                            {{ $m->km_ultima !== null ? number_format($m->km_ultima, 0, ',', '.') . ' km' : '—' }}
                            →
                            {{ $m->km_objetivo !== null ? number_format($m->km_objetivo, 0, ',', '.') . ' km' : '—' }}
                        </td>
                        <td class="font-mono">
                            @if ($m->km_restante === null) &mdash;
                            @elseif ($m->km_restante < 0) {{ abs($m->km_restante) }} km excedido
                            @else {{ $m->km_restante }} km
                            @endif
                        </td>
                        <td class="font-mono">{{ $m->fecha_objetivo?->format('d-m-Y') ?? '—' }}</td>
                        <td><span class="badge nivel-{{ $m->nivel }}">{{ $nivelLabel[$m->nivel] }}</span></td>
                        <td class="text-end text-nowrap">
                            @can('mantenciones.editar')
                                <button type="button" class="btn btn-sm btn-warning fw-semibold" title="Registrar realizada" data-bs-toggle="modal" data-bs-target="#modalRegistro{{ $m->id }}">Registrar</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" title="Editar plan" data-bs-toggle="modal" data-bs-target="#modalMantencionEdit{{ $m->id }}">✎</button>
                            @endcan
                            @can('administrador')
                                <button type="submit" form="deleteMantencion{{ $m->id }}" class="btn btn-sm btn-outline-danger" title="Eliminar plan" onclick="return confirm('¿Eliminar este ítem del plan de mantención?')">✕</button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7"><div class="empty-state">Sin ítems de mantención configurados.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @can('administrador')
        @foreach ($mantenciones as $m)
            <form id="deleteMantencion{{ $m->id }}" method="POST" action="{{ route('mantenciones.destroy', $m) }}" class="d-none">
                @csrf @method('DELETE')
            </form>
        @endforeach
    @endcan

    @can('mantenciones.editar')
        @foreach ($mantenciones as $m)
            @include('historial._modal_registro', [
                'modalId' => 'modalRegistro' . $m->id,
                'vehiculos' => $vehiculos,
                'tipos' => $tipos,
                'presetVehiculoId' => $m->vehiculo_id,
                'presetTipoId' => $m->tipo_mantencion_id,
            ])
        @endforeach

        {{-- Modal: nuevo ítem --}}
        <div class="modal fade" id="modalMantencionCreate" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('mantenciones.store') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo ítem de mantención</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('mantenciones._campos_nuevo')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar plan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modales: editar plan (vehículo y tipo quedan fijos una vez creado el ítem) --}}
    @foreach ($mantenciones as $m)
        <div class="modal fade" id="modalMantencionEdit{{ $m->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('mantenciones.update', $m) }}" class="modal-content">
                    @csrf @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Editar plan de mantención</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Vehículo</label>
                                <input type="text" class="form-control" value="{{ $m->vehiculo->ppu }} — {{ $m->vehiculo->marca }} {{ $m->vehiculo->modelo }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tipo de mantención</label>
                                <input type="text" class="form-control" value="{{ $m->tipoMantencion->nombre }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Intervalo (km)</label>
                                <input type="number" min="0" name="intervalo_km" class="form-control" value="{{ $m->intervalo_km }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Intervalo (meses)</label>
                                <input type="number" min="0" name="intervalo_meses" class="form-control" value="{{ $m->intervalo_meses }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kilometraje de última mantención</label>
                                <input type="number" min="0" name="km_ultima" class="form-control" value="{{ $m->km_ultima }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de última mantención</label>
                                <input type="date" name="fecha_ultima" class="form-control" value="{{ optional($m->fecha_ultima)->toDateString() }}">
                            </div>
                            <div class="col-12 form-check">
                                <input type="checkbox" name="activo" value="1" class="form-check-input" id="activo{{ $m->id }}" @checked($m->activo)>
                                <label class="form-check-label" for="activo{{ $m->id }}">Plan activo</label>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Observaciones</label>
                                <textarea name="observaciones" rows="2" class="form-control">{{ $m->observaciones }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar plan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

        <script>
            document.querySelectorAll('.js-tipo-mantencion').forEach(function (sel) {
                sel.addEventListener('change', function () {
                    var opt = sel.selectedOptions[0];
                    var modal = sel.closest('.modal-content');
                    modal.querySelector('.js-intervalo-km').value = opt.dataset.km || '';
                    modal.querySelector('.js-intervalo-meses').value = opt.dataset.meses || '';
                });
            });
        </script>
    @endcan
</x-app-layout>
