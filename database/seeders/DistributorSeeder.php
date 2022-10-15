<?php

namespace Database\Seeders;

use App\Models\Distributor;
use App\Models\Organization;
use App\Models\User;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DistributorSeeder extends Seeder
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('ro_RO');
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 50) as $index) {
            $organization = Organization::create(['type' => Organization::DISTRIBUTOR_TYPE]);
            $this->createUser($organization->id);
            $this->createDistributor($organization->id);
        }
    }

    private function createUser(int $organizationId)
    {
        User::create(
            [
                'organization_id' => $organizationId,
                'first_name'      => $this->faker->firstName,
                'last_name'       => $this->faker->lastName,
                'email'           => $this->faker->email,
                'phone'           => $this->faker->phoneNumber,
                'position'        => 'CEO',
                'password'        => Hash::make(Str::random()),
            ]
        );
    }

    private function createDistributor(int $organizationId)
    {
        Distributor::create(
            [
                'organization_id'     => $organizationId,
                'name'                => $this->faker->company,
                'email'               => $this->faker->companyEmail,
                'phone'               => $this->faker->phoneNumber,
                'continent'           => "Europe",
                'country'             => 'Romania',
                'city'                => $this->faker->city,
                'address'             => $this->faker->address,
                'products_categories' => ['face_mask', 'hair_oil'],
            ]
        );
    }
}
