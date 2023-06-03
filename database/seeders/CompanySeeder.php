<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\ProductsCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organization = Company::create(
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
                'first_name' => 'Marian',
                'last_name' => 'Dumitru',
                'is_admin' => true,
                'company_id' => $organization->id,
                'email' => 'marian@cezius.tech',
                'email_verified_at' => now(),
                'password' => '$2y$10$vCm4/r2zlSyOl6bkylqhsu.mhxP/.3q/NNXMGbZEg5MrMQk96hae6',
                'remember_token' => Str::random(10),
            ]
        );

        Company::factory(50)
                    ->has(User::factory()->count(1))
                    ->create();
    }
}
