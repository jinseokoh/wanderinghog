<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlagCounter extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'flaggable_id',
        'flaggable_type',
        'coiunt',
    ];

    /**
     * a flag belongs to
     */
    public function flaggable()
    {
        return $this->morphTo();
    }
}
