<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\ProductsCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $type = $this->faker->randomElement(Organization::TYPES);
        $sellingMethods = null;
        $marketplaces = null;

        if ($type === Organization::RETAILER_TYPE) {
            $sellingMethods = $this->faker->randomElements(Organization::SELLING_METHODS);

            if (in_array(Organization::ON_MARKETPLACE_METHOD, $sellingMethods)) {
                $marketplaces = $this->faker->randomElement(Organization::MARKETPLACES);
            }
        }
        return [
            'type' => $type,
            'name' => $this->faker->company,
            'email' => $this->faker->companyEmail,
            'phone' => $this->faker->phoneNumber,
            'continent' => $this->faker->randomElement(Organization::CONTINENTS),
            'country' => $this->faker->country,
            'city' => $this->faker->city,
            'address' => $this->faker->streetAddress,
            'products_categories' => ProductsCategory::inRandomOrder()->take(rand(1,10))->get()->pluck('name'),
            'selling_methods' => $sellingMethods,
            'marketplaces' => $marketplaces
        ];
    }
}
