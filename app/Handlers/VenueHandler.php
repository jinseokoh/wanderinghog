<?php

namespace App\Handlers;

use App\Models\Venue;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class VenueHandler
{
    /**
     * find appointment by id
     *
     * @param int $id
     * @return
     */
    public function findById(int $id)
    {
        return Venue::where('id', $id)
            ->firstOrFail();
    }

    /**
     * fetch all experiences
     *
     * @return LengthAwarePaginator
     */
    public function fetch(): LengthAwarePaginator
    {
        return Venue::orderBy('id', 'DESC')
            ->paginate(10);
    }
}
