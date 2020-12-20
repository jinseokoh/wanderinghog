<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Question extends Model implements HasMedia
{
    use InteractsWithMedia;
    use NodeTrait;

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
    // relations
    // ================================================================================

    /**
     * Recursive parent relations
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'parent_id', 'id');
    }

    /**
     * a question has many answers
     *
     * @return HasMany
     */
    public function answers(): hasMany
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Recursive children relations (untested)
     *
     * @return HasMany
     */
    public function children(): hasMany
    {
        return $this->hasMany(Question::class, 'parent_id', 'id')
            ->with('children');
    }

    // ================================================================================
    // laravel-media-library v8
    // ================================================================================

    public function registerMediaConversions(Media $media = null): void
    {
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('questions')
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->crop(Manipulations::CROP_CENTER, 256, 256);
            });
    }

    // ================================================================================
    // helpers
    // ================================================================================

    public function image()
    {
        return $this
            ->getMedia('questions')
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
     * Get a string path for the thread.
     *
     * @return string
     */
    public function path()
    {
        return "/questions/{$this->id}";
    }
}
