<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    // Tu dashboard normal
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Módulo de Administración: Protegido SOLO para el rol 'admin'
    Route::middleware(['role:admin'])->group(function () {

        // 1. Ruta para la gestión de usuarios (apunta al componente Livewire que creamos)
        Route::view('usuarios', 'admin.users')->name('admin.users');

        // 2. Ruta temporal para la gestión de roles (para que Flux pueda generar el enlace sin errores)
        Route::view('roles', 'admin.roles')->name('admin.roles');
    });
});

require __DIR__ . '/settings.php';
