<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Models\Employers;
use App\Models\DolStaff;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        /* Super Admin */
        DB::beginTransaction();
        try {

            $user = User::create([
                'name' => 'SuperAdmin',
                'full_name' => 'SuperAdmin',
                'first_name' => 'SuperAdmin',
                'last_name' => 'Account',
                'email' => 'superadmin@gmail.com',
                'is_admin' => 1,
                'password' =>  Hash::make(config('custom.password_superadmin')),
                'company_id' => 1
            ]);

            $employer = Employers::create([
                'user_id' => $user->id,
                'company_id' => 1
            ]);

            $dolstaff = DolStaff::create([
                'user_id' => $user->id,
                'company_id' => 1
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e, 500);
        }
        DB::commit();

        $user->assignRole('ADMIN');
        $user->assignRole('SUPERADMIN');

        /** Permissions ALL */

        /** Page Access */
        $user->givePermissionTo('Access-Page-User');
        $user->givePermissionTo('Access-Page-Dashboard');
        $user->givePermissionTo('Access-Page-Logs');

        /** Page Actions */
        $user->givePermissionTo('Action Edit Permission');

        /* User Permission */
        $user->givePermissionTo('Action Delete User');
        $user->givePermissionTo('Action Create User');
        $user->givePermissionTo('Action Edit User');
        $user->givePermissionTo('Action Show-All User');

        /* Logs Permission */

        /* Admin and SuperAdmin Permission */
        $user->givePermissionTo('Action Settings Roles');
        $user->givePermissionTo('Action Download User');
        $user->givePermissionTo('Action Download Logs');

        /* Report Permission */
        $user->givePermissionTo('Access-Page-Report');
        $user->givePermissionTo('Action Print Report');
        $user->givePermissionTo('Action Download Report');
        $user->givePermissionTo('Action Generate Report');

        /* Edit Company Permission */
        $user->givePermissionTo('Access-Page-Company');
        $user->givePermissionTo('Action Edit Company');
        $user->givePermissionTo('Action Print Company');
        $user->givePermissionTo('Action Download Company');

        /* Edit Categories Permission */
        $user->givePermissionTo('Access-Page-Categories');
        $user->givePermissionTo('Action Print Categories');
        $user->givePermissionTo('Action Download Categories');

        /* Admin */
        DB::beginTransaction();

        try {

            $user = User::create([
                'full_name' => 'Admin Account',
                'first_name' => 'Admin',
                'last_name' => 'Account',
                'name' => 'Admin Account',
                'email' => 'admin@gmail.com',
                'is_admin' => 1,
                'password' =>  Hash::make(config('custom.password_admin')),
                'company_id' => 1
            ]);

            $employer = Employers::create([
                'user_id' => $user->id,
                'company_id' => 1
            ]);

            $dolstaff = DolStaff::create([
                'user_id' => $user->id,
                'company_id' => 1
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e, 500);
        }

        DB::commit();

        $user->assignRole('ADMIN');

        /** Page Access */
        $user->givePermissionTo('Access-Page-User');
        $user->givePermissionTo('Access-Page-Dashboard');
        $user->givePermissionTo('Access-Page-Logs');

        /** Page Actions */
        $user->givePermissionTo('Action Edit Permission');
        /* User Permission */
        $user->givePermissionTo('Action Delete User');
        $user->givePermissionTo('Action Create User');
        $user->givePermissionTo('Action Edit User');
        $user->givePermissionTo('Action Show-All User');

        /* Logs Permission */

        /* Admin and SuperAdmin Permission */
        $user->givePermissionTo('Action Settings Roles');
        $user->givePermissionTo('Action Download User');
        $user->givePermissionTo('Action Download Logs');

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
