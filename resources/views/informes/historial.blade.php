<x-app-layout>
    @include('informes._print_header', ['titulo' => 'Informe de historial de mantenciones'])

    <form method="GET" action="{{ route('informes.historial') }}" class="d-flex align-items-end gap-2 mb-3 d-print-none">
        <div>
            <label class="form-label small mb-1">Desde</label>
            <input type="date" name="desde" value="{{ $desde }}" class="form-control">
        </div>
        <div>
            <label class="form-label small mb-1">Hasta</label>
            <input type="date" name="hasta" value="{{ $hasta }}" class="form-control">
        </div>
        <button type="submit" class="btn btn-outline-secondary">Filtrar</button>
        @if ($desde || $hasta)
            <a href="{{ route('informes.historial') }}" class="btn btn-link">Limpiar</a>
        @endif
    </form>

    @if ($desde || $hasta)
        <p class="d-none d-print-block small text-secondary">
            Rango: {{ $desde ?: 'sin límite inferior' }} — {{ $hasta ?: 'sin límite superior' }}
        </p>
    @endif

    <div class="table-responsive">
        <table class="table table-sm table-bordered align-middle bg-white">
            <thead>
                <tr><th>PPU</th><th>Mantención</th><th>Fecha</th><th>Kilometraje</th><th>Taller</th><th>Costo</th></tr>
            </thead>
            <tbody>
                @forelse ($historial as $h)
                    <tr>
                        <td><x-ppu-plate :ppu="$h->vehiculo->ppu" /></td>
                        <td>{{ $h->tipoMantencion->nombre }}</td>
                        <td class="font-mono">{{ $h->fecha_realizada->format('d-m-Y') }}</td>
                        <td class="font-mono">{{ number_format($h->km_realizada, 0, ',', '.') }} km</td>
                        <td>{{ $h->taller ?: '—' }}</td>
                        <td class="font-mono">{{ $h->costo !== null ? '$' . number_format($h->costo, 0, ',', '.') : '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="empty-state">Sin registros en el rango seleccionado.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
