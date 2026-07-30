<section>
    <header class="mb-3">
        <h5>Eliminar cuenta</h5>
        <p class="text-secondary small mb-0">
            Una vez eliminada la cuenta, no podrás volver a iniciar sesión con ella. Esta acción no se puede deshacer.
        </p>
    </header>

    <x-danger-button type="button" data-bs-toggle="modal" data-bs-target="#confirmUserDeletion">
        Eliminar cuenta
    </x-danger-button>

    <div class="modal fade" id="confirmUserDeletion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="{{ route('profile.destroy') }}" class="modal-content">
                @csrf
                @method('delete')
                <div class="modal-header">
                    <h5 class="modal-title">¿Eliminar tu cuenta?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-secondary small">
                        Ingresa tu contraseña para confirmar que deseas eliminar tu cuenta de forma permanente.
                    </p>
                    <x-input-label for="delete_password" value="Contraseña" class="visually-hidden" />
                    <x-text-input id="delete_password" name="password" type="password" placeholder="Contraseña" />
                    <x-input-error :messages="$errors->userDeletion->get('password')" />
                </div>
                <div class="modal-footer">
                    <x-secondary-button data-bs-dismiss="modal">Cancelar</x-secondary-button>
                    <x-danger-button>Eliminar cuenta</x-danger-button>
                </div>
            </form>
        </div>
    </div>
</section>
