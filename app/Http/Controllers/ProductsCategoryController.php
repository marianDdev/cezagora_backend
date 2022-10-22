<?php

namespace App\Http\Controllers;

use App\Models\ProductsCategory;

class ProductsCategoryController extends Controller
{
    public function get(): array
    {
        return ProductsCategory::all()->pluck('name')->toArray();
    }
}
