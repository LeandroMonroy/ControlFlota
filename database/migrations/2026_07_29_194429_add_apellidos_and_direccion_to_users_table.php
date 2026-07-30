<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('apellido_paterno', 100)->nullable()->after('name');
            $table->string('apellido_materno', 100)->nullable()->after('apellido_paterno');
            $table->enum('direccion', [
                'Dirección Medio Ambiente Aseo y Ornato',
                'Dirección de Seguridad Pública y Emergencia',
                'SECPLAC',
                'Dirección de Obras Municipales',
                'Dirección Administración y Finanzas',
            ])->nullable()->after('rol');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['apellido_paterno', 'apellido_materno', 'direccion']);
        });
    }
};
