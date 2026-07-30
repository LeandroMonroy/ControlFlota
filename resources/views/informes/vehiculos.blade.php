@php
    $estadoBadge = [
        'Operativo' => 'success',
        'En mantención' => 'warning',
        'Fuera de servicio' => 'danger',
        'Dado de baja' => 'secondary',
    ];
@endphp
<x-app-layout>
    @include('informes._print_header', ['titulo' => 'Informe de vehículos'])

    <div class="table-responsive">
        <table class="table table-sm table-bordered align-middle bg-white">
            <thead>
                <tr>
                    <th>PPU</th><th>Tipo</th><th>Combustible</th><th>Marca / Modelo</th><th>Año</th>
                    <th>Kilometraje</th><th>Área</th><th>Responsable</th><th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($vehiculos as $v)
                    <tr>
                        <td><span class="ppu-badge">{{ $v->ppu }}</span></td>
                        <td>{{ $v->tipo }}</td>
                        <td>{{ $v->tipo_combustible ?: '—' }}</td>
                        <td>{{ $v->marca }} {{ $v->modelo }}</td>
                        <td class="font-mono">{{ $v->anio }}</td>
                        <td class="font-mono">{{ number_format($v->kilometraje_actual, 0, ',', '.') }} km</td>
                        <td>{{ $v->area_asignada ?: '—' }}</td>
                        <td>{{ $v->responsable ?: '—' }}</td>
                        <td><span class="badge bg-{{ $estadoBadge[$v->estado] ?? 'secondary' }}">{{ $v->estado }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="9"><div class="empty-state">Sin vehículos registrados.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
