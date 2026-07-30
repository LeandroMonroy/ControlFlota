<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ControlFlota') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body class="d-flex flex-column align-items-center justify-content-center min-vh-100" style="background: linear-gradient(180deg, #071429, #0b1e3d 60%);">
        <div class="d-flex flex-column align-items-center mb-4 text-center">
            <img src="{{ asset('images/logo-pozo-almonte.png') }}" alt="Ilustre Municipalidad de Pozo Almonte" style="width:88px;height:auto;margin-bottom:10px;">
            <h1 class="h5 text-white mb-0" style="font-family:'Space Grotesk',sans-serif;">ControlFlota</h1>
            <p class="small mb-0" style="color:#8fa6c9;">Ilustre Municipalidad de Pozo Almonte</p>
        </div>

        <div class="card shadow-sm border-0" style="width:100%; max-width: 400px;">
            <div class="card-body p-4">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
