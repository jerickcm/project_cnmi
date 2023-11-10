<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
// use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
  
    public function run()
    {
        /** Page Access */
        Permission::create(['name' => 'Access-Page-User']);
        Permission::create(['name' => 'Access-Page-Dashboard']);
        Permission::create(['name' => 'Access-Page-Logs']);

        /** Page Actions */
        Permission::create(['name' => 'Action Edit Permission']);

        /* User Permission */
        Permission::create(['name' => 'Action Delete User']);
        Permission::create(['name' => 'Action Create User']);
        Permission::create(['name' => 'Action Edit User']);
        Permission::create(['name' => 'Action Show-All User']);

        /* Logs Permission */
        Permission::create(['name' => 'Action Download User']);
        Permission::create(['name' => 'Action Download Logs']);
        Permission::create(['name' => 'Action Settings Roles']);

        /* Report Permission */
        Permission::create(['name' => 'Access-Page-Report']);
        Permission::create(['name' => 'Action Print Report']);
        Permission::create(['name' => 'Action Download Report']);
        Permission::create(['name' => 'Action Generate Report']);

        /* Edit Company Permission */
        Permission::create(['name' => 'Access-Page-Company']);
        Permission::create(['name' => 'Action Edit Company']);
        Permission::create(['name' => 'Action Delete Company']);
        Permission::create(['name' => 'Action Print Company']);
        Permission::create(['name' => 'Action Download Company']);
        Permission::create(['name' => 'Action Create Company']);

        /* Edit Categories Permission */
        Permission::create(['name' => 'Access-Page-Categories']);
        Permission::create(['name' => 'Action Edit Categories-Type']);
        Permission::create(['name' => 'Action Delete Categories-Type']);
        Permission::create(['name' => 'Action Edit Categories-Industry']);
        Permission::create(['name' => 'Action Delete Categories-Industry']);
        Permission::create(['name' => 'Action Print Categories']);
        Permission::create(['name' => 'Action Download Categories']);
        Permission::create(['name' => 'Action Create Categories-Type']);
        Permission::create(['name' => 'Action Create Categories-Industry']);

    }
}
