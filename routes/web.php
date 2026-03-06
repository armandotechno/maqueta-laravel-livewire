<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// 1. Ruta pública (Landing)
Route::view('/', 'welcome')->name('home');

// 2. Rutas protegidas (Requieren Login)
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard Principal
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // ---------------------------------------------------------
    // Módulo de Administración: Protegido SOLO para el rol 'admin'
    // ---------------------------------------------------------
    Route::middleware(['role:admin'])->group(function () {

        // Vista principal de la tabla
        Route::view('usuarios', 'admin.users')->name('admin.users');

        // [CREAR] Lógica para guardar un nuevo usuario
        Route::post('usuarios/guardar', function (Request $request) {
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

            return back()->with('status', '¡Usuario creado con éxito!');
        })->name('admin.users.store');

        // [EDITAR/ACTUALIZAR] Lógica para modificar un usuario existente
        Route::put('usuarios/{user}', function (Request $request, User $user) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            ]);

            $user->update($request->only('name', 'email'));

            // Si el admin puso una nueva contraseña, la actualizamos
            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            return back()->with('status', 'Usuario actualizado correctamente.');
        })->name('admin.users.update');

        Route::put('usuarios/{user}/roles', function (Request $request, User $user) {
            // Sincroniza los roles seleccionados (borra los viejos y pone los nuevos)
            $user->syncRoles($request->roles ?? []);

            return back()->with('status', 'Roles actualizados con éxito.');
        })->name('admin.users.roles.update');

        Route::delete('usuarios/{user}', function (User $user) {
            // Evitamos que te borres a ti mismo por accidente
            if (auth()->id() === $user->id) {
                return back()->with('error', 'No puedes eliminar tu propia cuenta.');
            }

            $user->delete();
            return back()->with('status', 'Usuario eliminado con éxito.');
        })->name('admin.users.destroy');
    });
});

require __DIR__ . '/settings.php';
