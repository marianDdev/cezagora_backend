<?php

namespace App\Services;

use App\Models\Organization;
use Exception;
use Illuminate\Support\Collection;

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
        /** @var Collection $collection */
        $collection = Organization::when(!empty($filters['keyword']), function ($query) use ($filters) {
            return $query
                ->where('name', 'LIKE', "%{$filters['keyword']}%")
                ->orWhereJsonContains('products_categories', $filters['keyword']);
        })
                                  ->when(!empty($filters['company_type']), function ($query) use ($filters) {
                                      return $query->where('type', $filters['company_type']);
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
                $item->networking_status = $this->networkingService->getNetworkingStatusByOrganizationId($item->id);
                $item->lists             = $item->getMedia('lists');
            }
        }

        return $collection;
    }
}
