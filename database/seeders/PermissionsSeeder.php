<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $timestamp = '2025-11-07 21:46:25';

        $permissions = [
            ['id' => 1, 'name' => 'view_any_company'],
            ['id' => 2, 'name' => 'view_company'],
            ['id' => 3, 'name' => 'create_company'],
            ['id' => 4, 'name' => 'update_company'],
            ['id' => 5, 'name' => 'delete_company'],
            ['id' => 6, 'name' => 'restore_company'],
            ['id' => 7, 'name' => 'force_delete_company'],
            ['id' => 8, 'name' => 'force_delete_any_company'],
            ['id' => 9, 'name' => 'restore_any_company'],
            ['id' => 10, 'name' => 'replicate_company'],
            ['id' => 11, 'name' => 'reorder_company'],

            ['id' => 12, 'name' => 'view_any_driver'],
            ['id' => 13, 'name' => 'view_driver'],
            ['id' => 14, 'name' => 'create_driver'],
            ['id' => 15, 'name' => 'update_driver'],
            ['id' => 16, 'name' => 'delete_driver'],
            ['id' => 17, 'name' => 'restore_driver'],
            ['id' => 18, 'name' => 'force_delete_driver'],
            ['id' => 19, 'name' => 'force_delete_any_driver'],
            ['id' => 20, 'name' => 'restore_any_driver'],
            ['id' => 21, 'name' => 'replicate_driver'],
            ['id' => 22, 'name' => 'reorder_driver'],

            ['id' => 23, 'name' => 'view_any_user'],
            ['id' => 24, 'name' => 'view_user'],
            ['id' => 25, 'name' => 'create_user'],
            ['id' => 26, 'name' => 'update_user'],
            ['id' => 27, 'name' => 'delete_user'],
            ['id' => 28, 'name' => 'restore_user'],
            ['id' => 29, 'name' => 'force_delete_user'],
            ['id' => 30, 'name' => 'force_delete_any_user'],
            ['id' => 31, 'name' => 'restore_any_user'],
            ['id' => 32, 'name' => 'replicate_user'],
            ['id' => 33, 'name' => 'reorder_user'],

            ['id' => 34, 'name' => 'view_any_role'],
            ['id' => 35, 'name' => 'view_role'],
            ['id' => 36, 'name' => 'create_role'],
            ['id' => 37, 'name' => 'update_role'],
            ['id' => 38, 'name' => 'delete_role'],
            ['id' => 39, 'name' => 'restore_role'],
            ['id' => 40, 'name' => 'force_delete_role'],
            ['id' => 41, 'name' => 'force_delete_any_role'],
            ['id' => 42, 'name' => 'restore_any_role'],
            ['id' => 43, 'name' => 'replicate_role'],
            ['id' => 44, 'name' => 'reorder_role'],

            ['id' => 45, 'name' => 'view_any_activity'],
            ['id' => 46, 'name' => 'view_activity'],
            ['id' => 47, 'name' => 'create_activity'],
            ['id' => 48, 'name' => 'update_activity'],
            ['id' => 49, 'name' => 'delete_activity'],
            ['id' => 50, 'name' => 'restore_activity'],
            ['id' => 51, 'name' => 'force_delete_activity'],
            ['id' => 52, 'name' => 'force_delete_any_activity'],
            ['id' => 53, 'name' => 'restore_any_activity'],
            ['id' => 54, 'name' => 'replicate_activity'],
            ['id' => 55, 'name' => 'reorder_activity'],

            ['id' => 56, 'name' => 'view_my_profile_page'],
            ['id' => 57, 'name' => 'view_company_stats_overview'],

            ['id' => 58, 'name' => 'view_any_truck'],
            ['id' => 59, 'name' => 'view_truck'],
            ['id' => 60, 'name' => 'create_truck'],
            ['id' => 61, 'name' => 'update_truck'],
            ['id' => 62, 'name' => 'delete_truck'],
            ['id' => 63, 'name' => 'restore_truck'],
            ['id' => 64, 'name' => 'force_delete_truck'],
            ['id' => 65, 'name' => 'force_delete_any_truck'],
            ['id' => 66, 'name' => 'restore_any_truck'],
            ['id' => 67, 'name' => 'replicate_truck'],
            ['id' => 68, 'name' => 'reorder_truck'],

            ['id' => 69, 'name' => 'view_any_chassis'],
            ['id' => 70, 'name' => 'view_chassis'],
            ['id' => 71, 'name' => 'create_chassis'],
            ['id' => 72, 'name' => 'update_chassis'],
            ['id' => 73, 'name' => 'delete_chassis'],
            ['id' => 74, 'name' => 'restore_chassis'],
            ['id' => 75, 'name' => 'force_delete_chassis'],
            ['id' => 76, 'name' => 'force_delete_any_chassis'],
            ['id' => 77, 'name' => 'restore_any_chassis'],
            ['id' => 78, 'name' => 'replicate_chassis'],
            ['id' => 79, 'name' => 'reorder_chassis'],
        ];

        foreach ($permissions as &$p) {
            $p['guard_name'] = 'web';
            $p['created_at'] = $timestamp;
            $p['updated_at'] = $timestamp;
        }

        DB::table('permissions')->insert($permissions);
    }
}
