<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ControlFlota') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body>
        <div class="app-shell">

            <aside class="sidebar">
                <div class="brand">
                    <img src="{{ asset('images/logo-pozo-almonte.png') }}" alt="Ilustre Municipalidad de Pozo Almonte" class="brand-logo">
                    <h1>ControlFlota</h1>
                    <p>Ilustre Municipalidad de Pozo Almonte</p>
                </div>

                <nav class="sidebar-nav">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="dot"></span> Panel general
                    </a>
                    <a href="{{ route('vehiculos.index') }}" class="nav-link {{ request()->routeIs('vehiculos.*') ? 'active' : '' }}">
                        <span class="dot"></span> Vehículos
                    </a>
                    <a href="{{ route('documentos.index') }}" class="nav-link {{ request()->routeIs('documentos.*') ? 'active' : '' }}">
                        <span class="dot"></span> Documentación
                    </a>
                    <a href="{{ route('mantenciones.index') }}" class="nav-link {{ request()->routeIs('mantenciones.*') ? 'active' : '' }}">
                        <span class="dot"></span> Mantenciones
                    </a>
                    <a href="{{ route('historial.index') }}" class="nav-link {{ request()->routeIs('historial.*') ? 'active' : '' }}">
                        <span class="dot"></span> Historial
                    </a>
                    <a href="{{ route('informes.index') }}" class="nav-link {{ request()->routeIs('informes.*') ? 'active' : '' }}">
                        <span class="dot"></span> Informes
                    </a>
                    @can('administrador')
                        <a href="{{ route('usuarios.index') }}" class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                            <span class="dot"></span> Usuarios
                        </a>
                    @endcan
                    <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <span class="dot"></span> Mi cuenta
                    </a>
                </nav>

                <div class="sidebar-foot">
                    {{ auth()->user()->nombre_completo }}
                    <div class="small" style="color:#64789e;">{{ auth()->user()->rol }}</div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-1">
                        @csrf
                        <button type="submit" class="btn btn-link btn-sm text-decoration-none p-0" style="color:#8fa6c9;">Cerrar sesión</button>
                    </form>
                </div>
            </aside>

            <main class="main-content">
                @isset($header)
                    <div class="mb-3">{{ $header }}</div>
                @endisset

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{ $slot }}
            </main>

        </div>
    </body>
</html>
