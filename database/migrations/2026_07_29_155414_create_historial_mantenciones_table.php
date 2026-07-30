<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_mantenciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->cascadeOnDelete();
            $table->foreignId('tipo_mantencion_id')->constrained('tipos_mantencion');
            $table->date('fecha_realizada');
            $table->unsignedInteger('km_realizada');
            $table->decimal('costo', 10, 2)->nullable();
            $table->string('taller', 100)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_mantenciones');
    }
};
