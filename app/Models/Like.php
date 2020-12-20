<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Like extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'likable_id',
        'likable_type',
        'user_id',
    ];

    /**
     * a like belongs to
     */
    public function likable()
    {
        return $this->morphTo();
    }

    /**
     * a like belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
