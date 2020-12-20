<?php

namespace App\Scopings\Scopes\Appointments;

use App\Scopings\Contracts\Scope;
use Illuminate\Database\Eloquent\Builder;

class RegionScope implements Scope
{
    public function apply(Builder $builder, $value)
    {
        if (! $value) {
            return $builder;
        }

        $regions = explode(',', $value);
        return $builder->whereHas('regions', function ($builder) use ($regions) {
            $builder->whereIn('region_id', $regions);
        });
    }
}
