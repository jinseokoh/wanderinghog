<?php

namespace App\Providers;

use App\Events\AppInvitationSent;
use App\Events\AppointmentViewed;
use App\Events\KakaoRegistered;
use App\Events\PartyCreated;
use App\Events\PartyDecisionMadeByFriend;
use App\Events\PartyDecisionMadeByUser;
use App\Events\UserActivated;
use App\Events\VenueCreated;
use App\Events\VenueDeselected;
use App\Events\VenueSelected;
use App\Listeners\HandleAppInvitation;
use App\Listeners\HandleAppointmentActivation;
use App\Listeners\HandleAppointmentViewCountIncrement;
use App\Listeners\HandleFriendPartyDecision;
use App\Listeners\HandleKakaoRegistration;
use App\Listeners\HandleUserActivation;
use App\Listeners\HandleUserPartyDecision;
use App\Listeners\HandleVenueDeselection;
use App\Listeners\HandleVenueGooglePhotoDownload;
use App\Listeners\HandleVenueSelection;
use App\Listeners\SendWelcomeUserNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        KakaoRegistered::class => [
            HandleKakaoRegistration::class,
        ],
        Verified::class => [
            SendWelcomeUserNotification::class,
        ],
        AppointmentViewed::class => [
            HandleAppointmentViewCountIncrement::class,
        ],
        // party models
        PartyCreated::class => [
            HandleAppointmentActivation::class,
        ],
        PartyDecisionMadeByUser::class => [
            HandleUserPartyDecision::class,
        ],
        PartyDecisionMadeByFriend::class => [
            HandleFriendPartyDecision::class,
        ],
        // venue models
        VenueCreated::class => [
            HandleVenueGooglePhotoDownload::class,
        ],
        VenueSelected::class => [
            HandleVenueSelection::class,
        ],
        VenueDeselected::class => [
            HandleVenueDeselection::class,
        ],

        UserActivated::class => [
            HandleUserActivation::class,
        ],
        AppInvitationSent::class => [
            HandleAppInvitation::class,
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
