<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialMantencion extends Model
{
    const UPDATED_AT = null;

    protected $table = 'historial_mantenciones';

    protected $fillable = [
        'vehiculo_id',
        'tipo_mantencion_id',
        'fecha_realizada',
        'km_realizada',
        'costo',
        'taller',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_realizada' => 'date',
            'km_realizada' => 'integer',
            'costo' => 'decimal:2',
        ];
    }

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function tipoMantencion(): BelongsTo
    {
        return $this->belongsTo(TipoMantencion::class);
    }
}
