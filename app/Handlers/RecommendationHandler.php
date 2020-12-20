<?php

namespace App\Handlers;

use App\Models\Recommendation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class RecommendationHandler
{
    /**
     * find a model
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findById(int $id)
    {
        return Recommendation::with([ 'product' ])
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * fetch all relevant recommendations
     *
     * @param int $id
     * @return LengthAwarePaginator
     */
    public function fetch(Request $request): LengthAwarePaginator
    {
        return Recommendation::with([ 'product' ])
            ->orderBy('id', 'DESC')
            ->paginate(10);
    }
}
