<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MantencionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'intervalo_km' => ['nullable', 'integer', 'min:0'],
            'intervalo_meses' => ['nullable', 'integer', 'min:0'],
            'km_ultima' => ['nullable', 'integer', 'min:0'],
            'fecha_ultima' => ['nullable', 'date'],
            'activo' => ['sometimes', 'boolean'],
            'observaciones' => ['nullable', 'string'],
        ];

        // El vehículo y el tipo de mantención solo se definen al crear el plan.
        if ($this->isMethod('post')) {
            $rules['vehiculo_id'] = ['required', 'exists:vehiculos,id'];
            $rules['tipo_mantencion_id'] = [
                'required',
                'exists:tipos_mantencion,id',
                Rule::unique('mantenciones')->where(fn ($q) => $q->where('vehiculo_id', $this->vehiculo_id)),
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'tipo_mantencion_id.unique' => 'Este vehículo ya tiene un plan para ese tipo de mantención.',
        ];
    }
}
