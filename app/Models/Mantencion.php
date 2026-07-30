<?php

namespace App\Models;

use App\Support\NivelEstado;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mantencion extends Model
{
    protected $table = 'mantenciones';

    protected $fillable = [
        'vehiculo_id',
        'tipo_mantencion_id',
        'intervalo_km',
        'intervalo_meses',
        'km_ultima',
        'fecha_ultima',
        'activo',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'intervalo_km' => 'integer',
            'intervalo_meses' => 'integer',
            'km_ultima' => 'integer',
            'fecha_ultima' => 'date',
            'activo' => 'boolean',
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

    /** Intervalo en km efectivo: el override del vehículo o el del catálogo. */
    protected function intervaloKmEfectivo(): Attribute
    {
        return Attribute::get(
            fn () => $this->intervalo_km ?? $this->tipoMantencion?->intervalo_km_defecto
        );
    }

    /** Intervalo en meses efectivo: el override del vehículo o el del catálogo. */
    protected function intervaloMesesEfectivo(): Attribute
    {
        return Attribute::get(
            fn () => $this->intervalo_meses ?? $this->tipoMantencion?->intervalo_meses_defecto
        );
    }

    protected function kmObjetivo(): Attribute
    {
        return Attribute::get(function () {
            if ($this->km_ultima === null || $this->intervalo_km_efectivo === null) {
                return null;
            }

            return $this->km_ultima + $this->intervalo_km_efectivo;
        });
    }

    protected function kmRestante(): Attribute
    {
        return Attribute::get(function () {
            if ($this->km_objetivo === null) {
                return null;
            }

            return $this->km_objetivo - $this->vehiculo->kilometraje_actual;
        });
    }

    protected function fechaObjetivo(): Attribute
    {
        return Attribute::get(function () {
            if ($this->fecha_ultima === null || $this->intervalo_meses_efectivo === null) {
                return null;
            }

            return Carbon::parse($this->fecha_ultima)->addMonths($this->intervalo_meses_efectivo);
        });
    }

    protected function diasRestantes(): Attribute
    {
        return Attribute::get(function () {
            if ($this->fecha_objetivo === null) {
                return null;
            }

            return Carbon::today()->diffInDays($this->fecha_objetivo, false);
        });
    }

    protected function nivel(): Attribute
    {
        return Attribute::get(
            fn () => NivelEstado::paraMantencion($this->km_restante, $this->dias_restantes)
        );
    }
}
