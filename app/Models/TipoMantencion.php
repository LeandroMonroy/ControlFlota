<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoMantencion extends Model
{
    public $timestamps = false;

    protected $table = 'tipos_mantencion';

    protected $fillable = [
        'nombre',
        'intervalo_km_defecto',
        'intervalo_meses_defecto',
        'descripcion',
    ];

    protected function casts(): array
    {
        return [
            'intervalo_km_defecto' => 'integer',
            'intervalo_meses_defecto' => 'integer',
        ];
    }

    public function mantenciones(): HasMany
    {
        return $this->hasMany(Mantencion::class);
    }
}
