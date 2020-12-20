<?php

namespace App\Scopings\Scopes\Users;

use App\Scopings\Contracts\Scope;
use Illuminate\Database\Eloquent\Builder;

class VerificationScope implements Scope
{
    public function apply(Builder $builder, $value)
    {
        if (! $value) {
            return $builder;
        }

        return $builder->whereNotNull('profession_verified_at');
    }
}
