<?php

namespace App\Models;

use App\Enums\DecisionEnum;
use App\Models\Traits\CanBeScoped;
use App\Models\Traits\Flaggable;
use App\Observers\AppointmentObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Multicaret\Acquaintances\Traits\CanBeFavorited;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Appointment extends Model implements HasMedia, Searchable
{
    use InteractsWithMedia;
    use CanBeFavorited;
    use CanBeScoped;
    use SoftDeletes;
    use Flaggable;

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
        'is_closed' => 'bool',
        'is_active' => 'bool',
        'questions' => 'array',
    ];

    // ================================================================================
    // model events
    // ================================================================================

    public static function boot()
    {
        parent::boot();
        static::observe(AppointmentObserver::class);
    }

    // ================================================================================
    // relations
    // ================================================================================

    /**
     * An appointment has many partie combos
     *
     * @return HasMany
     */
    public function parties(): HasMany
    {
        return $this->hasMany(Party::class)
            ->orderBy('id');
    }

    /**
     * An appointment belongs to a venue
     *
     * @return BelongsTo;
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * An appointment belongs to a user
     *
     * @return BelongsTo;
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * An appointment belongs to many venues
     *
     * @return belongsToMany
     */
    public function regions(): belongsToMany
    {
        return $this->belongsToMany(Region::class);
    }

    // ================================================================================
    // accessors
    // ================================================================================

    /**
     * Todo. this isn't working. i lost the context i was trying to accomplish here.
     * @return array
     */
    public function getHostGendersAttribute(): array
    {
        return $this
            ->loadMissing(['parties.user', 'parties.friend'])
            ->first()
            ->loadMissing('user')->gender;
    }

    // ================================================================================
    // local scopes
    // ================================================================================

    /**
     * Scope a query to only include host party
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHostParty($query)
    {
        return $query->parties()
            ->where('is_host', true);
    }

    /**
     * Scope a query to only include all guest parties
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAllGuestParties($query)
    {
        return $query->parties()
            ->where('is_host', false);
    }

    /**
     * Scope a query to only include chosen guest party
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeChosenGuestParty($query)
    {
        return $query->parties()
            ->where('is_host', false)
            ->where('user_decision', DecisionEnum::approved()->label)
            ->where('friend_decision', DecisionEnum::approved()->label);
    }

    // ================================================================================
    // laravel-media-library v8
    // ================================================================================

    /**
     * @param Media|null $media
     */
    public function registerMediaConversions(Media $media = null): void
    {
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('appointments')
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->crop(Manipulations::CROP_CENTER, 256, 256);
            });
    }

    // ================================================================================
    // Searchable interface
    // ================================================================================

    public function getSearchResult(): SearchResult
    {
        // $url = route('blogPost.show', $this->slug);
        $url = $this->path();

        return new SearchResult(
            $this,
            $this->title,
            $url
        );
    }

    // ================================================================================
    // helpers
    // ================================================================================

    /**
     * @return Model|HasMany|object|null
     */
    public function hostParty()
    {
        return $this
            ->parties()
            ->where('is_host', true)
            ->first();
    }

    /**
     * @return array
     */
    public function allGuestParties(): array
    {
        return $this
            ->parties()
            ->where('is_host', false)
            ->get()
            ->all();
    }

    /**
     * @return Model|HasMany|object|null
     */
    public function chosenGuestParty()
    {
        return $this
            ->parties()
            ->where('is_host', false)
            ->where('user_decision', DecisionEnum::approved()->label)
            ->where('friend_decision', DecisionEnum::approved()->label)
            ->first();
    }

    /**
     * @return array|null
     */
    public function image(): ?array
    {
        return $this
            ->getMedia('appointments')
            ->map(function (Media $media) {
                return [
                    'id' => $media->id,
                    'image' => $media->getFullUrl(),
                    'thumb' => $media->getFullUrl('thumb'),
                ];
            })
            ->first();
    }

    /**
     * @return string
     */
    public function path(): string
    {
        return "/appointments/{$this->id}";
    }
}
