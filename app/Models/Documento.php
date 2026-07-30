<?php

namespace App\Models;

use App\Support\NivelEstado;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documento extends Model
{
    protected $fillable = [
        'vehiculo_id',
        'tipo_documento',
        'numero_documento',
        'entidad_emisora',
        'fecha_emision',
        'fecha_vencimiento',
        'costo',
        'archivo_url',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_emision' => 'date',
            'fecha_vencimiento' => 'date',
            'costo' => 'decimal:2',
        ];
    }

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class);
    }

    protected function diasRestantes(): Attribute
    {
        return Attribute::get(
            fn () => Carbon::today()->diffInDays($this->fecha_vencimiento, false)
        );
    }

    protected function nivel(): Attribute
    {
        return Attribute::get(
            fn () => NivelEstado::porDias($this->dias_restantes)
        );
    }
}
