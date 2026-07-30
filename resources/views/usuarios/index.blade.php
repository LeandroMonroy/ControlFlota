@php
    $rolBadge = [
        'Administrador' => 'primary',
        'Encargado' => 'info',
        'Mecánico' => 'warning',
        'Administrativo' => 'secondary',
    ];
@endphp
<x-app-layout>
    <div class="view-header">
        <div>
            <h2>Usuarios</h2>
            <p class="sub">Cuentas de acceso al sistema y sus roles.</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUsuarioCreate">
            + Nuevo usuario
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white">
            <thead>
                <tr><th>Nombre</th><th>Correo</th><th>Rol</th><th>Dirección</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($usuarios as $u)
                    <tr>
                        <td>{{ $u->nombre_completo }} @if ($u->is(auth()->user())) <span class="badge bg-secondary-subtle text-secondary">tú</span> @endif</td>
                        <td class="font-mono">{{ $u->email }}</td>
                        <td><span class="badge bg-{{ $rolBadge[$u->rol] ?? 'secondary' }}">{{ $u->rol }}</span></td>
                        <td>{{ $u->direccion ?: '—' }}</td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-secondary" title="Editar" data-bs-toggle="modal" data-bs-target="#modalUsuarioEdit{{ $u->id }}">✎</button>
                            @unless ($u->is(auth()->user()))
                                <button type="submit" form="deleteUsuario{{ $u->id }}" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Eliminar el usuario {{ $u->nombre_completo }}?')">✕</button>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty-state">Sin usuarios registrados.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @foreach ($usuarios as $u)
        @unless ($u->is(auth()->user()))
            <form id="deleteUsuario{{ $u->id }}" method="POST" action="{{ route('usuarios.destroy', $u) }}" class="d-none">
                @csrf @method('DELETE')
            </form>
        @endunless
    @endforeach

    {{-- Modal: nuevo usuario --}}
    <div class="modal fade" id="modalUsuarioCreate" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('usuarios.store') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('usuarios._campos')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar usuario</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modales: editar usuario --}}
    @foreach ($usuarios as $u)
        <div class="modal fade" id="modalUsuarioEdit{{ $u->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('usuarios.update', $u) }}" class="modal-content">
                    @csrf @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Editar usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @include('usuarios._campos', ['usuario' => $u])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar usuario</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
</x-app-layout>
