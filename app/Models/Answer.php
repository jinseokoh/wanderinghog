<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;

class Answer extends Model implements HasMedia
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
    protected $casts = [];

    // ================================================================================
    // relations
    // ================================================================================

    /**
     * An answer belongs to a question
     *
     * @return BelongsTo
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * An answer belongs to a user
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
            ->addMediaCollection('answers')
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

    public function image()
    {
        $defaultImage = Question::find($this->question_id)->image();
        $image = $this
            ->getMedia('answers')
            ->map(function (Media $media) {
                return [
                    'id' => $media->id,
                    'image' => $media->getFullUrl(),
                    'thumb' => $media->getFullUrl('thumb'),
                ];
            })
            ->first();

        return $image ?: $defaultImage;
    }

    /**
     * Get a string path for the thread.
     *
     * @return string
     */
    public function path()
    {
        return "/answers/{$this->id}";
    }
}
