@php
    $estadoBadge = [
        'Operativo' => 'success',
        'En mantención' => 'warning',
        'Fuera de servicio' => 'danger',
        'Dado de baja' => 'secondary',
    ];
    $nivelLabel = ['vencido' => 'Vencido', 'urgente' => 'Urgente', 'proximo' => 'Próximo', 'ok' => 'Al día'];
@endphp
<x-app-layout>
    <div class="view-header">
        <div>
            <h2 class="d-flex align-items-center gap-2"><x-ppu-plate :ppu="$vehiculo->ppu" size="lg" /> {{ $vehiculo->marca }} {{ $vehiculo->modelo }}</h2>
            <p class="sub">Ficha técnica completa y documentación asociada.</p>
        </div>
        <a href="{{ route('vehiculos.index') }}" class="btn btn-outline-secondary">← Volver</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <div class="text-muted small">PPU</div>
                    <div><x-ppu-plate :ppu="$vehiculo->ppu" /></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-muted small">Tipo</div>
                    <div>{{ $vehiculo->tipo }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-muted small">Combustible</div>
                    <div>{{ $vehiculo->tipo_combustible ?: '—' }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-muted small">Estado</div>
                    <div><span class="badge bg-{{ $estadoBadge[$vehiculo->estado] ?? 'secondary' }}">{{ $vehiculo->estado }}</span></div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="text-muted small">Marca / Modelo</div>
                    <div>{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-muted small">Año</div>
                    <div class="font-mono">{{ $vehiculo->anio }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-muted small">Color</div>
                    <div>{{ $vehiculo->color ?: '—' }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-muted small">Kilometraje actual</div>
                    <div class="font-mono">
                        {{ number_format($vehiculo->kilometraje_actual, 0, ',', '.') }} km
                        @if ($vehiculo->fecha_kilometraje)
                            <span class="text-muted small">({{ $vehiculo->fecha_kilometraje->format('d-m-Y') }})</span>
                        @endif
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="text-muted small">N° motor</div>
                    <div class="font-mono">{{ $vehiculo->numero_motor ?: '—' }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-muted small">N° chasis</div>
                    <div class="font-mono">{{ $vehiculo->numero_chasis ?: '—' }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-muted small">Área asignada</div>
                    <div>{{ $vehiculo->area_asignada ?: '—' }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-muted small">Responsable</div>
                    <div>{{ $vehiculo->responsable ?: '—' }}</div>
                </div>

                @if ($vehiculo->observaciones)
                    <div class="col-12">
                        <div class="text-muted small">Observaciones</div>
                        <div>{{ $vehiculo->observaciones }}</div>
                    </div>
                @endif

                @if ($vehiculo->foto1_url || $vehiculo->foto2_url)
                    <div class="col-12">
                        <div class="text-muted small mb-1">Fotografías</div>
                        <div class="d-flex gap-2">
                            @foreach ([$vehiculo->foto1_url, $vehiculo->foto2_url] as $foto)
                                @if ($foto)
                                    <a href="{{ $foto }}" target="_blank" rel="noopener">
                                        <img src="{{ $foto }}" alt="Fotografía de {{ $vehiculo->ppu }}" class="img-thumbnail" style="width:140px;height:140px;object-fit:cover">
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <h6 class="text-uppercase text-secondary small fw-semibold mb-2">
        Documentación <span class="badge bg-secondary">{{ $vehiculo->documentos->count() }}</span>
    </h6>
    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white">
            <thead>
                <tr>
                    <th>Documento</th><th>N° documento</th><th>Entidad emisora</th>
                    <th>Emisión</th><th>Vencimiento</th><th>Estado</th><th>Costo</th>
                    <th>Archivo</th><th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($vehiculo->documentos as $d)
                    <tr>
                        <td>{{ $d->tipo_documento }}</td>
                        <td class="font-mono">{{ $d->numero_documento ?: '—' }}</td>
                        <td>{{ $d->entidad_emisora ?: '—' }}</td>
                        <td class="font-mono">{{ $d->fecha_emision?->format('d-m-Y') ?: '—' }}</td>
                        <td class="font-mono">{{ $d->fecha_vencimiento->format('d-m-Y') }}</td>
                        <td>
                            <span class="badge nivel-{{ $d->nivel }}">
                                {{ $nivelLabel[$d->nivel] }}{{ $d->dias_restantes < 0 ? ' · ' . abs($d->dias_restantes) . 'd' : '' }}
                            </span>
                        </td>
                        <td class="font-mono">{{ $d->costo !== null ? '$' . number_format($d->costo, 0, ',', '.') : '—' }}</td>
                        <td>
                            @if ($d->archivo_link)
                                <a href="{{ $d->archivo_link }}" target="_blank" rel="noopener">Ver</a>
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $d->observaciones ?: '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="9"><div class="empty-state">Sin documentos registrados para este vehículo.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
