<?php

namespace App\Models\Traits;

use Kalnoy\Nestedset\QueryBuilder;

trait HasChildren
{
    public function scopeParent($query)
    {
        dd($query);
        return $query->whereNull('parent_id');
    }
}
