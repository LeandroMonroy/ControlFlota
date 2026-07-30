<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    public function index(): View
    {
        return view('usuarios.index', [
            'usuarios' => User::orderBy('name')->get(),
        ]);
    }

    public function store(UsuarioRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado.');
    }

    public function update(UsuarioRequest $request, User $usuario): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        if ($usuario->is($request->user()) && $data['rol'] !== 'Administrador' && $usuario->rol === 'Administrador') {
            return back()->with('error', 'No puedes quitarte a ti mismo el rol de Administrador.');
        }

        $usuario->update($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy(User $usuario): RedirectResponse
    {
        if ($usuario->is(auth()->user())) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        if ($usuario->rol === 'Administrador' && User::where('rol', 'Administrador')->count() <= 1) {
            return back()->with('error', 'Debe existir al menos un usuario Administrador.');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado.');
    }
}
