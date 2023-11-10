<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
  
    public function run()
    {

        $lists1 = ['company_name' => 'CNMI DEPARTMENT OF LABOR', 'contact_address' => 'address', 'contact_number' => 'contact'];

        $company = Company::create([
            'company_name' => $lists1['company_name'],
            'contact_address' => $lists1['contact_address'],
            'contact_number' => $lists1['contact_number'],
        ]);

        $company_array = [
            [
                'company_name' => 'US ARMY',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Hyatt',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Deloitte',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'EY',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'McDonalds',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Oracle',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Cisco Systems',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'US Navy',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Pizza Hut',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Hewlett Packard Enterprise | HPE',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'DFS Group',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'HEB',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Paypal',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Progressive Insurance',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Total Quality Logistics',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Ace Hardware',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'GHD',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Proof Point',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Ernst & Young',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Republic Finance LLC',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Entegris',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Tutor.com',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Sirius Compute Solutions',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'APL',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Department of Defense Education',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'North East Independent School District',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Elliot Company',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Royal Ambulance',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Northern Marianas College',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Department of Public Works and Highways',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'NTT DoCoMo',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Mount Carmel School',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'OKIN Process',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'CNMI Public School System',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Grupo JCA',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Commonwealth Utilities Corporation',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'DFS Galleria',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Diagnostic Laboratory Services',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Zebra English',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Casher',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'CWM International',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Patriot Contract Services',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Star Marianas Air',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Office of the Governor of the State of California',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Triple J Enterprises',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'CHCC',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Western Sales Trading Co.',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Saipan Community School',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Marianas Pacific Distributors',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Kalayaan',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Saipan Seventh-day Adventist Clinic',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Joeten',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Saipan World Resort',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'RR and Associates',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'CNMI Attorney General',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Micronesia Resort',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'CNMI Office of the Public Auditor',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Marianas Health',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Dotts Law Office',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ],
            [
                'company_name' => 'Tan Holdings',
                'contact_address' => 'Northern Mariana Islands',
                'contact_number' => ''
            ]
            
        ];

        foreach ($company_array as $list) {

            $company = Company::create([
                'company_name' => $list['company_name'],
                'contact_address' => $list['contact_address'],
                'contact_number' => $list['contact_number'],
                'verified' => 1
            ]);

        }
    }
}
