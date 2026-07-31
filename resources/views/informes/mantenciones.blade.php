@php
    $nivelLabel = ['vencido' => 'Vencido', 'urgente' => 'Urgente', 'proximo' => 'Próximo', 'ok' => 'Al día'];
@endphp
<x-app-layout>
    @include('informes._print_header', ['titulo' => 'Informe del plan de mantenciones'])

    <div class="table-responsive">
        <table class="table table-sm table-bordered align-middle bg-white">
            <thead>
                <tr><th>PPU</th><th>Mantención</th><th>Último → Objetivo</th><th>Restante</th><th>Fecha objetivo</th><th>Estado</th></tr>
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
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="empty-state">Sin ítems de mantención configurados.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
