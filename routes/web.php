<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController; // Asegúrate de que este controlador exista
use Illuminate\Support\Facades\Route;

// 1. Ruta pública
Route::view('/', 'welcome')->name('home');

// 2. Rutas protegidas
Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Definimos profile.edit manualmente para que el componente de Flux no de error
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Módulo de Administración (Solo Admin)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('usuarios', [UserController::class, 'index'])->name('admin.users');
        Route::post('usuarios', [UserController::class, 'store'])->name('admin.users.store');
        Route::put('usuarios/{user}/roles', [UserController::class, 'updateRoles'])->name('admin.users.roles.update');
        Route::delete('usuarios/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });

    // Ruta de Logout manual ya que no tienes breeze/auth.php
    Route::post('logout', function () {
        auth()->logout();
        return redirect('/');
    })->name('logout');
});

// Carga solo lo que sí existe en tu carpeta routes/
if (file_exists(__DIR__ . '/settings.php')) {
    require __DIR__ . '/settings.php';
}
