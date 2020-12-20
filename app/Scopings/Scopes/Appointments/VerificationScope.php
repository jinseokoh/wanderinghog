<?php

namespace App\Scopings\Scopes\Appointments;

use App\Scopings\Contracts\Scope;
use Illuminate\Database\Eloquent\Builder;

class VerificationScope implements Scope
{
    public function apply(Builder $builder, $value)
    {
        if (! $value) {
            return $builder;
        }

        return
            $builder
                ->whereHas('user', function ($builder) {
                    $builder->whereNotNull('profession_verified_at');
                })
                ->orWhereHas('parties', function ($builder) {
                    $builder
                        ->where('is_host', true)
                        ->whereHas('friend', function ($builder) {
                            $builder->whereNotNull('profession_verified_at');
                        });
                });
    }
}
