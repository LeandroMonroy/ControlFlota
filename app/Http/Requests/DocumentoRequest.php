<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehiculo_id' => ['required', 'exists:vehiculos,id'],
            'tipo_documento' => ['required', Rule::in([
                'Permiso de Circulación',
                'Revisión Técnica',
                'Seguro Obligatorio (SOAP)',
                'Seguro Adicional',
                'Certificado de Emisiones',
                'Padrón / Inscripción',
                'Otro',
            ])],
            'numero_documento' => ['nullable', 'string', 'max:100'],
            'entidad_emisora' => ['nullable', 'string', 'max:100'],
            'fecha_emision' => ['nullable', 'date'],
            'fecha_vencimiento' => ['required', 'date'],
            'costo' => ['nullable', 'numeric', 'min:0'],
            'archivo_url' => ['nullable', 'string', 'max:255'],
            'observaciones' => ['nullable', 'string'],
        ];
    }
}
