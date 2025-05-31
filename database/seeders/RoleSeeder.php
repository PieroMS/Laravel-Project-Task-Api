<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definir roles

        $superadmin = Role::create(['name' => 'jefe', 'guard_name' => 'api']);
        $admin = Role::create(['name' => 'administrador', 'guard_name' => 'api']);
        $client = Role::create(['name' => 'client', 'guard_name' => 'api']);

        // Crear permisos y asignar a roles
        // El rol superadmin ya tiene permisos por defecto
        Permission::create(['name' => 'index', 'guard_name' => 'api'])->syncRoles([$superadmin, $admin, $client]);
        Permission::create(['name' => 'show', 'guard_name' => 'api'])->syncRoles([$superadmin, $admin, $client]);
        Permission::create(['name' => 'create', 'guard_name' => 'api'])->syncRoles([$superadmin, $admin]);
        Permission::create(['name' => 'update', 'guard_name' => 'api'])->syncRoles([$superadmin, $admin]);
        Permission::create(['name' => 'destroy', 'guard_name' => 'api'])->assignRole($superadmin);
    }
}
