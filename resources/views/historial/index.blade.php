<x-app-layout>
    <div class="view-header">
        <div>
            <h2>Historial de mantenciones</h2>
            <p class="sub">Registro de todas las mantenciones realizadas por vehículo.</p>
        </div>
    </div>

    <form method="GET" action="{{ route('historial.index') }}" class="d-flex gap-2 mb-3">
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
                <tr><th>PPU</th><th>Mantención</th><th>Fecha</th><th>Kilometraje</th><th>Taller</th><th>Costo</th></tr>
            </thead>
            <tbody>
                @forelse ($historial as $h)
                    <tr>
                        <td><span class="ppu-badge">{{ $h->vehiculo->ppu }}</span></td>
                        <td>{{ $h->tipoMantencion->nombre }}</td>
                        <td class="font-mono">{{ $h->fecha_realizada->format('d-m-Y') }}</td>
                        <td class="font-mono">{{ number_format($h->km_realizada, 0, ',', '.') }} km</td>
                        <td>{{ $h->taller ?: '—' }}</td>
                        <td class="font-mono">{{ $h->costo !== null ? '$' . number_format($h->costo, 0, ',', '.') : '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="empty-state">Sin registros de mantención realizados.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
