<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'samiulkabir9988@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $this->call([
            CompanyProfileSeeder::class,
            MarketSeeder::class,
            SalesRepSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
