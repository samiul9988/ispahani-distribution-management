<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/ispahani_products.json');
        $products = json_decode(file_get_contents($path), true);

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['name' => $product['name']],
                [
                    'price' => $product['price'],
                    'pic' => $product['pic'],
                ]
            );
        }
    }
}
