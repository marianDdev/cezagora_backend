<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Models\ProductsCategory;
use App\Models\Retailer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'company_types'  => ['nullable', 'array'],
            'name' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string'],
            'continent' => ['nullable', 'string', Rule::in(Company::CONTINENTS)],
            'country' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'products_categories' => ['nullable', 'array', Rule::in(ProductsCategory::CATEGORIES)],
            'selling_methods' => ['nullable', 'array', Rule::in(Company::SELLING_METHODS)],
            'marketplaces' => ['nullable', 'array', Company::MARKETPLACES],
        ];
    }
}
