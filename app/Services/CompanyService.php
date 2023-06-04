<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Retailer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CompanyService
{
    public function getAuthCompany(): Company
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        return $authUser->company;
    }

    public function update(Company $company, array $validated): void
    {
        if (in_array('selling_methods', $validated)) {
            $this->validateRequiredMarketPlace($validated);
            $validated['selling_methods'] = array_unique($validated['selling_methods']);
        }

        if (in_array('products_categories', $validated)) {
            $validated['products_categories'] = array_unique($validated['products_categories']);
        }

        if (in_array('company_types', $validated)) {
            $validated['company_types'] = array_unique($validated['company_types']);
        }

        foreach ($validated as $column => $value) {
            if ($company->hasAttribute($column)) {
                $company->$column = $value;
            }
        }

        $company->save();
        $this->updateHasDetailsCompleted($company);
    }

    private function validateRequiredMarketPlace(array $validated): void
    {
        $marketplacesValidated = Validator::make($validated, [
            'marketplaces' => Rule::requiredIf(in_array(Company::ON_MARKETPLACE_METHOD, $validated['selling_methods'])),
        ]);

        if ($marketplacesValidated->fails()) {
            $marketplacesValidated->errors()->getMessages();
        }
    }

    private function updateHasDetailsCompleted(Company $company): void
    {
        $hasDetailsCompleted = count($company->company_types) > 0
                               && !is_null($company->phone)
                               && !is_null($company->continent)
                               && !is_null($company->country)
                               && !is_null($company->city)
                               && !is_null($company->address)
                               && count($company->products_categories) > 0
                               && count($company->selling_methods) > 0;
        if ($hasDetailsCompleted) {
            $company->has_details_completed = true;
            $company->save();
        }
    }
}
