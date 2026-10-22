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
                    // Placeholder assumption (actual Ispahani cost price not in the source sheet):
                    // 85% of selling price. Edit per-product once real purchase costs are known.
                    'cost_price' => round($product['price'] * 0.85, 2),
                    'pic' => $product['pic'],
                ]
            );
        }
    }
}
