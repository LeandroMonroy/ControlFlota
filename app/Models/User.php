<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'apellido_paterno',
        'apellido_materno',
        'email',
        'password',
        'rol',
        'direccion',
    ];

    const ROLES = ['Administrador', 'Encargado', 'Mecánico', 'Administrativo'];

    const DIRECCIONES = [
        'Dirección Medio Ambiente Aseo y Ornato',
        'Dirección de Seguridad Pública y Emergencia',
        'SECPLAC',
        'Dirección de Obras Municipales',
        'Dirección Administración y Finanzas',
    ];

    protected function nombreCompleto(): Attribute
    {
        return Attribute::get(
            fn () => trim("{$this->name} {$this->apellido_paterno} {$this->apellido_materno}")
        );
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
