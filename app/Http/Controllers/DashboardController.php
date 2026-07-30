<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Mantencion;
use App\Models\TipoMantencion;
use App\Models\Vehiculo;

class DashboardController extends Controller
{
    public function index()
    {
        $resumen = [
            'total_vehiculos' => Vehiculo::count(),
            'operativos' => Vehiculo::where('estado', 'Operativo')->count(),
            'en_mantencion' => Vehiculo::where('estado', 'En mantención')->count(),
            'fuera_servicio' => Vehiculo::where('estado', 'Fuera de servicio')->count(),
        ];

        $documentos = Documento::with('vehiculo')
            ->get()
            ->filter(fn (Documento $d) => $d->dias_restantes <= 30)
            ->sortBy('dias_restantes')
            ->values();

        $mantenciones = Mantencion::where('activo', true)
            ->with(['vehiculo', 'tipoMantencion'])
            ->get()
            ->filter(fn (Mantencion $m) => in_array($m->nivel, ['vencido', 'urgente']))
            ->sortBy('km_restante')
            ->values();

        $vehiculos = Vehiculo::orderBy('ppu')->get();
        $tipos = TipoMantencion::orderBy('nombre')->get();

        return view('dashboard', compact('resumen', 'documentos', 'mantenciones', 'vehiculos', 'tipos'));
    }
}
