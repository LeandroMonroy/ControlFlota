<x-app-layout>
    <x-slot name="header">
        <div class="view-header">
            <div>
                <h2>Mi cuenta</h2>
                <p class="sub">Datos de acceso del usuario administrador.</p>
            </div>
        </div>
    </x-slot>

    <div class="row g-3">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card mb-3">
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card border-danger-subtle">
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
