<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehiculoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ppu' => [
                'required', 'string', 'max:10',
                Rule::unique('vehiculos', 'ppu')->ignore($this->route('vehiculo')),
            ],
            'tipo' => ['required', Rule::in(['Automóvil', 'Camioneta', 'Furgón', 'Motocicleta', 'Camión', 'Bus', 'Cuatrimoto', 'Otro'])],
            'tipo_combustible' => ['nullable', Rule::in(['Petróleo', 'Bencina', 'Gas', 'Eléctrico', 'Híbrido', 'Otro'])],
            'marca' => ['required', 'string', 'max:50'],
            'modelo' => ['required', 'string', 'max:50'],
            'anio' => ['required', 'integer', 'min:1980', 'max:2035'],
            'numero_motor' => ['nullable', 'string', 'max:60'],
            'numero_chasis' => ['nullable', 'string', 'max:60'],
            'color' => ['nullable', 'string', 'max:30'],
            'kilometraje_actual' => ['nullable', 'integer', 'min:0'],
            'area_asignada' => ['nullable', 'string', 'max:80'],
            'responsable' => ['nullable', 'string', 'max:80'],
            'estado' => ['nullable', Rule::in(['Operativo', 'En mantención', 'Fuera de servicio', 'Dado de baja'])],
            'observaciones' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'ppu.unique' => 'Ya existe un vehículo con esa PPU.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('ppu')) {
            $this->merge(['ppu' => strtoupper(trim((string) $this->ppu))]);
        }
    }
}
