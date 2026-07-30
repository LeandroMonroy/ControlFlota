# ControlFlota — Gestión de Mantenciones de Flota Vehicular (Laravel + Bootstrap)

Ilustre Municipalidad de Pozo Almonte

Rediseño con Laravel 12 (Blade) + Bootstrap 5 del prototipo original
(`../flota_dspe`, HTML + JS + PHP plano). Mismo alcance funcional:
fichas técnicas de vehículos, documentos con vencimiento, plan de
mantenciones preventivas por km/tiempo e historial de mantenciones.

## Requisitos

- PHP 8.2+ con `pdo_mysql` (incluido en XAMPP)
- MySQL (XAMPP)
- Node.js + npm (para compilar Bootstrap/JS con Vite)
- Composer — este proyecto usa un `composer.phar` local en `C:\xampp\htdocs\composer.phar`
  (no está instalado globalmente en el sistema)

## Instalación

```bash
cd C:\xampp\htdocs\ControlFlota

# Dependencias
php ../composer.phar install
npm install

# Variables de entorno (ya configurado en .env: MySQL, BD controlflota)
# Crear la base de datos si no existe:
#   CREATE DATABASE controlflota CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Migraciones + datos de ejemplo (usuario admin, catálogo de mantenciones, 3 vehículos demo)
php artisan migrate --seed

# Compilar assets (Bootstrap 5 + JS)
npm run build

# Levantar el servidor de desarrollo
php artisan serve
```

Abrir **http://127.0.0.1:8000**.

## Acceso y roles

No hay registro público. `UserSeeder` crea un usuario por rol (misma
contraseña `FlotaDSPE#2026` para los cuatro, pensada solo para pruebas —
cambiarla desde "Mi cuenta" después del primer inicio de sesión):

| Rol | Correo | Puede |
|---|---|---|
| Administrador | `admin@flotadspe.cl` | Todo, incluida la gestión de Usuarios y eliminar registros |
| Encargado | `encargado@flotadspe.cl` | Crear/editar Vehículos, Documentos y Mantenciones (no elimina, no ve Usuarios) |
| Mecánico | `mecanico@flotadspe.cl` | Crear/editar Mantenciones (incluye registrar mantención realizada); solo lectura en Vehículos/Documentos |
| Administrativo | `administrativo@flotadspe.cl` | Solo lectura en todo el sistema; sección Informes para ver/imprimir |

La autorización está definida como Gates en
`app/Providers/AppServiceProvider.php` (`vehiculos.editar`,
`documentos.editar`, `mantenciones.editar`, `administrador`) y aplicada
como middleware `can:` en `routes/web.php` — no solo se ocultan botones
en las vistas, las rutas también rechazan la acción (403) si el rol no
corresponde.

## Estructura

```
app/
├── Models/            → Vehiculo, Documento, TipoMantencion, Mantencion, HistorialMantencion, User (rol)
├── Support/NivelEstado.php → cálculo de nivel de alerta (vencido/urgente/proximo/ok)
├── Providers/AppServiceProvider.php → Gates de autorización por rol
└── Http/
    ├── Controllers/   → Dashboard, Vehiculo, Documento, Mantencion, HistorialMantencion, Usuario, Informe
    └── Requests/      → validación de formularios

database/
├── migrations/        → esquema (vehiculos, documentos, tipos_mantencion, mantenciones, historial_mantenciones)
└── seeders/           → UserSeeder, TiposMantencionSeeder, FlotaDemoSeeder

public/
└── images/logo-pozo-almonte.png → logo institucional (sidebar y login)

resources/
├── sass/               → Bootstrap 5 + paleta de marca (navy/oro/cian) en _variables.scss
└── views/
    ├── layouts/app.blade.php → shell con sidebar (todas las páginas autenticadas)
    ├── dashboard.blade.php
    ├── vehiculos/, documentos/, mantenciones/, historial/, usuarios/
    ├── informes/          → vistas imprimibles (@media print en app.scss)
    └── auth/, profile/  → login y cuenta (Laravel Breeze, restyleado a Bootstrap)
```

## Notas de diseño

- Sin SPA/API: cada sección es una ruta y controlador Laravel reales
  (Blade multi-página), con modales Bootstrap para crear/editar.
- Los niveles de alerta (vencido/urgente/próximo/ok) se calculan en
  `App\Support\NivelEstado` y como accessors en los modelos `Documento`
  y `Mantencion`, replicando la lógica del prototipo original
  (antes en `js/app.js` y `api/dashboard.php`).
- "Registrar mantención realizada" (`HistorialMantencionController@store`)
  reproduce la transacción original: guarda el historial, reinicia el
  plan de mantención y sube el kilometraje del vehículo si corresponde.
