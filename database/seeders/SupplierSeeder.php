<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        Supplier::firstOrCreate(
            ['name' => 'Ispahani Tea Bangladesh Ltd.'],
            ['company' => 'Ispahani Tea Bangladesh Ltd.', 'opening_balance' => 0]
        );
    }
}
