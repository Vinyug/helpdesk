<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'all-access',
            'company-create',
            'company-delete', 
            'company-edit',
            'company-list',
            'job-create',
            'job-delete',
            'job-edit',
            'job-list',
            'role-create',
            'role-delete',
            'role-edit',
            'role-list',
            'service-create',
            'service-delete',
            'service-edit',
            'service-list',
            'state-create',
            'state-delete',
            'state-edit',
            'state-list',
            'ticket-create',
            'ticket-delete',
            'ticket-edit',
            'ticket-list',
            'user-create',
            'user-delete',
            'user-edit',
            'user-list',
        ];
     
        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }
    }
}

