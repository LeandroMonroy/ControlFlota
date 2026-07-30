<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehiculoRequest;
use App\Models\TipoMantencion;
use App\Models\Vehiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehiculoController extends Controller
{
    const COLUMNAS_ORDENABLES = ['ppu', 'tipo', 'marca', 'anio', 'kilometraje_actual', 'area_asignada', 'estado'];

    public function index(Request $request): View
    {
        $buscar = trim((string) $request->query('q', ''));
        $estado = trim((string) $request->query('estado', ''));
        $sort = in_array($request->query('sort'), self::COLUMNAS_ORDENABLES, true) ? $request->query('sort') : 'ppu';
        $dir = $request->query('dir') === 'desc' ? 'desc' : 'asc';

        $vehiculos = Vehiculo::query()
            ->when($buscar !== '', fn ($q) => $q->where(fn ($q2) => $q2
                ->where('ppu', 'like', "%{$buscar}%")
                ->orWhere('marca', 'like', "%{$buscar}%")
                ->orWhere('modelo', 'like', "%{$buscar}%")
            ))
            ->when($estado !== '', fn ($q) => $q->where('estado', $estado))
            ->orderBy($sort, $dir)
            ->when($sort === 'marca', fn ($q) => $q->orderBy('modelo', $dir))
            ->get();

        return view('vehiculos.index', [
            'vehiculos' => $vehiculos,
            'buscar' => $buscar,
            'estadoFiltro' => $estado,
            'sort' => $sort,
            'dir' => $dir,
            'tiposMantencion' => TipoMantencion::orderBy('nombre')->get(),
        ]);
    }

    public function store(VehiculoRequest $request): RedirectResponse
    {
        Vehiculo::create($request->validated());

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo creado.');
    }

    public function update(VehiculoRequest $request, Vehiculo $vehiculo): RedirectResponse
    {
        $vehiculo->update($request->validated());

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo actualizado.');
    }

    public function destroy(Vehiculo $vehiculo): RedirectResponse
    {
        $ppu = $vehiculo->ppu;
        $vehiculo->delete();

        return redirect()->route('vehiculos.index')->with('success', "Vehículo {$ppu} eliminado.");
    }
}
