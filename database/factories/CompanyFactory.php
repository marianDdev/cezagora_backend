<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\ProductsCategory;
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
        $type = $this->faker->randomElement(Company::TYPES);
        $sellingMethods = null;
        $marketplaces = null;

        if ($type === Company::RETAILER_TYPE) {
            $sellingMethods = $this->faker->randomElements(Company::SELLING_METHODS);

            if (in_array(Company::ON_MARKETPLACE_METHOD, $sellingMethods)) {
                $marketplaces = $this->faker->randomElement(Company::MARKETPLACES);
            }
        }
        return [
            'company_types' => [],
            'name' => $this->faker->company,
            'email' => $this->faker->companyEmail,
            'phone' => $this->faker->phoneNumber,
            'continent' => $this->faker->randomElement(Company::CONTINENTS),
            'country' => $this->faker->country,
            'city' => $this->faker->city,
            'address' => $this->faker->streetAddress,
            'products_categories' => ProductsCategory::inRandomOrder()->take(rand(1,10))->get()->pluck('name'),
            'selling_methods' => [],
            'marketplaces' => $marketplaces
        ];
    }
}
