<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('status', 'Usuario creado con éxito');
    }

    public function updateRoles(Request $request, User $user)
    {
        $user->syncRoles($request->roles ?? []);
        return back()->with('status', 'Roles actualizados');
    }

    public function destroy(User $user)
    {
        // Verificación de seguridad para el proyecto del TSJ
        if (auth()->id() === $user->id) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();
        return back()->with('status', 'Usuario eliminado con éxito.');
    }
}
