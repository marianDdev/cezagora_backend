<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchAllLimited(Request $request, SearchService $searchService)
    {
        return $searchService->getAllLimited($request->all(), 3);
    }

    public function searchByCompanies(Request $request, SearchService $searchService)
    {
        $companyType = $request->get('company_type');
        $composedMethodName = 'get' . ucfirst($companyType);

        return $searchService->$composedMethodName($request->all());
    }
}
