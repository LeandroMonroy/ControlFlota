@php
    $tiposDoc = [
        'Permiso de Circulación', 'Revisión Técnica', 'Seguro Obligatorio (SOAP)',
        'Seguro Adicional', 'Certificado de Emisiones', 'Padrón / Inscripción', 'Otro',
    ];
    $nivelLabel = ['vencido' => 'Vencido', 'urgente' => 'Urgente', 'proximo' => 'Próximo', 'ok' => 'Al día'];
@endphp
<x-app-layout>
    <div class="view-header">
        <div>
            <h2>Documentación y vencimientos</h2>
            <p class="sub">Permiso de circulación, revisión técnica, seguros y otros documentos.</p>
        </div>
        @can('documentos.editar')
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDocumentoCreate">
                + Nuevo documento
            </button>
        @endcan
    </div>

    <form method="GET" action="{{ route('documentos.index') }}" class="d-flex gap-2 mb-3">
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
                <tr><th>PPU</th><th>Documento</th><th>N° documento</th><th>Vence</th><th>Estado</th><th>Costo</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($documentos as $d)
                    <tr>
                        <td><span class="ppu-badge">{{ $d->vehiculo->ppu }}</span></td>
                        <td>{{ $d->tipo_documento }}</td>
                        <td class="font-mono">{{ $d->numero_documento ?: '—' }}</td>
                        <td class="font-mono">{{ $d->fecha_vencimiento->format('d-m-Y') }}</td>
                        <td>
                            <span class="badge nivel-{{ $d->nivel }}">
                                {{ $nivelLabel[$d->nivel] }}{{ $d->dias_restantes < 0 ? ' · ' . abs($d->dias_restantes) . 'd' : '' }}
                            </span>
                        </td>
                        <td class="font-mono">{{ $d->costo !== null ? '$' . number_format($d->costo, 0, ',', '.') : '—' }}</td>
                        <td class="text-end">
                            @can('documentos.editar')
                                <button type="button" class="btn btn-sm btn-outline-secondary" title="Editar" data-bs-toggle="modal" data-bs-target="#modalDocumentoEdit{{ $d->id }}">✎</button>
                            @endcan
                            @can('administrador')
                                <button type="submit" form="deleteDocumento{{ $d->id }}" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Eliminar este documento?')">✕</button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7"><div class="empty-state">Sin documentos registrados.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @can('administrador')
        @foreach ($documentos as $d)
            <form id="deleteDocumento{{ $d->id }}" method="POST" action="{{ route('documentos.destroy', $d) }}" class="d-none">
                @csrf @method('DELETE')
            </form>
        @endforeach
    @endcan

    @can('documentos.editar')
        <div class="modal fade" id="modalDocumentoCreate" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('documentos.store') }}" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Nuevo documento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @include('documentos._campos')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar documento</button>
                    </div>
                </form>
            </div>
        </div>

        @foreach ($documentos as $d)
            <div class="modal fade" id="modalDocumentoEdit{{ $d->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ route('documentos.update', $d) }}" class="modal-content">
                        @csrf @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Editar documento</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            @include('documentos._campos', ['documento' => $d])
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar documento</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    @endcan
</x-app-layout>
