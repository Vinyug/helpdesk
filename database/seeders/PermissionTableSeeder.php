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
           'role-list',
           'role-create',
           'role-edit',
           'role-delete',
           'user-list',
           'user-create',
           'user-edit',
           'user-delete',
           'company-list',
           'company-create',
           'company-edit',
           'company-delete', 
           'job-list',
           'job-create',
           'job-edit',
           'job-delete',
           'state-list',
           'state-create',
           'state-edit',
           'state-delete',
           'service-list',
           'service-create',
           'service-edit',
           'service-delete',
           'ticket-list',
           'ticket-create',
           'ticket-edit',
           'ticket-delete',
        ];
     
        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }
    }
}

