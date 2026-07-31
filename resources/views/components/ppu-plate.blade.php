@props(['ppu', 'size' => 'sm'])
@php
    $clean = strtoupper(trim((string) $ppu));
    $letras = $clean;
    $numeros = '';
    if (str_contains($clean, '-')) {
        [$letras, $numeros] = array_pad(explode('-', $clean, 2), 2, '');
    }
    $izq = $der = null;
    if (strlen($letras) === 4) {
        $izq = substr($letras, 0, 2);
        $der = substr($letras, 2, 2);
    }
@endphp
<span class="ppu-plate {{ $size === 'lg' ? 'ppu-plate-lg' : '' }}">
    <span class="ppu-plate-row">
        @if ($izq && $der)
            <span>{{ $izq }}</span><span class="ppu-plate-dot">&middot;</span><span>{{ $der }}</span>
            <span class="ppu-plate-badge" aria-hidden="true"><span></span></span>
            <span>{{ $numeros }}</span>
        @else
            <span>{{ $clean }}</span>
        @endif
    </span>
    <span class="ppu-plate-country">CHILE</span>
</span>
