<?php

namespace App\Models;

use App\Observers\PartyObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Party extends Model
{
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
    protected $casts = [
        'is_host' => 'bool',
        'is_excluded' => 'bool',
        'answers' => 'array',
    ];

    // ================================================================================
    // model events
    // ================================================================================

    public static function boot()
    {
        parent::boot();
        static::observe(PartyObserver::class);
    }

    // ================================================================================
    // relations
    // ================================================================================

    /**
     * A party belongs to an appointment
     *
     * @return BelongsTo
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * A party belongs to a user
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A party belongs to a user called friend
     *
     * @return BelongsTo
     */
    public function friend(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ================================================================================
    // helpers
    // ================================================================================
}
