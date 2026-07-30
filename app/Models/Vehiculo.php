<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehiculo extends Model
{
    use HasFactory;

    protected $fillable = [
        'ppu',
        'tipo',
        'tipo_combustible',
        'marca',
        'modelo',
        'anio',
        'numero_motor',
        'numero_chasis',
        'color',
        'kilometraje_actual',
        'fecha_kilometraje',
        'area_asignada',
        'responsable',
        'estado',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'anio' => 'integer',
            'kilometraje_actual' => 'integer',
            'fecha_kilometraje' => 'date',
        ];
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class);
    }

    public function mantenciones(): HasMany
    {
        return $this->hasMany(Mantencion::class);
    }

    public function historial(): HasMany
    {
        return $this->hasMany(HistorialMantencion::class);
    }
}
