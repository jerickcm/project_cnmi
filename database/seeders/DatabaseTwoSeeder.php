<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseTwoSeeder extends Seeder
{

    public function run()
    {
        $this->call([
            CompanySeeder::class,
            UserDolSeeder::class,
            UserEmployerSeeder::class,
        ]);
    }
}
