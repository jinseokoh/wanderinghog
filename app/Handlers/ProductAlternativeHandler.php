<?php

namespace App\Handlers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductAlternativeHandler
{
    public function fetch(int $id): Collection
    {
        $regions = Product::find($id)
            ->categories()
            ->get()
            ->reverse();

        // $departure = $model->departure;

        foreach ($regions as $region) {
            $products = $region->products()
                ->with('categories')
                ->where('id', '<>', $id)
                ->inRandomOrder()
                // ->with('keywords')
                // ->whereHas('keywords', function ($q) {
                //     $q->where('category', 'SUBSET_ACCESSIBLE_TRAVEL');
                // })
                // ->where('departure', $departure);
                ->take(4)
                ->get();

            if ($products->count() >= 4) {
                return $products;
            }
        }

        return $products;
    }
}
