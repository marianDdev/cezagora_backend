<?php

namespace App\Services;

use App\Models\Company;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class SearchService
{
    private NetworkingService $networkingService;
    private ListsService      $listsService;

    public function __construct(NetworkingService $networkingService, ListsService $listsService)
    {
        $this->networkingService = $networkingService;
        $this->listsService      = $listsService;
    }

    /**
     * @throws Exception
     */
    public function getAll(array $filters, ?int $limit = null): Collection
    {
        /** @var User $authUser */
        $authUser           = Auth::user();
        $authCompanyId = $authUser->company->id;

        /** @var Collection $collection */
        $collection = Company::where('id', '!=', $authCompanyId)
                             ->when(!empty($filters['keyword']), function ($query) use ($filters) {
                                      return $query
                                          ->where('name', 'LIKE', "%{$filters['keyword']}%")
                                          ->orWhereJsonContains('products_categories', $filters['keyword']);
                                  })
                             ->when(!empty($filters['company_types']), function ($query) use ($filters) {
                                      return $query->whereJsonContains('company_types', $filters['type']);
                                  })
                             ->when(!empty($filters['continent']), function ($query) use ($filters) {
                                      return $query->where('continent', $filters['continent']);
                                  })
                                  ->when(!empty($filters['country']), function ($query) use ($filters) {
                                      return $query->where('country', $filters['country']);
                                  })
                                  ->when(!empty($filters['city']), function ($query) use ($filters) {
                                      return $query->where('city', 'LIKE', "%{$filters['city']}%");
                                  })
                                  ->when(!empty($filters['products']), function ($query) use ($filters) {
                                      return $query->whereJsonContains('products_categories', $filters['products']);
                                  })
                                  ->when(!is_null($limit), function ($query) use ($limit) {
                                      return $query->limit($limit);
                                  })
                                  ->get();


        if (!$collection->isEmpty()) {
            foreach ($collection as $item) {

                $networkingStats = $this->networkingService->getNetworkingStatusByCompanyId($item->id);
                $item->following = $networkingStats['followed'];
                $item->connected = $networkingStats['connected'];

                $item->lists      = $item->getMedia('lists');
                $item->avatar     = $item->getFirstMediaUrl('profile_picture');
                $item->background = $item->getFirstMediaUrl('background_picture');
            }
        }

        return $collection;
    }
}
