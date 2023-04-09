<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateDataTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // COMPANIES
        $companies = Company::factory()->count(3)->create();


        // LISTINGS
        $jobs = ['Poste A', 'Poste B', 'Poste C'];
        $states = ['Etat A', 'Etat B', 'Etat C'];
        $services = ['Service A', 'Service B', 'Service C'];
        
        foreach ($jobs as $job) {
            Listing::factory()->create([
                'job' => $job,
                'state' => null,
                'service' => null,
            ]);
        }
        
        foreach ($states as $state) {
            Listing::factory()->create([
                'job' => null,
                'state' => $state,
                'service' => null,
            ]);
        }
        
        foreach ($services as $service) {
            Listing::factory()->create([
                'job' => null,
                'state' => null,
                'service' => $service
            ]);
        }


        // USERS
        // User with role 'admin-entreprise'
        $userAdminCompany = User::firstOrCreate([
            'firstname' => 'admin', 
            'lastname' => 'Entreprise', 
            'email' => 'adminentreprise@mail.com',
            'password' => bcrypt('password'),
            'company_id' => $companies[0]->id,
            'job' => $jobs[0],
        ]);
    
        $adminCompanyRole = Role::create(['name' => 'Admin-entreprise']);
     
        $adminPermissions = Permission::whereIn('id', [4, 22, 23, 24, 25, 26, 27, 28, 29, 30])->get();
   
        $adminCompanyRole->syncPermissions($adminPermissions);
     
        $userAdminCompany->assignRole([$adminCompanyRole->id]);

        // User with role 'membre'
        $memberRole = Role::create(['name' => 'Membre']);
     
        $memberPermissions = Permission::whereIn('id', [22, 23, 24, 25, 30])->get();
                
        $memberRole->syncPermissions($memberPermissions);
                
        for ($i = 0; $i < 4; $i++) { 
            $companyIndex = ($i < 2) ? 0 : rand(1,2);

            $userMember = User::firstOrCreate([
                'firstname' => fake()->firstName(), 
                'lastname' => fake()->lastName(), 
                'email' => "pn".($i + 1)."@mail.com",
                'password' => bcrypt('password'),
                'company_id' => $companies[$companyIndex]->id,
                'job' => $jobs[rand(0,2)],
            ]);
            
            $userMember->assignRole([$memberRole->id]);
        }
    }
}
