<?php

namespace App\Models;

use App\Models\Traits\CanBeScoped;
use App\Models\Traits\Flaggable;
use App\Observers\UserObserver;
use App\Support\PhoneNumberParser;
use App\Notifications\VerifyEmail as VerfiyEmailNotification;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Jenssegers\Optimus\Optimus;
use Multicaret\Acquaintances\Interaction;
use Multicaret\Acquaintances\Traits\CanFavorite;
use Multicaret\Acquaintances\Traits\Friendable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail, HasMedia
{
    use Notifiable;
    use Friendable;
    use CanFavorite;
    use CanBeScoped;
    use InteractsWithMedia;
    use Flaggable;
    use HasFactory;

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
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'email_verified_at',
        'phone_verified_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'dob' => 'datetime:Y-m-d',
        'is_active' => 'boolean',
    ];

    // ================================================================================
    // model events
    // ================================================================================

    public static function boot()
    {
        parent::boot();
        static::observe(UserObserver::class);
    }

    // ================================================================================
    // relations
    // ================================================================================

    /**
     * A user has one profile
     *
     * @return HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * A user has one device token
     *
     * @return HasOne
     */
    public function deviceToken(): HasOne
    {
        return $this->hasOne(DeviceToken::class);
    }

    /**
     * A user has one profile
     *
     * @return HasOne
     */
    public function preference(): HasOne
    {
        return $this->hasOne(Preference::class);
    }

    // ================================================================================

    /**
     * Get the Social Login (OAuth) providers.
     *
     * @return HasMany
     */
    public function socialProviders(): HasMany
    {
        return $this->hasMany(SocialProvider::class);
    }

    /**
     * A user has many appInvitations
     *
     * @return HasMany
     */
    public function appInvitations(): HasMany
    {
        return $this->hasMany(AppInvitation::class);
    }

    /**
     * A user has many answers
     *
     * @return HasMany
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * A user has many appointments
     *
     * @return HasMany
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    // ================================================================================

    /**
     * A user belongs to many rooms
     *
     * @return BelongsToMany
     */
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class)
            ->withPivot('unread');
    }

    /**
     * A user belongs to many questions
     *
     * @return BelongsToMany
     */
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class);
    }

    /**
     * A user belongs to many kakao(friend)s
     *
     * @return BelongsToMany
     */
    public function kakaos(): BelongsToMany
    {
        return $this->belongsToMany(Kakao::class);
    }

    /**
     * @return MorphToMany
     */
    public function bookmarks(): MorphToMany
    {
        return $this->morphToMany(
            User::class,
            'subject',
            'interactions')
            ->wherePivot('relation', '=', Interaction::RELATION_FAVORITE)
            ->withPivot(['relation', 'relation_value', 'relation_type']);
    }

    // ================================================================================
    // accessors
    // ================================================================================

    /**
     * @return int|null
     */
    public function getAgeAttribute(): ?int
    {
        return $this->dob ? Carbon::parse($this->dob)->age : null;
    }

    /**
     * @return int
     */
    public function getHashedIdAttribute(): int
    {
        return app(Optimus::class)->encode($this->id);
    }

    // ================================================================================
    // JWTSubject interfaces
    // ================================================================================

    /**
     * @return int
     */
    public function getJWTIdentifier(): int
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'uuid' => $this->uuid,
        ];
    }

    // ================================================================================
    // MustVerifyEmail interfaces
    // ================================================================================

    /**
     * @return void
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerfiyEmailNotification);
    }

    // ================================================================================
    // CanResetPassword interfaces
    // ================================================================================

    /**
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    // ================================================================================
    // Notification channels
    // ================================================================================

    /**
     * Route notifications for the Slack channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForSlack($notification): string
    {
        return config('logging.channels.slack.url');
    }

    /**
     * Route notifications for the AWS SNS channel. (for SMS text message)
     *
     * @param $notification
     * @return string
     */
    public function routeNotificationForSns(Notification $notification): string
    {
        $phone = (new PhoneNumberParser())->international($this->phone);
        \Log::info('[INFO] '.$phone);

        return $phone;
    }

    /**
     * Route notifications for the Aligo text channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForAligoText($notification): string
    {
        return $this->phone;
    }

    /**
     * Route notifications for the Aligo kakao channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForAligoKakao($notification): string
    {
        return $this->phone;
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
            ->addMediaCollection('avatars')
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->crop(Manipulations::CROP_CENTER, 256, 256);
            });

        $this
            ->addMediaCollection('photos')
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->crop(Manipulations::CROP_CENTER, 256, 256);
            });

        $this
            ->addMediaCollection('cards')
            ->singleFile();
    }

    // ================================================================================
    // helpers
    // ================================================================================

    public function age(): ?int
    {
        return $this->dob ?
            ($this->dob->age > 500 ? null : $this->dob->age) :
            null;
    }

    /**
     * @return bool
     */
    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
            'is_active' => true,
        ])->save();
    }

    /**
     * @return array|null
     */
    public function photos(): ?array
    {
        return $this
            ->getMedia('photos')
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
            ->getMedia('cards')
            ->map(function (Media $media) {
                return [
                    'id' => $media->id,
                    'image' => $media->getFullUrl(),
                    'thumb' => null // $media->getFullUrl('thumb'),
                ];
            })
            ->first();
    }

    /**
     * @return string
     */
    public function path(): string
    {
        return "/users/{$this->id}";
    }
}
