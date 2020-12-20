<?php

namespace App\Scopings\Scopes\Users;

use App\Scopings\Contracts\Scope;
use Illuminate\Database\Eloquent\Builder;

class GenderScope implements Scope
{
    public function apply(Builder $builder, $value)
    {
        if (! $value) {
            return $builder;
        }

        return $builder->where('gender', $value);
    }
}
