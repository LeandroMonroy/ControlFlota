<x-guest-layout>
    <div class="mb-3 small text-secondary">
        ¿Olvidaste tu contraseña? Indica tu correo y te enviaremos un enlace para restablecerla.
    </div>

    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="d-flex justify-content-end">
            <x-primary-button>Enviar enlace de recuperación</x-primary-button>
        </div>
    </form>
</x-guest-layout>
