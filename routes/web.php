<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController; // Asegúrate de que este controlador exista
use App\Livewire\Auth\ChangePassword; // <-- IMPORTANTE: Agregamos esta línea para el componente de cambio de clave
use Illuminate\Support\Facades\Route;

// 1. Ruta pública
Route::view('/', 'pages.auth.login')->name('home');

// 2. Rutas protegidas
Route::middleware(['auth', 'verified'])->group(function () {

    // ====================================================================
    // ZONA BLOQUEADA: El usuario no pasa de aquí si debe cambiar su clave
    // ====================================================================
    Route::middleware(['force_password'])->group(function () {

        Route::view('dashboard', 'dashboard')->name('dashboard');

        // Módulo de Administración (Solo Admin)
        Route::middleware(['role:admin'])->group(function () {
            Route::get('usuarios', [UserController::class, 'index'])->name('admin.users');
            Route::post('usuarios', [UserController::class, 'store'])->name('admin.users.store');
            Route::put('usuarios/{user}/roles', [UserController::class, 'updateRoles'])->name('admin.users.roles.update');
            Route::delete('usuarios/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        });
    }); // Fin de Zona Bloqueada

    // ====================================================================
    // ZONA LIBRE: Accesible incluso si el usuario necesita cambiar su clave
    // ====================================================================

    // Ruta obligatoria para cambiar la clave (El guardia te redirige aquí)
    Route::get('/cambiar-clave-obligatorio', ChangePassword::class)->name('password.force-change');

    // Ruta de Logout manual (Para que el usuario pueda salir si se arrepiente)
    Route::post('logout', function () {
        auth()->logout();
        return redirect('/');
    })->name('logout');
});

// Carga solo lo que sí existe en tu carpeta routes/
if (file_exists(__DIR__ . '/settings.php')) {
    require __DIR__ . '/settings.php';
}
