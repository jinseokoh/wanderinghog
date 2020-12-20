<?php

namespace App\Scopings\Scopes\Appointments;

use App\Scopings\Contracts\Scope;
use Illuminate\Database\Eloquent\Builder;

class ThemeScope implements Scope
{
    public function apply(Builder $builder, $value)
    {
        if (! $value) {
            return $builder;
        }

        $types = explode(',', $value);
        return $builder->whereIn('theme_type', $types);
    }
}
