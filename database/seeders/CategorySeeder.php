<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        foreach (['Sport', 'Health'] as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
