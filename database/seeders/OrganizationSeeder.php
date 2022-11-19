<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\ProductsCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Organization::create(
            [
                'type' => 'distributor',
                'name' => 'Cezius Link',
                'email' => 'marian@cezius.tech',
                'phone' => '0737014770',
                'continent' => 'Europe',
                'country' => 'Romania',
                'city' => 'Bucharest',
                'address' => 'Str Patriotilor nr 9',
                'products_categories' => ProductsCategory::inRandomOrder()->take(rand(1,10))->get()->pluck('name'),
                'selling_methods' => null,
                'marketplaces' => null
            ]
        );

        Organization::factory(50)->create();
    }
}
