<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mantenciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->cascadeOnDelete();
            $table->foreignId('tipo_mantencion_id')->constrained('tipos_mantencion');
            $table->unsignedInteger('intervalo_km')->nullable()->comment('Override del intervalo en km para este vehículo');
            $table->unsignedSmallInteger('intervalo_meses')->nullable()->comment('Override del intervalo en meses para este vehículo');
            $table->unsignedInteger('km_ultima')->nullable();
            $table->date('fecha_ultima')->nullable();
            $table->boolean('activo')->default(true);
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->unique(['vehiculo_id', 'tipo_mantencion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mantenciones');
    }
};
