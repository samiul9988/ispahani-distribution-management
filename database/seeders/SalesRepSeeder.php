<?php

namespace Database\Seeders;

use App\Models\Market;
use App\Models\SalesRep;
use Illuminate\Database\Seeder;

class SalesRepSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'Emran' => 'Eshapura',
            'Sohel' => 'Barapa',
            'Shovo' => 'Golakandile',
        ];

        foreach ($map as $repName => $marketName) {
            $market = Market::where('name', $marketName)->first();

            SalesRep::firstOrCreate(
                ['name' => $repName],
                ['market_id' => $market?->id]
            );
        }
    }
}
