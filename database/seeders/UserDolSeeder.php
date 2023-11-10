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

class UserDolSeeder extends Seeder
{

    public function run()
    {

        for ($x = 3; $x <= 10; $x++) {
            $lists[] = ['email' => 'dolstaff' . $x . '@gmail.com', 'full_name' => 'dol_staff' . $x . '', 'password' => '111111', 'company_name' => 'company_staff' . $x . ''];
        };

        foreach ($lists as $list) {
            $company_id = 1;
            $user = User::create([
                'full_name' => $list['full_name'],
                'email' =>  $list['email'],
                'is_admin' => 0,
                'password' =>  Hash::make($list['password']),
                'company_id' => 1
            ]);

            $employer = Employers::create([
                'user_id' => $user->id,
                'company_name' => $list['company_name'],
                'company_id' => $company_id,
            ]);

            $dolstaff = DolStaff::create([
                'user_id' => $user->id,
                'verified' => 1,
                'company_id' => $company_id
            ]);

            $user->assignRole('DOL STAFF');

            /** Page Access */
            $user->givePermissionTo('Access-Page-User');
            $user->givePermissionTo('Access-Page-Dashboard');
            $user->givePermissionTo('Action Edit User');

            /* Report Permission */
            $user->givePermissionTo('Access-Page-Report');
            $user->givePermissionTo('Action Print Report');
            $user->givePermissionTo('Action Download Report');
            $user->givePermissionTo('Action Generate Report');

            /* Edit Company Permission */
            $user->givePermissionTo('Access-Page-Company');
            $user->givePermissionTo('Action Edit Company');
            $user->givePermissionTo('Action Delete Company');
            $user->givePermissionTo('Action Print Company');
            $user->givePermissionTo('Action Download Company');
            $user->givePermissionTo('Action Create Company');

            /* Edit Categories Permission */
            $user->givePermissionTo('Access-Page-Categories');
            $user->givePermissionTo('Action Edit Categories-Type');
            $user->givePermissionTo('Action Delete Categories-Type');
            $user->givePermissionTo('Action Edit Categories-Industry');
            $user->givePermissionTo('Action Delete Categories-Industry');
            $user->givePermissionTo('Action Print Categories');
            $user->givePermissionTo('Action Download Categories');
            $user->givePermissionTo('Action Create Categories-Type');
            $user->givePermissionTo('Action Create Categories-Industry');
        }
    }
}
