<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\Retailer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrganizationService
{
    public function getAuthOrganization(): Organization
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        return $authUser->organization;
    }

    public function update(Organization $organization, array $validated): void
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
            if ($organization->hasAttribute($column)) {
                $organization->$column = $value;
            }
        }

        $organization->save();
        $this->updateHasDetailsCompleted($organization);
    }

    private function validateRequiredMarketPlace(array $validated): void
    {
        $marketplacesValidated = Validator::make($validated, [
            'marketplaces' => Rule::requiredIf(in_array(Organization::ON_MARKETPLACE_METHOD, $validated['selling_methods'])),
        ]);

        if ($marketplacesValidated->fails()) {
            $marketplacesValidated->errors()->getMessages();
        }
    }

    private function updateHasDetailsCompleted(Organization $organization): void
    {
        $hasDetailsCompleted = count($organization->company_types) > 0
                               && !is_null($organization->phone)
                               && !is_null($organization->continent)
                               && !is_null($organization->country)
                               && !is_null($organization->city)
                               && !is_null($organization->address)
                               && count($organization->products_categories) > 0
                               && count($organization->selling_methods) > 0;
        if ($hasDetailsCompleted) {
            $organization->has_details_completed = true;
            $organization->save();
        }
    }
}
