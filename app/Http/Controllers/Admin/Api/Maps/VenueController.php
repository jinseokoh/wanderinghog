<?php

namespace App\Http\Controllers\Admin\Api\Maps;

use App\Http\Requests\VenueStoreRequest;
use App\Models\Map;
use App\Models\Venue;
use App\Http\Controllers\Controller;

class VenueController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function show($mapId, $venueId)
    {
        $venue = Venue::findOrFail($venueId);

        $payload = [
            'order' => $venue->order,
            'title' => $venue->title,
            'description' => $venue->description,
            'title_en' => $venue->getTranslation('title','en'),
            'description_en' => $venue->getTranslation('description','en'),

            'coordinates' => $venue->latitude.','.$venue->longitude.','.$venue->zoom_level.'z',
            'phone' => $venue->phone,
            'address' => $venue->address,
            'url' => $venue->url,
            'geo_json' => $venue->geo_json,
        ];

        return response()->json($payload);
    }

    public function store($mapId, VenueStoreRequest $request)
    {
        $map = Map::findOrFail($mapId);
        $venue = Venue::make([
            'order' => $request->getOrder(),
            'latitude' => $request->getLatitude(),
            'longitude' => $request->getLongitude(),
            'zoom_level' => $request->getZoomLevel(),
            'phone' => $request->getPhone(),
            'address' => $request->getAddress(),
            'url' => $request->getUrl(),
            'geo_json' => $request->getGeoJson(),
        ])->setTranslations('title', [
            'en' => $request->getTitleEnglish(),
            'ko' => $request->getTitle()
        ])->setTranslations('description', [
            'en' => $request->getDescriptionEnglish(),
            'ko' => $request->getDescription()
        ]);
        $map->venues()->save($venue);

        return response([], 204);
    }

    public function update($mapId, $venueId, VenueStoreRequest $request)
    {
        /** @var Venue $venue */
        $venue = tap(Venue::find($venueId))->update([
            'order' => $request->getOrder(),
            'latitude' => $request->getLatitude(),
            'longitude' => $request->getLongitude(),
            'zoom_level' => $request->getZoomLevel(),
            'phone' => $request->getPhone(),
            'address' => $request->getAddress(),
            'url' => $request->getUrl(),
            'geo_json' => $request->getGeoJson(),
        ]);
        $venue->setTranslations('title', [
            'en' => $request->getTitleEnglish(),
            'ko' => $request->getTitle()
        ])->setTranslations('description', [
            'en' => $request->getDescriptionEnglish(),
            'ko' => $request->getDescription()
        ])->save();

        return response([], 200);
    }

    public function destroy($mapId, $venueId)
    {
        $venue = Venue::findOrFail($venueId);
        $venue->delete();

        return response([], 200);
    }
}
