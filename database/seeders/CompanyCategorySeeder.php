<?php

namespace Database\Seeders;

use App\Models\CompanyCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (CompanyCategory::TYPES as $type)
        {
            CompanyCategory::create(['name' => $type]);
        }
    }
}
