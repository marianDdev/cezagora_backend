<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SearchController extends Controller
{
    public function searchAll(Request $request, SearchService $searchService): Collection|JsonResponse
    {

        $collection = $searchService->getAll($request->all());

        if ($collection->count() === 0) {
            return response()->json('No results found');
        }

        return $collection;
    }

    public function searchByType(Request $request, SearchService $searchService): Collection|JsonResponse
    {
        $companyType = $request->get('company_types');

        if (count($companyType) === 0) {
            return response()->json(['Please add a company type.'], 401);
        }

        $collection = $searchService->getAll($request->all());

        if ($collection->count() === 0) {
            return response()->json('No results found');
        }

        return $collection;
    }
}
