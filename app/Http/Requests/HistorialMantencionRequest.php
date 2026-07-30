<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HistorialMantencionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehiculo_id' => ['required', 'exists:vehiculos,id'],
            'tipo_mantencion_id' => ['required', 'exists:tipos_mantencion,id'],
            'fecha_realizada' => ['required', 'date'],
            'km_realizada' => ['required', 'integer', 'min:0'],
            'costo' => ['nullable', 'numeric', 'min:0'],
            'taller' => ['nullable', 'string', 'max:100'],
            'observaciones' => ['nullable', 'string'],
            'crear_plan' => ['nullable', 'boolean'],
            'intervalo_km' => ['nullable', 'integer', 'min:0'],
            'intervalo_meses' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
