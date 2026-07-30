<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_mantencion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 80)->unique();
            $table->unsignedInteger('intervalo_km_defecto')->nullable();
            $table->unsignedSmallInteger('intervalo_meses_defecto')->nullable();
            $table->string('descripcion', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_mantencion');
    }
};
