<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DistributorSeeder::class);
        $this->call(ManufacturerSeeder::class);
        $this->call(RetailerSeeder::class);
        $this->call(WholeSalerSeeder::class);
        $this->call(ProducstCategoriesSeeder::class);
    }
}
