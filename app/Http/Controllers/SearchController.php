<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchAllLimited(Request $request, SearchService $searchService)
    {
        return $searchService->getAllLimited($request->all());
    }

    public function searchByCompanies(Request $request, SearchService $searchService): JsonResponse
    {
        $companyType = $request->get('company_type');

        if (is_null($companyType)) {
            return response()->json(['Please add a compnay type.'], 401);
        }
        $composedMethodName = 'get' . ucfirst($companyType);

        return $searchService->$composedMethodName($request->all());
    }
}
