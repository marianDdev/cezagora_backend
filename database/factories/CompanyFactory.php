<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CompanyCategory;
use App\Models\ProductsCategory;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $type = $this->faker->randomElement(CompanyCategory::TYPES);
        return [
            'company_category_id' => CompanyCategory::inRandomOrder()->first()->id,
            'name' => $this->faker->company,
            'email' => $this->faker->companyEmail,
            'phone' => $this->faker->phoneNumber,
            'continent' => $this->faker->randomElement(Company::CONTINENTS),
            'country' => $this->faker->country,
            'city' => $this->faker->city,
            'address' => $this->faker->streetAddress,
        ];
    }
}
