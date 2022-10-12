<?php

namespace App\Http\Requests;

use App\Models\Organization;
use App\Models\ProductsCategory;
use App\Models\Retailer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string'],
            'continent' => ['nullable', 'string', Rule::in(Organization::CONTINENTS)],
            'country' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'products_categories' => ['nullable', 'array', Rule::in(ProductsCategory::CATEGORIES)],
            'selling_methods' => ['nullable', 'array', Rule::in(Retailer::SELLING_METHODS)],
            'marketplaces' => ['nullable', 'array', Retailer::MARKETPLACES],
        ];
    }
}
