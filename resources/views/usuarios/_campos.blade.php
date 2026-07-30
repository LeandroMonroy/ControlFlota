@php $usuario = $usuario ?? null; @endphp
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Nombres</label>
        <input type="text" name="name" required class="form-control" value="{{ old('name', $usuario->name ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Apellido paterno</label>
        <input type="text" name="apellido_paterno" required class="form-control" value="{{ old('apellido_paterno', $usuario->apellido_paterno ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Apellido materno</label>
        <input type="text" name="apellido_materno" required class="form-control" value="{{ old('apellido_materno', $usuario->apellido_materno ?? '') }}">
    </div>
    <div class="col-12">
        <label class="form-label">Correo electrónico</label>
        <input type="email" name="email" required class="form-control" value="{{ old('email', $usuario->email ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Rol</label>
        <select name="rol" required class="form-select">
            @foreach (\App\Models\User::ROLES as $r)
                <option @selected(old('rol', $usuario->rol ?? 'Encargado') === $r)>{{ $r }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Dirección</label>
        <select name="direccion" required class="form-select">
            <option value="">Seleccionar…</option>
            @foreach (\App\Models\User::DIRECCIONES as $d)
                <option @selected(old('direccion', $usuario->direccion ?? '') === $d)>{{ $d }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">
            Contraseña
            @if ($usuario) <span class="text-secondary small">— dejar en blanco para no cambiarla</span> @endif
        </label>
        <input type="password" name="password" class="form-control" autocomplete="new-password" {{ $usuario ? '' : 'required' }} minlength="8">
    </div>
</div>
