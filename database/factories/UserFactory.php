<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $organization = Organization::inRandomOrder()->take(1)->first();
        $firstName = $this->faker->firstName;
        return [
            'organization_id' => $organization->id,
            'first_name' => $firstName,
            'last_name' => $this->faker->lastName,
            'email' => strtolower(sprintf('%s.%s', $firstName, $organization->email)),
            'phone' => $this->faker->phoneNumber,
            'position' => $this->faker->randomElement(['CEO', 'CTO', 'CPO', 'VP', 'CFO']),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
