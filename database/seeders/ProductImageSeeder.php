<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductImage;

class ProductImageSeeder extends Seeder
{
    public function run()
    {
        ProductImage::insert([
            ['product_id' => 1, 'image_url' => '/storage/assets/images/img.svg'],
            ['product_id' => 2, 'image_url' => '/storage/assets/images/img.svg'],
            ['product_id' => 3, 'image_url' => '/storage/assets/images/img.svg'],
        ]);
    }
}