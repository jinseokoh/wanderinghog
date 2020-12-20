<?php

namespace App\Http\Controllers\Appointments;

use App\Handlers\ProductSearchHandler;
use App\Http\Controllers\Controller;
use App\Http\Resources\CurationResource;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(ProductSearchHandler $curationHandler, Request $request)
    {
        $query = $request->input('query');
        $items = $curationHandler->search($query);
        $products = $items->map(function ($item) {
            return $item->searchable;
        });

        return ProductResource::collection($products);
    }
}
