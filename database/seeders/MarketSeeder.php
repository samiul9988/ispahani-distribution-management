<?php

namespace Database\Seeders;

use App\Models\Market;
use Illuminate\Database\Seeder;

class MarketSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Eshapura', 'Barapa', 'Golakandile'] as $name) {
            Market::firstOrCreate(['name' => $name]);
        }
    }
}
