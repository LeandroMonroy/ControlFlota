@php
    $nivelLabel = ['vencido' => 'Vencido', 'urgente' => 'Urgente', 'proximo' => 'Próximo', 'ok' => 'Al día'];
@endphp
<x-app-layout>
    @include('informes._print_header', ['titulo' => 'Informe de documentos y vencimientos'])

    <form method="GET" action="{{ route('informes.documentos') }}" class="d-flex align-items-end gap-2 mb-3 d-print-none">
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
            <a href="{{ route('informes.documentos') }}" class="btn btn-link">Limpiar</a>
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
                <tr><th>PPU</th><th>Documento</th><th>N° documento</th><th>Vence</th><th>Estado</th><th>Costo</th></tr>
            </thead>
            <tbody>
                @forelse ($documentos as $d)
                    <tr>
                        <td><span class="ppu-badge">{{ $d->vehiculo->ppu }}</span></td>
                        <td>{{ $d->tipo_documento }}</td>
                        <td class="font-mono">{{ $d->numero_documento ?: '—' }}</td>
                        <td class="font-mono">{{ $d->fecha_vencimiento->format('d-m-Y') }}</td>
                        <td><span class="badge nivel-{{ $d->nivel }}">{{ $nivelLabel[$d->nivel] }}</span></td>
                        <td class="font-mono">{{ $d->costo !== null ? '$' . number_format($d->costo, 0, ',', '.') : '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="empty-state">Sin documentos en el rango seleccionado.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
