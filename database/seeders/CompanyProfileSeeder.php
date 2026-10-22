<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use Illuminate\Database\Seeder;

class CompanyProfileSeeder extends Seeder
{
    public function run(): void
    {
        CompanyProfile::firstOrCreate(
            ['distributor_name' => 'M/S Tobarak Mollah Store'],
            [
                'supplier_name' => 'Ispahani Tea Bangladesh Ltd.',
                'address' => 'Biyala Bazar, Bhulta, Rupganj, Narayanganj',
            ]
        );
    }
}
