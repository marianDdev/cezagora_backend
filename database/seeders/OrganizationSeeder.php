<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\ProductsCategory;
use App\Models\User;
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
                'company_types'       => [],
                'name'                => 'Cezius Link',
                'email'               => 'marian@cezius.tech',
                'phone'               => '0737014770',
                'continent'           => 'Europe',
                'country'             => 'Romania',
                'city'                => 'Bucharest',
                'address'             => 'Str Patriotilor nr 9',
                'products_categories' => ProductsCategory::inRandomOrder()->take(rand(1, 10))->get()->pluck('name'),
                'selling_methods'     => [],
                'marketplaces'        => null,
            ]
        );

        User::create(
            [
                'company_name' => 'Cezius Link',
                'organization_id' => 1,
                'email' => 'marian@cezius.tech',
                'email_verified_at' => now(),
                'password' => '$2y$10$vCm4/r2zlSyOl6bkylqhsu.mhxP/.3q/NNXMGbZEg5MrMQk96hae6',
                'remember_token' => Str::random(10),
            ]
        );

        Organization::factory(50)
                    ->has(User::factory()->count(1))
                    ->create();
    }
}
