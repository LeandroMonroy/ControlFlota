@php
    $tipos = ['Automóvil', 'Camioneta', 'Furgón', 'Motocicleta', 'Camión', 'Bus', 'Cuatrimoto', 'Otro'];
    $combustibles = ['Petróleo', 'Bencina', 'Gas', 'Eléctrico', 'Híbrido', 'Otro'];
    $estados = ['Operativo', 'En mantención', 'Fuera de servicio', 'Dado de baja'];
    $tiposDoc = [
        'Permiso de Circulación', 'Revisión Técnica', 'Seguro Obligatorio (SOAP)',
        'Seguro Adicional', 'Certificado de Emisiones', 'Padrón / Inscripción', 'Otro',
    ];
    $estadoBadge = [
        'Operativo' => 'success',
        'En mantención' => 'warning',
        'Fuera de servicio' => 'danger',
        'Dado de baja' => 'secondary',
    ];
    $sortLink = fn ($col) => request()->fullUrlWithQuery(['sort' => $col, 'dir' => ($sort === $col && $dir === 'asc') ? 'desc' : 'asc']);
    $sortIcon = fn ($col) => $sort === $col ? ($dir === 'asc' ? ' ▲' : ' ▼') : '';
@endphp
<x-app-layout>
    <div class="view-header">
        <div>
            <h2>Vehículos</h2>
            <p class="sub">Ficha técnica de cada unidad de la flota.</p>
        </div>
        @can('vehiculos.editar')
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalVehiculoCreate">
                + Nuevo vehículo
            </button>
        @endcan
    </div>

    <form method="GET" action="{{ route('vehiculos.index') }}" class="d-flex gap-2 mb-3">
        <input type="text" name="q" value="{{ $buscar }}" class="form-control" style="max-width:280px" placeholder="Buscar por PPU, marca o modelo…">
        <select name="estado" class="form-select" style="max-width:220px">
            <option value="">Todos los estados</option>
            @foreach ($estados as $e)
                <option value="{{ $e }}" @selected($estadoFiltro === $e)>{{ $e }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-outline-secondary">Filtrar</button>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white">
            <thead>
                <tr>
                    <th><a href="{{ $sortLink('ppu') }}" class="th-sort">PPU{{ $sortIcon('ppu') }}</a></th>
                    <th><a href="{{ $sortLink('tipo') }}" class="th-sort">Tipo{{ $sortIcon('tipo') }}</a></th>
                    <th><a href="{{ $sortLink('marca') }}" class="th-sort">Marca / Modelo{{ $sortIcon('marca') }}</a></th>
                    <th><a href="{{ $sortLink('anio') }}" class="th-sort">Año{{ $sortIcon('anio') }}</a></th>
                    <th><a href="{{ $sortLink('kilometraje_actual') }}" class="th-sort">Kilometraje{{ $sortIcon('kilometraje_actual') }}</a></th>
                    <th><a href="{{ $sortLink('area_asignada') }}" class="th-sort">Área{{ $sortIcon('area_asignada') }}</a></th>
                    <th><a href="{{ $sortLink('estado') }}" class="th-sort">Estado{{ $sortIcon('estado') }}</a></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($vehiculos as $v)
                    <tr>
                        <td><span class="ppu-badge">{{ $v->ppu }}</span></td>
                        <td>{{ $v->tipo }}</td>
                        <td>{{ $v->marca }} {{ $v->modelo }}</td>
                        <td class="font-mono">{{ $v->anio }}</td>
                        <td class="font-mono">{{ number_format($v->kilometraje_actual, 0, ',', '.') }} km</td>
                        <td>{{ $v->area_asignada ?: '—' }}</td>
                        <td><span class="badge bg-{{ $estadoBadge[$v->estado] ?? 'secondary' }}">{{ $v->estado }}</span></td>
                        <td class="text-end">
                            @can('vehiculos.editar')
                                <button type="button" class="btn btn-sm btn-outline-secondary" title="Editar" data-bs-toggle="modal" data-bs-target="#modalVehiculoEdit{{ $v->id }}">✎</button>
                            @endcan
                            @can('documentos.editar')
                                <button type="button" class="btn btn-sm btn-outline-secondary" title="Agregar documento" data-bs-toggle="modal" data-bs-target="#modalDocumentoCreate{{ $v->id }}">▤</button>
                            @endcan
                            @can('mantenciones.editar')
                                <button type="button" class="btn btn-sm btn-outline-secondary" title="Nuevo ítem de mantención" data-bs-toggle="modal" data-bs-target="#modalMantencionCreate{{ $v->id }}">⚙</button>
                            @endcan
                            @can('administrador')
                                <button type="submit" form="deleteVehiculo{{ $v->id }}" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Eliminar el vehículo {{ $v->ppu }}? Esto también elimina sus documentos, plan de mantención e historial.')">✕</button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8"><div class="empty-state">No hay vehículos que coincidan con el filtro.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Formularios de eliminación (fuera de la tabla, referenciados por el botón vía atributo form) --}}
    @can('administrador')
        @foreach ($vehiculos as $v)
            <form id="deleteVehiculo{{ $v->id }}" method="POST" action="{{ route('vehiculos.destroy', $v) }}" class="d-none">
                @csrf @method('DELETE')
            </form>
        @endforeach
    @endcan

    @can('vehiculos.editar')
        {{-- Modal: nuevo vehículo --}}
        <div class="modal fade" id="modalVehiculoCreate" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('vehiculos.store') }}" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Nuevo vehículo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @include('vehiculos._campos')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar vehículo</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modales: editar vehículo (uno por fila, pre-rellenado) --}}
        @foreach ($vehiculos as $v)
            <div class="modal fade" id="modalVehiculoEdit{{ $v->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ route('vehiculos.update', $v) }}" class="modal-content">
                        @csrf @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Editar vehículo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            @include('vehiculos._campos', ['vehiculo' => $v])
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar vehículo</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', () => new bootstrap.Modal(document.getElementById('modalVehiculoCreate')).show());
            </script>
        @endif
    @endcan

    @can('documentos.editar')
        {{-- Modales: agregar documento directo desde la fila del vehículo (mismo formulario que Documentación) --}}
        @foreach ($vehiculos as $v)
            <div class="modal fade" id="modalDocumentoCreate{{ $v->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ route('documentos.store') }}" class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Nuevo documento — {{ $v->ppu }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            @include('documentos._campos', ['presetVehiculoId' => $v->id])
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

    @can('mantenciones.editar')
        {{-- Modales: nuevo ítem de mantención directo desde la fila del vehículo (mismo formulario que Mantenciones) --}}
        @foreach ($vehiculos as $v)
            <div class="modal fade" id="modalMantencionCreate{{ $v->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ route('mantenciones.store') }}" class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Nuevo ítem de mantención — {{ $v->ppu }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            @include('mantenciones._campos_nuevo', ['tipos' => $tiposMantencion, 'presetVehiculoId' => $v->id])
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
