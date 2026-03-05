<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    // Tu dashboard normal
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Módulo de Administración: Protegido SOLO para el rol 'admin'
    Route::middleware(['role:admin'])->group(function () {

        // 1. Ruta para la gestión de usuarios (apunta al componente Livewire que creamos)
        Route::view('/users/manage', 'livewire.users.manage')->name('users.manage');

        // 2. Ruta temporal para la gestión de roles (para que Flux pueda generar el enlace sin errores)
        Route::get('/roles/manage', function () {
            return 'Módulo de roles en construcción...';
        })->name('roles.manage');
    });
});

require __DIR__ . '/settings.php';
