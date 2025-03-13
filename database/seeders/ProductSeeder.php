<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::insert([
            ['name' => 'NMN 800', 'price' => 20, 'category_id' => 1],
            ['name' => 'Trypto-Tyro', 'price' => 20, 'category_id' => 1],
            ['name' => 'Hangover Fighter', 'price' => 35, 'category_id' => 1],
        ]);
    }
}
