<?php

namespace App\Handlers;

use App\Models\Product;
use Spatie\Searchable\ModelSearchAspect;
use Spatie\Searchable\Search;

class ProductSearchHandler
{
    public function search($query)
    {
        return (new Search())
            ->registerModel(Product::class, function(ModelSearchAspect $modelSearchAspect) {
                $modelSearchAspect
                    ->addSearchableAttribute('title') // return results for partial matches
                    ->addSearchableAttribute('subtitle') // return results for partial matches
                    // ->addExactSearchableAttribute('email') // only return results that exactly match
                    ->with([
                        'categories',
                        'keywords',
                        'languages',
                        'programs',
                        'maps'
                    ]);
            })
            ->search($query);
    }
}
