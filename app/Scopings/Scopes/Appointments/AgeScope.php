<?php

namespace App\Scopings\Scopes\Appointments;

use App\Scopings\Contracts\Scope;
use App\Support\AgeCalculator;
use Illuminate\Database\Eloquent\Builder;

class AgeScope implements Scope
{
    public function apply(Builder $builder, $value)
    {
        if (! $value) {
            return $builder;
        }
        $range = collect(explode(',', $value));
        if ($range->count() < 2) {
            \Log::info("[info] something's gone wrong => ".$value);
            return $builder;
        }

        $calculator = new AgeCalculator();
        $dates = $range
            ->map(function ($age, $idx) use ($calculator) {
                if ($idx) {
                    $age++;
                }
                return $calculator->getDobFromAge((int) $age);
            })
            ->reverse()
            ->all();

        return $builder->whereHas('user', function ($builder) use ($dates) {
            $builder->whereBetween('dob', $dates);
        });
    }
}
