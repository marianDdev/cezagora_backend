<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SearchService
{
    private NetworkingService $networkingService;

    public function __construct(NetworkingService $networkingService)
    {
        $this->networkingService = $networkingService;
    }

    public function getAll(array $filters, ?int $limit = null): array|string
    {
        $results = [
            'manufacturers' => $this->getManufacturers($filters, $limit)->toArray(),
            'distributors'  => $this->getDistributors($filters, $limit)->toArray(),
            'retailers'     => $this->getRetailers($filters, $limit)->toArray(),
            'wholesalers'   => $this->getWholesalers($filters, $limit)->toArray(),
        ];

        $lengths = [];

        foreach ($results as $result) {
            $lengths[] = count($result) === 0;
        }

        if (!in_array(false, $lengths)) {
            return "No results";
        }

        return $results;
    }

    public function getManufacturers(array $filters, $limit = null): Collection
    {
        return $this->getQueryWithFilters('manufacturers', $filters, $limit);
    }

    public function getRetailers(array $filters, $limit = null): Collection
    {
        return $this->getQueryWithFilters('retailers', $filters, $limit);
    }

    public function getDistributors(array $filters, $limit = null): Collection
    {
        return $this->getQueryWithFilters('distributors', $filters, $limit);
    }

    public function getWholesalers(array $filters, $limit = null): Collection
    {
        return $this->getQueryWithFilters('wholesalers', $filters, $limit);
    }

    /**
     * @throws Exception
     */
    private function getQueryWithFilters(string $tableName, array $filters, $limit): Collection
    {
        $collection = DB::table($tableName)->when(!empty($filters['keyword']), function ($query) use ($filters) {
            return $query
                ->where('name', 'LIKE', "%{$filters['keyword']}%")
                ->orWhereJsonContains('products_categories', $filters['keyword']);
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

        foreach ($collection as $item) {
            $organization_type = substr_replace($tableName ,"",-1);
            $item->networking_status = $this->networkingService->getNetworkingStatusByOrganizationId($item->organization_id);
            $item->organization_type = $organization_type;
        }

        return $collection;
    }
}
