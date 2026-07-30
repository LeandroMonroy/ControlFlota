<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentoRequest;
use App\Models\Documento;
use App\Models\Vehiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DocumentoController extends Controller
{
    public function index(Request $request): View
    {
        $vehiculoId = $request->query('vehiculo_id');

        $documentos = Documento::with('vehiculo')
            ->when($vehiculoId, fn ($q) => $q->where('vehiculo_id', $vehiculoId))
            ->get()
            ->sortBy('fecha_vencimiento')
            ->values();

        return view('documentos.index', [
            'documentos' => $documentos,
            'vehiculos' => Vehiculo::orderBy('ppu')->get(),
            'vehiculoFiltro' => $vehiculoId,
        ]);
    }

    public function store(DocumentoRequest $request): RedirectResponse
    {
        Documento::create($request->validated());

        return redirect()->route('documentos.index')->with('success', 'Documento registrado.');
    }

    public function update(DocumentoRequest $request, Documento $documento): RedirectResponse
    {
        $documento->update($request->validated());

        return redirect()->route('documentos.index')->with('success', 'Documento actualizado.');
    }

    public function destroy(Documento $documento): RedirectResponse
    {
        $documento->delete();

        return redirect()->route('documentos.index')->with('success', 'Documento eliminado.');
    }
}
