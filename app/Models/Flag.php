<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Flag extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'flaggable_id',
        'flaggable_type',
        'user_id',
    ];

    /**
     * a flag belongs to
     */
    public function flaggable()
    {
        return $this->morphTo();
    }

    /**
     * a flag belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
