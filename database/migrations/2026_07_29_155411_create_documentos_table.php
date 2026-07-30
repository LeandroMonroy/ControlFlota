<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->cascadeOnDelete();
            $table->enum('tipo_documento', [
                'Permiso de Circulación',
                'Revisión Técnica',
                'Seguro Obligatorio (SOAP)',
                'Seguro Adicional',
                'Certificado de Emisiones',
                'Padrón / Inscripción',
                'Otro',
            ]);
            $table->string('numero_documento', 100)->nullable();
            $table->string('entidad_emisora', 100)->nullable();
            $table->date('fecha_emision')->nullable();
            $table->date('fecha_vencimiento');
            $table->decimal('costo', 10, 2)->nullable();
            $table->string('archivo_url')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index('fecha_vencimiento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
