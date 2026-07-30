<section>
    <header class="mb-3">
        <h5>Información de perfil</h5>
        <p class="text-secondary small mb-0">Actualiza tu nombre y correo electrónico.</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="mb-3">
            <x-input-label for="name" value="Nombre" />
            <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div class="mb-3">
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="d-flex align-items-center gap-3">
            <x-primary-button>Guardar</x-primary-button>

            @if (session('status') === 'profile-updated')
                <span class="text-success small">Guardado.</span>
            @endif
        </div>
    </form>
</section>
