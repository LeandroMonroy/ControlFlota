<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\HistorialMantencion;
use App\Models\Mantencion;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InformeController extends Controller
{
    public function index(): View
    {
        return view('informes.index');
    }

    public function vehiculos(): View
    {
        return view('informes.vehiculos', [
            'vehiculos' => Vehiculo::orderBy('ppu')->get(),
        ]);
    }

    public function documentos(Request $request): View
    {
        $desde = $request->query('desde');
        $hasta = $request->query('hasta');

        $documentos = Documento::with('vehiculo')
            ->when($desde, fn ($q) => $q->whereDate('fecha_vencimiento', '>=', $desde))
            ->when($hasta, fn ($q) => $q->whereDate('fecha_vencimiento', '<=', $hasta))
            ->get()
            ->sortBy('fecha_vencimiento')
            ->values();

        return view('informes.documentos', compact('documentos', 'desde', 'hasta'));
    }

    public function mantenciones(): View
    {
        $mantenciones = Mantencion::with(['vehiculo', 'tipoMantencion'])
            ->get()
            ->sortBy('km_restante')
            ->values();

        return view('informes.mantenciones', compact('mantenciones'));
    }

    public function historial(Request $request): View
    {
        $desde = $request->query('desde');
        $hasta = $request->query('hasta');

        $historial = HistorialMantencion::with(['vehiculo', 'tipoMantencion'])
            ->when($desde, fn ($q) => $q->whereDate('fecha_realizada', '>=', $desde))
            ->when($hasta, fn ($q) => $q->whereDate('fecha_realizada', '<=', $hasta))
            ->orderByDesc('fecha_realizada')
            ->get();

        return view('informes.historial', compact('historial', 'desde', 'hasta'));
    }
}
