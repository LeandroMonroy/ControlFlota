@php $titulo = $titulo ?? 'Informe'; @endphp
<div class="d-flex align-items-start justify-content-between mb-3 d-print-none">
    <div>
        <h2 class="mb-0">{{ $titulo }}</h2>
        <p class="sub">Generado el {{ now()->format('d-m-Y H:i') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('informes.index') }}" class="btn btn-outline-secondary">← Informes</a>
        <button type="button" class="btn btn-primary" onclick="window.print()">Imprimir</button>
    </div>
</div>

<div class="d-none d-print-block mb-3">
    <h2 class="mb-0">{{ $titulo }}</h2>
    <p class="sub mb-0">ControlFlota · Ilustre Municipalidad de Pozo Almonte · Generado el {{ now()->format('d-m-Y H:i') }}</p>
</div>
