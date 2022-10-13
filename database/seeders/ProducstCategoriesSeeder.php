<?php

namespace Database\Seeders;

use App\Models\ProductsCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProducstCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (ProductsCategory::CATEGORIES as $category) {
            ProductsCategory::create(['name' => $category]);
        }
    }
}
