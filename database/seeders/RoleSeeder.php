<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
 
    public function run()
    {
        $role = Role::create(['name' => 'SUPERADMIN']);
        $role = Role::create(['name' => 'ADMIN']);
        $role = Role::create(['name' => 'EMPLOYER']);
        $role = Role::create(['name' => 'DOL STAFF']);
    }
}
