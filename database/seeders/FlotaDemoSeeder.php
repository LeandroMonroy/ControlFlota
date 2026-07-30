<?php

namespace Database\Seeders;

use App\Models\Documento;
use App\Models\HistorialMantencion;
use App\Models\Mantencion;
use App\Models\TipoMantencion;
use App\Models\Vehiculo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class FlotaDemoSeeder extends Seeder
{
    /**
     * Mismos 3 vehículos de ejemplo del prototipo original, con fechas
     * relativas a hoy para que las alertas de dashboard se vean pobladas
     * sin importar cuándo se ejecute el seeder.
     */
    public function run(): void
    {
        $hoy = Carbon::today();

        $rpxk = Vehiculo::query()->updateOrCreate(['ppu' => 'RPXK-45'], [
            'tipo' => 'Camioneta', 'tipo_combustible' => 'Petróleo', 'marca' => 'Toyota', 'modelo' => 'Hilux 4x4', 'anio' => 2023,
            'numero_motor' => 'TY2345MOT', 'numero_chasis' => 'CH998877665', 'color' => 'Blanco',
            'kilometraje_actual' => 38500, 'fecha_kilometraje' => $hoy,
            'area_asignada' => 'SKYVAULT', 'responsable' => 'E. Vargas', 'estado' => 'Operativo',
        ]);

        $fktr = Vehiculo::query()->updateOrCreate(['ppu' => 'FKTR-12'], [
            'tipo' => 'Furgón', 'tipo_combustible' => 'Petróleo', 'marca' => 'Hyundai', 'modelo' => 'H1', 'anio' => 2021,
            'numero_motor' => 'HY1122MOT', 'numero_chasis' => 'CH112233445', 'color' => 'Blanco',
            'kilometraje_actual' => 62300, 'fecha_kilometraje' => $hoy,
            'area_asignada' => 'Central de Cámaras', 'responsable' => 'M. Rojas', 'estado' => 'Operativo',
        ]);

        $bwsz = Vehiculo::query()->updateOrCreate(['ppu' => 'BWSZ-88'], [
            'tipo' => 'Automóvil', 'tipo_combustible' => 'Bencina', 'marca' => 'Suzuki', 'modelo' => 'Swift', 'anio' => 2020,
            'numero_motor' => 'SZ5544MOT', 'numero_chasis' => 'CH556677889', 'color' => 'Gris',
            'kilometraje_actual' => 81200, 'fecha_kilometraje' => $hoy,
            'area_asignada' => 'Patrullaje', 'responsable' => 'J. Soto', 'estado' => 'En mantención',
        ]);

        Documento::query()->updateOrCreate(
            ['vehiculo_id' => $rpxk->id, 'tipo_documento' => 'Permiso de Circulación'],
            ['numero_documento' => 'PC-2026-0451', 'entidad_emisora' => 'I. Municipalidad de Pozo Almonte', 'fecha_emision' => $hoy->copy()->subMonths(4), 'fecha_vencimiento' => $hoy->copy()->addMonths(8), 'costo' => 45000]
        );
        Documento::query()->updateOrCreate(
            ['vehiculo_id' => $rpxk->id, 'tipo_documento' => 'Revisión Técnica'],
            ['numero_documento' => 'RT-88231', 'entidad_emisora' => 'Planta RT Iquique', 'fecha_emision' => $hoy->copy()->subMonths(6), 'fecha_vencimiento' => $hoy->copy()->addDays(10), 'costo' => 18000]
        );
        Documento::query()->updateOrCreate(
            ['vehiculo_id' => $rpxk->id, 'tipo_documento' => 'Seguro Obligatorio (SOAP)'],
            ['numero_documento' => 'SOAP-90212', 'entidad_emisora' => 'Mapfre', 'fecha_emision' => $hoy->copy()->subMonths(7), 'fecha_vencimiento' => $hoy->copy()->addDays(25), 'costo' => 12500]
        );
        Documento::query()->updateOrCreate(
            ['vehiculo_id' => $fktr->id, 'tipo_documento' => 'Permiso de Circulación'],
            ['numero_documento' => 'PC-2026-0452', 'entidad_emisora' => 'I. Municipalidad de Pozo Almonte', 'fecha_emision' => $hoy->copy()->subMonths(4), 'fecha_vencimiento' => $hoy->copy()->addMonths(8), 'costo' => 45000]
        );
        Documento::query()->updateOrCreate(
            ['vehiculo_id' => $fktr->id, 'tipo_documento' => 'Revisión Técnica'],
            ['numero_documento' => 'RT-88232', 'entidad_emisora' => 'Planta RT Iquique', 'fecha_emision' => $hoy->copy()->subMonths(12), 'fecha_vencimiento' => $hoy->copy()->subDays(5), 'costo' => 18000]
        );
        Documento::query()->updateOrCreate(
            ['vehiculo_id' => $bwsz->id, 'tipo_documento' => 'Seguro Obligatorio (SOAP)'],
            ['numero_documento' => 'SOAP-90213', 'entidad_emisora' => 'Mapfre', 'fecha_emision' => $hoy->copy()->subMonths(11), 'fecha_vencimiento' => $hoy->copy()->addDays(20), 'costo' => 12500]
        );

        $aceite = TipoMantencion::where('nombre', 'Cambio de aceite y filtro')->first();
        $frenos = TipoMantencion::where('nombre', 'Revisión / cambio de frenos')->first();
        $rotacion = TipoMantencion::where('nombre', 'Rotación de neumáticos')->first();

        Mantencion::query()->updateOrCreate(
            ['vehiculo_id' => $rpxk->id, 'tipo_mantencion_id' => $aceite->id],
            ['intervalo_km' => 5000, 'intervalo_meses' => 6, 'km_ultima' => 35500, 'fecha_ultima' => $hoy->copy()->subMonths(3)]
        );
        Mantencion::query()->updateOrCreate(
            ['vehiculo_id' => $rpxk->id, 'tipo_mantencion_id' => $frenos->id],
            ['intervalo_km' => 20000, 'intervalo_meses' => 12, 'km_ultima' => 20100, 'fecha_ultima' => $hoy->copy()->subMonths(6)]
        );
        Mantencion::query()->updateOrCreate(
            ['vehiculo_id' => $fktr->id, 'tipo_mantencion_id' => $aceite->id],
            ['intervalo_km' => 5000, 'intervalo_meses' => 6, 'km_ultima' => 59000, 'fecha_ultima' => $hoy->copy()->subMonths(2)]
        );
        Mantencion::query()->updateOrCreate(
            ['vehiculo_id' => $fktr->id, 'tipo_mantencion_id' => $rotacion->id],
            ['intervalo_km' => 10000, 'intervalo_meses' => 6, 'km_ultima' => 52300, 'fecha_ultima' => $hoy->copy()->subMonths(4)]
        );
        Mantencion::query()->updateOrCreate(
            ['vehiculo_id' => $bwsz->id, 'tipo_mantencion_id' => $aceite->id],
            ['intervalo_km' => 5000, 'intervalo_meses' => 6, 'km_ultima' => 76000, 'fecha_ultima' => $hoy->copy()->subMonths(5)]
        );

        HistorialMantencion::query()->firstOrCreate(
            ['vehiculo_id' => $rpxk->id, 'tipo_mantencion_id' => $aceite->id, 'fecha_realizada' => $hoy->copy()->subMonths(3)],
            ['km_realizada' => 35500, 'costo' => 45000, 'taller' => 'Taller Municipal', 'observaciones' => 'Aceite 5W30 + filtro original']
        );
        HistorialMantencion::query()->firstOrCreate(
            ['vehiculo_id' => $fktr->id, 'tipo_mantencion_id' => $aceite->id, 'fecha_realizada' => $hoy->copy()->subMonths(2)],
            ['km_realizada' => 59000, 'costo' => 42000, 'taller' => 'Automotora Iquique']
        );
        HistorialMantencion::query()->firstOrCreate(
            ['vehiculo_id' => $bwsz->id, 'tipo_mantencion_id' => $aceite->id, 'fecha_realizada' => $hoy->copy()->subMonths(5)],
            ['km_realizada' => 76000, 'costo' => 40000, 'taller' => 'Taller Municipal']
        );
    }
}
