<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Creamos al usuario con la contraseña 123456 encriptada
        $admin = User::create([
            'name'     => 'Administrador Sistema',
            'email'    => 'admin@admin.com',
            'password' => Hash::make('123456'), // Encriptación segura de Laravel
        ]);

        // 2. Le asignamos el rol de admin (Asegúrate de haber corrido RolesAndPermissionsSeeder antes)
        $role = Role::where('name', 'admin')->first();

        if ($role) {
            $admin->assignRole($role);
        }
    }
}
