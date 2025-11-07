<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $timestamp = '2025-11-07 21:35:49';

        /*
        |--------------------------------------------------------------------------
        | ROLES
        |--------------------------------------------------------------------------
        */

        DB::table('roles')->insert([
            [
                'id'         => 1,
                'name'       => 'super_admin',
                'guard_name' => 'web',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | MODEL HAS ROLES  (asignar rol al usuario ID = 1)
        |--------------------------------------------------------------------------
        */

        DB::table('model_has_roles')->insert([
            [
                'role_id'    => 1,
                'model_type' => 'App\\Models\\User',
                'model_id'   => 1,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | ROLE HAS PERMISSIONS (rol super_admin con 57 permisos)
        |--------------------------------------------------------------------------
        */

        $permissions = range(1, 79); // IDs 1 al 79

        $rolePermissions = array_map(function ($permId) {
            return [
                'permission_id' => $permId,
                'role_id'       => 1,
            ];
        }, $permissions);

        DB::table('role_has_permissions')->insert($rolePermissions);
    }
}
