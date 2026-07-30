<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('ppu', 10)->unique()->comment('Placa patente única');
            $table->enum('tipo', ['Automóvil', 'Camioneta', 'Furgón', 'Motocicleta', 'Camión', 'Bus', 'Cuatrimoto', 'Otro'])->default('Camioneta');
            $table->string('marca', 50);
            $table->string('modelo', 50);
            $table->unsignedSmallInteger('anio');
            $table->string('numero_motor', 60)->nullable();
            $table->string('numero_chasis', 60)->nullable();
            $table->string('color', 30)->nullable();
            $table->unsignedInteger('kilometraje_actual')->default(0);
            $table->date('fecha_kilometraje')->nullable();
            $table->string('area_asignada', 80)->nullable();
            $table->string('responsable', 80)->nullable();
            $table->enum('estado', ['Operativo', 'En mantención', 'Fuera de servicio', 'Dado de baja'])->default('Operativo');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
