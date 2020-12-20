<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kalnoy\Nestedset\NodeTrait;

class Profession extends Model
{
    use NodeTrait;

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

    // ================================================================================
    // local scopes
    // ================================================================================

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    // ================================================================================
    // relations
    // ================================================================================

    /**
     * Recursive children relations
     *
     * @return HasMany
     */
    public function children(): hasMany
    {
        return $this->hasMany(Profession::class, 'parent_id', 'id')
            ->with('children');
    }

}
