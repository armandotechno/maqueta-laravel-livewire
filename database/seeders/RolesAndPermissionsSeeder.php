<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Limpiar caché de permisos de Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        Permission::create(['name' => 'editar artículos']);
        Permission::create(['name' => 'eliminar artículos']);
        Permission::create(['name' => 'administrar usuarios']);

        // Crear roles y asignar permisos
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleAdmin->givePermissionTo(Permission::all());

        $roleUser = Role::create(['name' => 'user']);
        $roleUser->givePermissionTo('editar artículos');
    }
}
