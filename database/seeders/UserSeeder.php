<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(
            [
                'organization_id' => 1,
                'first_name' => 'Marian',
                'last_name' => 'Dumitru',
                'email' => 'marian@cezius.tech',
                'phone' => '0737014770',
                'position' => 'CEO',
                'email_verified_at' => now(),
                'password' => '$2y$10$vCm4/r2zlSyOl6bkylqhsu.mhxP/.3q/NNXMGbZEg5MrMQk96hae6',
                'remember_token' => Str::random(10),
            ]
        );

        User::factory(50)->create();
    }
}
