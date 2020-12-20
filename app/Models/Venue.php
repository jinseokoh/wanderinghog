<?php

namespace App\Models;

use App\Observers\VenueObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;

class Venue extends Model implements HasMedia
{
    use InteractsWithMedia, HasTags;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['photo_refs' => 'array'];

    // ================================================================================
    // model events
    // ================================================================================

    public static function boot()
    {
        parent::boot();
        static::observe(VenueObserver::class);
    }

    // ================================================================================
    // relations
    // ================================================================================

    /**
     * A venue belongs to many appointments
     *
     * @return HasMany
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    // ================================================================================
    // laravel-media-library v8
    // ================================================================================

    /**
     * @param Media|null $media
     */
    public function registerMediaConversions(Media $media = null): void
    {
        // reference) https://docs.spatie.be/image/v1/image-manipulations/overview/
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('venues')
            // ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->crop(Manipulations::CROP_CENTER, 256, 256);
            });
    }

    // ================================================================================
    // helpers
    // ================================================================================

    /**
     * @return array
     */
    public function hashTags(): array
    {
        return $this
            ->tags()
            ->get()
            ->map(function ($item) {
                return $item->name;
            })
            ->values()
            ->all();
    }

    /**
     * @return array|null
     */
    public function images(): ?array
    {
        return $this
            ->getMedia('venues')
            ->map(function (Media $media) {
                return [
                    'id' => $media->id,
                    'image' => $media->getFullUrl(),
                    'thumb' => $media->getFullUrl('thumb'),
                ];
            })
            ->all();
    }

    /**
     * @return array|null
     */
    public function image(): ?array
    {
        return $this
            ->getMedia('venues')
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
        return "/venues/{$this->id}";
    }
}
