<?php

namespace App\Models;

use App\Observers\ProfileObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = [
        'user'
    ];

    /**
     * The attributes that should be cast to Carbon type.
     *
     * @var array
     */
    protected $dates = [
        'expired_at',
    ];

    // ================================================================================
    // model events
    // ================================================================================

    public static function boot()
    {
        parent::boot();
        static::observe(ProfileObserver::class);
    }

    // ================================================================================
    // relations
    // ================================================================================

    /**
     * A profile belongs to a user
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
