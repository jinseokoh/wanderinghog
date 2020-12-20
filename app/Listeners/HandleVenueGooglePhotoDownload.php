<?php

namespace App\Listeners;

use App\Events\VenueCreated;
use App\Handlers\MediaHandler;
use App\Models\Venue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use SKAgarwal\GoogleApi\PlacesApi;

class HandleVenueGooglePhotoDownload implements ShouldQueue
{
    /**
     * @var MediaHandler
     */
    private MediaHandler $mediaHandler;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(MediaHandler $mediaHandler)
    {
        $this->mediaHandler = $mediaHandler;
    }

    /**
     * Handle the event.
     *veby
     * @param  VenueCreated  $event
     * @return void
     */
    public function handle(VenueCreated $event)
    {
        /** @var Venue $venue */
        $venue = $event->venue;
        $photoRefs = $venue->photo_refs;

        if (is_array($photoRefs)) {
            foreach ($photoRefs as $photoRef) {
                $url = (new PlacesApi())
                    ->setKey(config('services.google_api.key'))
                    ->photo($photoRef, ['maxwidth' => 1280]);

                try {
                    $this->mediaHandler->saveModelMediaFromUrl($venue, $url);
                } catch (\Throwable $exception) {
                    \Log::error($exception->getMessage());
                }
            }
        }
    }
}
