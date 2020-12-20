<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Theme extends Model implements HasMedia
{
    use InteractsWithMedia;

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
    protected $casts = [];

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
            ->addMediaCollection('themes')
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
     * @return array|null
     */
    public function images(): ?array
    {
        return $this
            ->getMedia('themes')
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
     * @return string
     */
    public function path(): string
    {
        return "/themes/{$this->id}";
    }
}

