<?php

namespace Database\Seeders;

use App\Models\TipoMantencion;
use Illuminate\Database\Seeder;

class TiposMantencionSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Cambio de aceite y filtro', 'intervalo_km_defecto' => 5000, 'intervalo_meses_defecto' => 6, 'descripcion' => 'Aceite motor + filtro de aceite'],
            ['nombre' => 'Filtro de aire', 'intervalo_km_defecto' => 10000, 'intervalo_meses_defecto' => 12, 'descripcion' => null],
            ['nombre' => 'Filtro de combustible', 'intervalo_km_defecto' => 20000, 'intervalo_meses_defecto' => 24, 'descripcion' => null],
            ['nombre' => 'Rotación de neumáticos', 'intervalo_km_defecto' => 10000, 'intervalo_meses_defecto' => 6, 'descripcion' => null],
            ['nombre' => 'Cambio de neumáticos', 'intervalo_km_defecto' => 40000, 'intervalo_meses_defecto' => null, 'descripcion' => null],
            ['nombre' => 'Revisión / cambio de frenos', 'intervalo_km_defecto' => 20000, 'intervalo_meses_defecto' => 12, 'descripcion' => 'Pastillas, discos, líquido de freno'],
            ['nombre' => 'Correa de distribución', 'intervalo_km_defecto' => 60000, 'intervalo_meses_defecto' => 48, 'descripcion' => null],
            ['nombre' => 'Refrigerante / sistema de enfriamiento', 'intervalo_km_defecto' => 40000, 'intervalo_meses_defecto' => 24, 'descripcion' => null],
            ['nombre' => 'Batería', 'intervalo_km_defecto' => null, 'intervalo_meses_defecto' => 24, 'descripcion' => 'Revisión de carga y bornes'],
            ['nombre' => 'Alineación y balanceo', 'intervalo_km_defecto' => 10000, 'intervalo_meses_defecto' => 12, 'descripcion' => null],
            ['nombre' => 'Revisión general / mantención mayor', 'intervalo_km_defecto' => 30000, 'intervalo_meses_defecto' => 12, 'descripcion' => 'Chequeo integral del vehículo'],
        ];

        foreach ($tipos as $tipo) {
            TipoMantencion::query()->updateOrCreate(['nombre' => $tipo['nombre']], $tipo);
        }
    }
}
