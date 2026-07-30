<?php

namespace App\Http\Controllers;

use App\Http\Requests\MantencionRequest;
use App\Models\Mantencion;
use App\Models\TipoMantencion;
use App\Models\Vehiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MantencionController extends Controller
{
    public function index(Request $request): View
    {
        $vehiculoId = $request->query('vehiculo_id');

        $mantenciones = Mantencion::with(['vehiculo', 'tipoMantencion'])
            ->when($vehiculoId, fn ($q) => $q->where('vehiculo_id', $vehiculoId))
            ->get()
            ->sortBy('km_restante')
            ->values();

        return view('mantenciones.index', [
            'mantenciones' => $mantenciones,
            'vehiculos' => Vehiculo::orderBy('ppu')->get(),
            'tipos' => TipoMantencion::orderBy('nombre')->get(),
            'vehiculoFiltro' => $vehiculoId,
        ]);
    }

    public function store(MantencionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['km_ultima'] ??= 0;
        $data['fecha_ultima'] ??= now()->toDateString();

        Mantencion::create($data);

        return redirect()->route('mantenciones.index')->with('success', 'Ítem de mantención agregado.');
    }

    public function update(MantencionRequest $request, Mantencion $mantencion): RedirectResponse
    {
        $data = $request->validated();
        $data['km_ultima'] ??= 0;
        $data['fecha_ultima'] ??= now()->toDateString();
        $data['activo'] = $request->boolean('activo', true);

        $mantencion->update($data);

        return redirect()->route('mantenciones.index')->with('success', 'Plan de mantención actualizado.');
    }

    public function destroy(Mantencion $mantencion): RedirectResponse
    {
        $mantencion->delete();

        return redirect()->route('mantenciones.index')->with('success', 'Ítem eliminado.');
    }
}
