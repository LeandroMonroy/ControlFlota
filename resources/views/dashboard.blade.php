<x-app-layout>
    <div class="view-header">
        <div>
            <h2>Panel general</h2>
            <p class="sub">Estado consolidado de la flota, documentación y mantenciones preventivas.</p>
        </div>
        @can('mantenciones.editar')
            <button type="button" class="btn btn-warning fw-semibold" data-bs-toggle="modal" data-bs-target="#modalRegistro">
                + Registrar mantención realizada
            </button>
        @endcan
    </div>

    <div class="row row-cols-2 row-cols-md-4 g-3 mb-4">
        <div class="col">
            <div class="kpi-card">
                <div class="val">{{ $resumen['total_vehiculos'] }}</div>
                <div class="lbl">Vehículos totales</div>
            </div>
        </div>
        <div class="col">
            <div class="kpi-card">
                <div class="val">{{ $resumen['operativos'] }}</div>
                <div class="lbl">Operativos</div>
            </div>
        </div>
        <div class="col">
            <div class="kpi-card">
                <div class="val">{{ $resumen['en_mantencion'] }}</div>
                <div class="lbl">En mantención</div>
            </div>
        </div>
        <div class="col">
            <div class="kpi-card">
                <div class="val">{{ $resumen['fuera_servicio'] }}</div>
                <div class="lbl">Fuera de servicio</div>
            </div>
        </div>
    </div>

    <h6 class="text-uppercase text-secondary small fw-semibold mb-2">
        Documentación por vencer <span class="badge bg-secondary">{{ $documentos->count() }}</span>
    </h6>
    @if ($documentos->isEmpty())
        <div class="empty-state mb-4">Sin documentos por vencer en los próximos 30 días.</div>
    @else
        <div class="mb-4">
            @foreach ($documentos as $d)
                <div class="alert-row">
                    <span class="led {{ $d->nivel }}"></span>
                    <div class="alert-main">
                        <x-ppu-plate :ppu="$d->vehiculo->ppu" /> · {{ $d->tipo_documento }}
                        <div class="desc">{{ $d->vehiculo->marca }} {{ $d->vehiculo->modelo }} · vence {{ $d->fecha_vencimiento->format('d-m-Y') }}</div>
                    </div>
                    <span class="badge nivel-{{ $d->nivel }}">
                        {{ $d->dias_restantes < 0 ? abs($d->dias_restantes) . ' días vencido' : $d->dias_restantes . ' días' }}
                    </span>
                    <a href="{{ route('documentos.index') }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                </div>
            @endforeach
        </div>
    @endif

    <h6 class="text-uppercase text-secondary small fw-semibold mb-2">
        Mantenciones próximas o vencidas <span class="badge bg-secondary">{{ $mantenciones->count() }}</span>
    </h6>
    @if ($mantenciones->isEmpty())
        <div class="empty-state">Sin mantenciones próximas o vencidas.</div>
    @else
        <div>
            @foreach ($mantenciones as $m)
                <div class="alert-row">
                    <span class="led {{ $m->nivel }}"></span>
                    <div class="alert-main">
                        <x-ppu-plate :ppu="$m->vehiculo->ppu" /> · {{ $m->tipoMantencion->nombre }}
                        <div class="desc">{{ $m->vehiculo->marca }} {{ $m->vehiculo->modelo }} · actual {{ number_format($m->vehiculo->kilometraje_actual, 0, ',', '.') }} km</div>
                    </div>
                    <span class="badge nivel-{{ $m->nivel }}">
                        @if ($m->km_restante === null) &mdash;
                        @elseif ($m->km_restante < 0) {{ abs($m->km_restante) }} km excedidos
                        @else {{ $m->km_restante }} km restantes
                        @endif
                    </span>
                    <a href="{{ route('mantenciones.index') }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                </div>
            @endforeach
        </div>
    @endif

    @can('mantenciones.editar')
        @include('historial._modal_registro', ['vehiculos' => $vehiculos, 'tipos' => $tipos])
    @endcan
</x-app-layout>
