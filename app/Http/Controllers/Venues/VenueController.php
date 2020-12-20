<?php

namespace App\Http\Controllers\Venues;

use App\Handlers\VenueHandler;
use App\Http\Controllers\Controller;
use App\Http\Dtos\VenueStoreDto;
use App\Http\Requests\VenueStoreRequest;
use App\Http\Resources\VenueResource;
use App\Models\Venue;

class VenueController extends Controller
{
    /**
     * @param VenueHandler $venueHandler
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(VenueHandler $venueHandler)
    {
        $venues = $venueHandler
            ->fetch();

        return VenueResource::collection($venues);
    }

    /**
     * @param VenueStoreRequest $request
     * @return VenueResource
     */
    public function store(VenueStoreRequest $request): VenueResource
    {
        /** @var VenueStoreDto $dto */
        $dto = $request->getVenueStoreDto();

        $venue = Venue::where('place_id', $dto->getPlaceId())
            ->firstOr(function () use ($dto) {
                return Venue::create($dto->toArray());
            });

        return new VenueResource($venue);
    }
}
