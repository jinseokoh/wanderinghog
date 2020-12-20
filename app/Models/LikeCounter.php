<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LikeCounter extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'likable_id',
        'likable_type',
        'count',
    ];

    /**
     * a like belongs to
     */
    public function likable()
    {
        return $this->morphTo();
    }
}
