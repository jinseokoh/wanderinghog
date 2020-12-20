<?php


namespace App\Scopings\Scopes\Appointments;

use App\Scopings\Contracts\Scope;
use Illuminate\Database\Eloquent\Builder;

class LikeScope implements Scope
{
    public function apply(Builder $builder, $value)
    {
        if (! $value) {
            return $builder;
        }

        return $builder->whereHas('favoriters', function ($builder) use ($value) {
            $builder->where('user_id', $value);
        });
    }
}