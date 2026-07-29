<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Category::factory()
            ->count(5)
            ->create();

        Product::factory()
            ->count(30)
            ->create();
    }
}