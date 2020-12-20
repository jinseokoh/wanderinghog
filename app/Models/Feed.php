<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feed extends Model
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
        'is_private' => 'boolean',
    ];

    // ================================================================================
    // relations
    // ================================================================================

    /**
     * An activity belongs to a user
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * An activity belongs to a category
     *
     * @return BelongsTo
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * An activity belongs to many parties (users)
     *
     * @return BelongsToMany
     */
    public function parties(): BelongsToMany
    {
        // return $this->belongsToMany(User::class)->wherePivot('approved', 1);
        return $this->belongsToMany(User::class)
            ->withPivot('approved');
    }

    // ================================================================================
    // accessors
    // ================================================================================

    public function getSkillsAttribute($value)
    {
        return array_map(function ($item) {
            return (int) $item;
        }, array_filter(explode('|', $value)));
    }

    public function getPartieOptionsAttribute($value)
    {
        return array_filter(explode('|', $value));
    }
}
