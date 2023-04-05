<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'firstname' => 'admin', 
            'lastname' => 'admin', 
            'email' => 'admin@mail.com',
            'password' => bcrypt('password'),
            'role' => 'Super-admin',
        ]);
    
        $role = Role::create(['name' => 'Super-admin']);
     
        $permissions = Permission::pluck('id','id');
   
        $role->syncPermissions($permissions);
     
        $user->assignRole([$role->id]);

        // Attach permissions to user
        $userPermissions = Permission::all();
        foreach ($userPermissions as $permission) {
            $user->givePermissionTo($permission);
        }
    }
}
