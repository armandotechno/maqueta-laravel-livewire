<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Persona;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usamos una transacción para asegurarnos de que se creen ambos o ninguno
        DB::transaction(function () {

            // 1. Creamos la Persona (Madre del usuario)
            $persona = Persona::create([
                'cedula'           => 'V-00000000',
                'primer_nombre'    => 'ADMINISTRADOR',
                'primer_apellido'  => 'SISTEMA',
                'fecha_nacimiento' => '1990-01-01',
                'sexo'             => 'M',
                'estado'           => 'CARACAS',
            ]);

            // 2. Creamos al usuario vinculado a esa persona
            $admin = User::create([
                'persona_id' => $persona->id, // FK obligatoria
                'name'       => $persona->primer_nombre . ' ' . $persona->primer_apellido,
                'email'      => 'admin@admin.com',
                'needs_password_change' => false,
                'password'   => Hash::make('123456'),
            ]);

            // 3. Asignación del rol de Spatie
            $role = Role::where('name', 'admin')->first();

            if ($role) {
                $admin->assignRole($role);
            }
        });
    }
}
