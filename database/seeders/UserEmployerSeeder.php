<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
// use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Employers;
use App\Models\DolStaff;
use App\Models\Company;

class UserEmployerSeeder extends Seeder
{
   
    public function run()
    {
        for ($x = 11; $x <= 70; $x++) {
            $industry = rand(1, 20);
            $type = rand(1, 2);
            $lists[] = ['industry' => $industry, 'type' => $type, 'email' => 'employer' . $x . '@gmail.com', 'full_name' => 'employer' . $x . '', 'password' => '111111'];
        };

        $company_id = 1;
        
        foreach ($lists as $list) {
            
            $company_id = $company_id + 1;

            $company = Company::findorfail($company_id);

            $user = User::create([
                'full_name' => $list['full_name'],
                'email' =>  $list['email'],
                'is_admin' => 0,
                'password' =>  Hash::make($list['password']),
                'company_id' =>   $company_id
            ]);

            $employer = Employers::create([
                'user_id' => $user->id,
                'company_name' => $company->company_name,
                'verified' => 1,
                'company_id' =>   $company_id
            ]);

            $dolstaff = DolStaff::create([
                'user_id' => $user->id,
                'company_id' => $company_id
            ]);

            $user->assignRole('EMPLOYER');

            /** Page Access */
            $user->givePermissionTo('Access-Page-User');
            $user->givePermissionTo('Access-Page-Dashboard');
            $user->givePermissionTo('Action Edit User');
        }
    }
}
