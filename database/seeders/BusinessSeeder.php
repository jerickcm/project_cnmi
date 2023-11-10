<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessSeeder extends Seeder
{

    public function run()
    {
        ini_set('memory_limit', '-1');

        DB::statement("INSERT INTO `businesses` (`id`, `industry`) VALUES
            (1, 'Automotive '),
            (2, 'Business Support & Supplies'),
            (3, 'Computers & Electronics'),
            (4, 'Construction & Contractors'),
            (5, 'Education'),
            (6, 'Entertainment'),
            (7, 'Food & Dining '),
            (8, 'Health & Medicine'),
            (9, 'Home & Garden '),
            (10, 'Legal & Financial'),
            (11, 'Manufacturing, Wholesale, Distribution '),
            (12, 'Merchants (Retail) '),
            (13, 'Miscellaneous '),
            (14, 'Personal Care & Services '),
            (15, 'Real Estate'),
            (16, 'Travel & Transportation');"
         );

         DB::statement("UPDATE `businesses` SET `created_at`=now(),`updated_at`=now(); ");


    }
}
