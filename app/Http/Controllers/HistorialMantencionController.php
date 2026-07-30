<?php

namespace App\Http\Controllers;

use App\Http\Requests\HistorialMantencionRequest;
use App\Models\HistorialMantencion;
use App\Models\Mantencion;
use App\Models\TipoMantencion;
use App\Models\Vehiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HistorialMantencionController extends Controller
{
    public function index(Request $request): View
    {
        $vehiculoId = $request->query('vehiculo_id');

        $historial = HistorialMantencion::with(['vehiculo', 'tipoMantencion'])
            ->when($vehiculoId, fn ($q) => $q->where('vehiculo_id', $vehiculoId))
            ->orderByDesc('fecha_realizada')
            ->orderByDesc('id')
            ->get();

        return view('historial.index', [
            'historial' => $historial,
            'vehiculos' => Vehiculo::orderBy('ppu')->get(),
            'tipos' => TipoMantencion::orderBy('nombre')->get(),
            'vehiculoFiltro' => $vehiculoId,
        ]);
    }

    /**
     * Registra una mantención realizada. Si existe un plan (vehiculo_id +
     * tipo_mantencion_id) lo actualiza para reiniciar el contador de
     * km/fecha; si no existe y se marcó "crear_plan", crea uno nuevo.
     * También sube el kilometraje del vehículo si el registrado es mayor.
     */
    public function store(HistorialMantencionRequest $request): RedirectResponse
    {
        $d = $request->validated();

        DB::transaction(function () use ($d) {
            HistorialMantencion::create([
                'vehiculo_id' => $d['vehiculo_id'],
                'tipo_mantencion_id' => $d['tipo_mantencion_id'],
                'fecha_realizada' => $d['fecha_realizada'],
                'km_realizada' => $d['km_realizada'],
                'costo' => $d['costo'] ?? null,
                'taller' => $d['taller'] ?? null,
                'observaciones' => $d['observaciones'] ?? null,
            ]);

            $plan = Mantencion::where('vehiculo_id', $d['vehiculo_id'])
                ->where('tipo_mantencion_id', $d['tipo_mantencion_id'])
                ->first();

            if ($plan) {
                $plan->update([
                    'km_ultima' => $d['km_realizada'],
                    'fecha_ultima' => $d['fecha_realizada'],
                    'activo' => true,
                ]);
            } elseif (! empty($d['crear_plan'])) {
                Mantencion::create([
                    'vehiculo_id' => $d['vehiculo_id'],
                    'tipo_mantencion_id' => $d['tipo_mantencion_id'],
                    'intervalo_km' => $d['intervalo_km'] ?? null,
                    'intervalo_meses' => $d['intervalo_meses'] ?? null,
                    'km_ultima' => $d['km_realizada'],
                    'fecha_ultima' => $d['fecha_realizada'],
                ]);
            }

            Vehiculo::where('id', $d['vehiculo_id'])
                ->where('kilometraje_actual', '<', $d['km_realizada'])
                ->update([
                    'kilometraje_actual' => $d['km_realizada'],
                    'fecha_kilometraje' => $d['fecha_realizada'],
                ]);
        });

        return redirect()->back()->with('success', 'Mantención registrada y plan actualizado.');
    }
}
