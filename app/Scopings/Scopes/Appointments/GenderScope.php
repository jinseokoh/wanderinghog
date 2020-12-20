<?php

namespace App\Scopings\Scopes\Appointments;

use App\Scopings\Contracts\Scope;
use Illuminate\Database\Eloquent\Builder;

class GenderScope implements Scope
{
    public function apply(Builder $builder, $value)
    {
        if (! $value) {
            return $builder;
        }

        return $builder->whereHas('user', function ($builder) use ($value) {
            $builder->where('gender', $value);
        });
    }
}
