@php
    $reportes = [
        ['ruta' => 'informes.vehiculos', 'titulo' => 'Vehículos', 'desc' => 'Ficha técnica completa de la flota.'],
        ['ruta' => 'informes.documentos', 'titulo' => 'Documentos y vencimientos', 'desc' => 'Permisos, revisiones técnicas y seguros, con filtro por rango de fechas.'],
        ['ruta' => 'informes.mantenciones', 'titulo' => 'Plan de mantenciones', 'desc' => 'Estado actual del plan preventivo por vehículo.'],
        ['ruta' => 'informes.historial', 'titulo' => 'Historial de mantenciones', 'desc' => 'Mantenciones realizadas, con filtro por rango de fechas.'],
    ];
@endphp
<x-app-layout>
    <div class="view-header">
        <div>
            <h2>Informes</h2>
            <p class="sub">Vistas imprimibles para respaldo o revisión en papel.</p>
        </div>
    </div>

    <div class="row g-3">
        @foreach ($reportes as $r)
            <div class="col-12 col-md-6">
                <a href="{{ route($r['ruta']) }}" class="text-decoration-none">
                    <div class="kpi-card h-100">
                        <div class="fw-semibold text-body mb-1">{{ $r['titulo'] }}</div>
                        <div class="text-secondary small">{{ $r['desc'] }}</div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</x-app-layout>
