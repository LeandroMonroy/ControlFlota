<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('vehiculos.editar', fn (User $user) => in_array($user->rol, ['Administrador', 'Encargado']));
        Gate::define('documentos.editar', fn (User $user) => in_array($user->rol, ['Administrador', 'Encargado']));
        Gate::define('mantenciones.editar', fn (User $user) => in_array($user->rol, ['Administrador', 'Encargado', 'Mecánico']));

        // Eliminar registros (en cualquier módulo) y gestionar Usuarios: solo Administrador.
        Gate::define('administrador', fn (User $user) => $user->rol === 'Administrador');
    }
}
