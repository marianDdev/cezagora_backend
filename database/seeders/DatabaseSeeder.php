<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CompanyCategorySeeder::class);
        $this->call(ProducstCategoriesSeeder::class);
        $this->call(CompanySeeder::class);
    }
}
