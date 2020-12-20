<?php

namespace App\Handlers;

use App\Models\Curation;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductCurationHandler
{
    public function fetch()
    {
        $items = \Cache::remember('curated_products', 60, function () {
            return Curation::with([
                'products' => function (BelongsToMany $query) {
                    $query->where('is_active', true)
                        ->orderBy('view_count', 'DESC');
                }, 'products.likes'])
                ->where('is_active', true)
                ->orderBy('order')
                ->get()
                ->groupBy('keyword');
        });

        return $items->all();
    }
}
