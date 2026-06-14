<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $base = rtrim(env('AWS_URL'), '/');

        $products = [
            // Sport
            [
                'category_id'    => 1,
                'name'           => 'Whey Protein',
                'description'    => 'High-quality whey protein to support muscle growth and recovery after training.',
                'price'          => 39.99,
                'stock_quantity' => 150,
                'image_url'      => "{$base}/whey.webp",
            ],
            [
                'category_id'    => 1,
                'name'           => 'Creatine Monohydrate',
                'description'    => 'Pure creatine monohydrate to increase strength, power and muscle mass.',
                'price'          => 24.99,
                'stock_quantity' => 200,
                'image_url'      => "{$base}/creatine.jpg",
            ],
            [
                'category_id'    => 1,
                'name'           => 'Omega-3',
                'description'    => 'Premium fish oil rich in EPA and DHA to support cardiovascular and joint health.',
                'price'          => 19.99,
                'stock_quantity' => 180,
                'image_url'      => "{$base}/omega3.jpg",
            ],
            // Health
            [
                'category_id'    => 2,
                'name'           => 'Vitamin C',
                'description'    => 'High-dose vitamin C to boost the immune system and protect against oxidative stress.',
                'price'          => 14.99,
                'stock_quantity' => 250,
                'image_url'      => "{$base}/vitamin_c.jpg",
            ],
            [
                'category_id'    => 2,
                'name'           => 'Magnesium',
                'description'    => 'Magnesium glycinate for better absorption to reduce fatigue and improve sleep quality.',
                'price'          => 17.99,
                'stock_quantity' => 160,
                'image_url'      => "{$base}/magnesium.webp",
            ],
            [
                'category_id'    => 2,
                'name'           => 'Vitamin D3',
                'description'    => 'Vitamin D3 combined with K2 for optimal calcium absorption and bone health.',
                'price'          => 15.99,
                'stock_quantity' => 220,
                'image_url'      => "{$base}/vitamin_d3.jpg",
            ],
            [
                'category_id'    => 2,
                'name'           => 'Melatonin',
                'description'    => 'Natural melatonin to regulate sleep cycles and improve the quality of rest.',
                'price'          => 12.99,
                'stock_quantity' => 190,
                'image_url'      => "{$base}/melatonin.webp",
            ],
            [
                'category_id'    => 2,
                'name'           => 'Ashwagandha',
                'description'    => 'Adaptogenic root extract (KSM-66) to reduce stress, anxiety and improve energy levels.',
                'price'          => 22.99,
                'stock_quantity' => 130,
                'image_url'      => "{$base}/ashwagandha.jpg",
            ],
            [
                'category_id'    => 2,
                'name'           => 'Collagen',
                'description'    => 'Hydrolyzed marine collagen to support skin elasticity, joint health and hair strength.',
                'price'          => 29.99,
                'stock_quantity' => 120,
                'image_url'      => "{$base}/collagen.webp",
            ],
            [
                'category_id'    => 2,
                'name'           => 'Zinc',
                'description'    => 'Zinc bisglycinate for optimal immune function, hormone balance and skin health.',
                'price'          => 11.99,
                'stock_quantity' => 210,
                'image_url'      => "{$base}/zinc.webp",
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                $product
            );
        }
    }
}
