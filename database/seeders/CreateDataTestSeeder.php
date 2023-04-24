<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
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
        $faker = Faker::create();
        
        // COMPANIES
        $companies = Company::factory()->count(3)->create();

        // LISTINGS
        $jobs = ['Poste A', 'Poste B', 'Poste C'];
        $states = ['Non lu', 'Lu', 'En cours', 'En attente de réponse helpdesk', 'En attente de réponse entreprise', 'Résolu'];
        $services = ['Support informatique', 'Sécurité informatique', 'Réseau informatique', 'Application logicielle', 'Matériel informatique', 'Téléphonie / Communication', 'Conseil assistance générale', 'Autre'];
        $hourly_rate = '25';
        
        foreach ($jobs as $job) {
            Listing::factory()->create([
                'job' => $job,
                'state' => null,
                'service' => null,
                'hourly_rate' => null,
                'description' => null,
            ]);
        }
        
        foreach ($states as $state) {
            Listing::factory()->create([
                'job' => null,
                'state' => $state,
                'service' => null,
                'hourly_rate' => null,
                'description' => null,
            ]);
        }
        
        foreach ($services as $service) {
            Listing::factory()->create([
                'job' => null,
                'state' => null,
                'service' => $service,
                'hourly_rate' => null,
                'description' => $faker->paragraph(),
            ]);
        }

        Listing::factory()->create([
            'job' => null,
            'state' => null,
            'service' => null,
            'hourly_rate' => $hourly_rate,
            'description' => null,
        ]);

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
     
        $adminPermissions = Permission::whereIn('id', [4, 24, 25, 26, 27, 28, 30, 32, 33])->get();
   
        $adminCompanyRole->syncPermissions($adminPermissions);
     
        $userAdminCompany->assignRole([$adminCompanyRole->id]);

        // User with role 'membre'
        $memberRole = Role::create(['name' => 'Membre']);
     
        $memberPermissions = Permission::whereIn('id', [24, 25, 26, 27, 33])->get();
                
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
