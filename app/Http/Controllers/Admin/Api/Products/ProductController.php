<?php

namespace App\Http\Controllers\Admin\Api\Products;

use App\Handlers\ProductHandler;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function __construct()
    {
         $this->middleware('auth:admin');
    }

    public function index(Request $request, ProductHandler $productHandler)
    {
        $ids = $request->get('ids', []);

        if (count($ids) > 0) {
            $products =  Product::whereIn('id', $ids)->get();
        } else {
            $products = $productHandler->fetch('id', [], false);
        }

        return ProductResource::collection($products);
    }
}
