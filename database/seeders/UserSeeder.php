<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Usuarios por defecto, uno por rol. Cambiar las contraseñas desde
     * "Mi cuenta" después del primer inicio de sesión.
     */
    public function run(): void
    {
        $usuarios = [
            [
                'email' => 'admin@flotadspe.cl', 'name' => 'Carlos', 'apellido_paterno' => 'Pizarro', 'apellido_materno' => 'Reyes',
                'rol' => 'Administrador', 'direccion' => 'Dirección de Seguridad Pública y Emergencia', 'password' => 'FlotaDSPE#2026',
            ],
            [
                'email' => 'encargado@flotadspe.cl', 'name' => 'María', 'apellido_paterno' => 'González', 'apellido_materno' => 'Tapia',
                'rol' => 'Encargado', 'direccion' => 'Dirección de Seguridad Pública y Emergencia', 'password' => 'FlotaDSPE#2026',
            ],
            [
                'email' => 'mecanico@flotadspe.cl', 'name' => 'Luis', 'apellido_paterno' => 'Vargas', 'apellido_materno' => 'Soto',
                'rol' => 'Mecánico', 'direccion' => 'Dirección de Obras Municipales', 'password' => 'FlotaDSPE#2026',
            ],
            [
                'email' => 'administrativo@flotadspe.cl', 'name' => 'Andrea', 'apellido_paterno' => 'Rojas', 'apellido_materno' => 'Muñoz',
                'rol' => 'Administrativo', 'direccion' => 'Dirección Administración y Finanzas', 'password' => 'FlotaDSPE#2026',
            ],
        ];

        foreach ($usuarios as $u) {
            User::query()->updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'apellido_paterno' => $u['apellido_paterno'],
                    'apellido_materno' => $u['apellido_materno'],
                    'rol' => $u['rol'],
                    'direccion' => $u['direccion'],
                    'password' => $u['password'],
                ]
            );
        }
    }
}
