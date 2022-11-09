<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\Retailer;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrganizationService
{
    public function getOrganizationTypeClassName(Organization $organization): string
    {
        return 'App\Models\\' . ucfirst($organization->type);
    }

    public function getOrganizationTypeModel(Organization $organization): ?Model
    {
        $organizationType = $this->getOrganizationTypeClassName($organization);

        return $organizationType::where('organization_id', $organization->id)->first();
    }

    public function update(Model $organizationType, array $validated)
    {
        $this->validateRequiredMarketPlace($validated);

        foreach ($validated as $column => $value) {
            if ($organizationType->hasAttribute($column)) {
                $organizationType->$column = $value;
            }
        }

        $organizationType->save();
        $this->updateHasDetailsCompleted($organizationType);

        $modelResource = $this->getModelResource($organizationType->organization);

        return new $modelResource($organizationType);
    }

    public function getOrganizationByAuthUser(): ?Model
    {
        /** @var User $user */
        $user = Auth::user();

        return $this->getOrganizationTypeModel($user->organization);
    }

    public function getModelResource(Organization $organization): string
    {
        return 'App\Http\Resources\\' . ucfirst($organization->type) . 'Resource';
    }

    /**
     * marketplaces key must be present in the request body if selling methods array contains on_marketplaces value
     *
     * @param array $validated
     *
     * @return array|void
     */
    private function validateRequiredMarketPlace(array $validated)
    {
        $marketplacesValidated = Validator::make($validated, [
            'marketplaces' => Rule::requiredIf(in_array(Retailer::ON_MARKETPLACE_METHOD, $validated['selling_methods'])),
        ]);

        if ($marketplacesValidated->fails()) {
            return $marketplacesValidated->errors()->getMessages();
        }
    }

    private function updateHasDetailsCompleted(Model $organizationTypeModel): void
    {
        /** @var Organization $organization */
        $organization = $organizationTypeModel->organization;

        $organization->has_details_completed = true;
        $organization->save();
    }
}
