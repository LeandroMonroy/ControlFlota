<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehiculoRequest;
use App\Models\TipoMantencion;
use App\Models\Vehiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

    public function show(Vehiculo $vehiculo): View
    {
        $vehiculo->load(['documentos' => fn ($q) => $q->orderBy('fecha_vencimiento')]);

        return view('vehiculos.show', [
            'vehiculo' => $vehiculo,
        ]);
    }

    public function store(VehiculoRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['foto1', 'foto2', 'eliminar_foto1', 'eliminar_foto2']);

        foreach (['foto1', 'foto2'] as $campo) {
            if ($request->hasFile($campo)) {
                $data["{$campo}_path"] = $request->file($campo)->store('vehiculos', 'public');
            }
        }

        Vehiculo::create($data);

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo creado.');
    }

    public function update(VehiculoRequest $request, Vehiculo $vehiculo): RedirectResponse
    {
        $data = $request->safe()->except(['foto1', 'foto2', 'eliminar_foto1', 'eliminar_foto2']);

        foreach (['foto1', 'foto2'] as $campo) {
            $columna = "{$campo}_path";

            if ($request->hasFile($campo)) {
                if ($vehiculo->$columna) {
                    Storage::disk('public')->delete($vehiculo->$columna);
                }
                $data[$columna] = $request->file($campo)->store('vehiculos', 'public');
            } elseif ($request->boolean("eliminar_{$campo}")) {
                if ($vehiculo->$columna) {
                    Storage::disk('public')->delete($vehiculo->$columna);
                }
                $data[$columna] = null;
            }
        }

        $vehiculo->update($data);

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo actualizado.');
    }

    public function destroy(Vehiculo $vehiculo): RedirectResponse
    {
        $ppu = $vehiculo->ppu;

        foreach ([$vehiculo->foto1_path, $vehiculo->foto2_path] as $path) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
        }

        $vehiculo->delete();

        return redirect()->route('vehiculos.index')->with('success', "Vehículo {$ppu} eliminado.");
    }
}
