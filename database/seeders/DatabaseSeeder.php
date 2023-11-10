<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            // PermissionSeeder::class,
            // RoleSeeder::class,
            // UserSeeder::class,
            // BusinessSeeder::class,
            // BusinessTypeSeeder::class,
        ]);

        ini_set('memory_limit', '-1');
    }
}
