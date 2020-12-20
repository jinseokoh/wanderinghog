<?php

namespace App\Handlers;

use App\Models\Product;
use App\Scoping\Scopes\CategoryScope;
use App\Scoping\Scopes\DepartureScope;
use App\Scoping\Scopes\KeywordScope;
use App\Scoping\Scopes\LanguageScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ProductHandler
{
    /**
     * @param int $id
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function findById(int $id, array $relations = [])
    {
        return
            Product::with(
                count($relations) ?
                    $relations :
                    [
                        'categories',
                        'keywords',
                        'languages',
                        //
                        'owner',
                        'recommendations',
                        'reminders',
                        'comments.user',
                        'programs.items',
                        'maps.venues',
                        //
                        'accommodations',
                        'flights',
                        'meals',
                        'tickets',
                        'tourGuides',
                        'tourServices',
                        'transportation',
                    ])
                ->where('id', $id)
                ->firstOrFail();
    }

    /**
     * fetch all relevant products
     *
     * @param string $orderBy
     * @param array $relations
     * @param bool $constraint
     * @return LengthAwarePaginator
     */
    public function fetch($orderBy = 'id', array $relations = [], bool $constraint = true): LengthAwarePaginator
    {
        $builder = Product::with(
            count($relations) ?
                $relations :
                [
                    'categories',
                    'keywords',
                    'programs',
                    'maps'
                ])
            ->withScopes($this->scopes());

        return $constraint ?
            $builder
                ->where('is_active', true)
                ->orderBy($orderBy, 'DESC')
                ->paginate(10) :
            $builder
                ->orderBy($orderBy, 'DESC')
                ->paginate(10);
    }

    /**
     * @param int $id
     * @param int $score
     * @return Product
     */
    public function updateLikeCount(int $id, int $score): Product
    {
        $product = Product::find($id);

        return tap($product)->update(['like_count' => $product->like_count + $score]);
    }

    /**
     * @param Product $product
     * @return array
     */
    public function getServices(Product $product)
    {
        $services = [];

        if ($product->accommodations->count()) {
            $services['name'][] = __('services.accommodation');
            $services['list'][] = $product->accommodations->pluck('context')->map(function ($i) {
                return __('accommodations.'.Str::slug($i));
            })->toArray();
        }
        if ($product->flights->count()) {
            $services['name'][] = __('services.flight');
            $services['list'][] = $product->flights->pluck('context')->map(function ($i) {
                return __('flights.'.Str::slug($i));
            })->toArray();
        }
        if ($product->transportation->count()) {
            $services['name'][] = __('services.transportation');
            $services['list'][] = $product->transportation->pluck('context')->map(function ($i) {
                return __('transportation.'.Str::slug($i));
            })->toArray();
        }
        if ($product->meals->count()) {
            $services['name'][] = __('services.meal');
            $services['list'][] = $product->meals->pluck('context')->map(function ($i) {
                return __('meals.'.Str::slug($i));
            })->toArray();
        }
        if ($product->tickets->count()) {
            $services['name'][] = __('services.ticket');
            $services['list'][] = $product->tickets->pluck('context')->map(function ($i) {
                return __('tickets.'.Str::slug($i));
            })->toArray();
        }
        if ($product->tourGuides->count()) {
            $services['name'][] = __('services.tour-guide');
            $services['list'][] = $product->tourGuides->pluck('context')->map(function ($i) {
                return __('tour_guides.'.Str::slug($i));
            })->toArray();
        }
        if ($product->tourServices->count()) {
            $services['name'][] = __('services.tour-service');
            $services['list'][] = $product->tourServices->pluck('context')->map(function ($i) {
                return __('tour_services.'.Str::slug($i));
            })->toArray();
        }

        return $services;
    }

    // ================================================================================
    // protected methods
    // ================================================================================

    protected function scopes()
    {
        return [
            'category' => new CategoryScope(),
            'keyword' => new KeywordScope(),
//            'language' => new LanguageScope(),
//            'departure' => new DepartureScope(),
        ];
    }
}
