<?php

namespace App\Http\Controllers\Admin\Venues;

use App\Handlers\VenueHandler;
use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Http\Resources\VenueResource;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VenueController extends Controller
{
    /**
     * @var VenueHandler
     */
    private $venueHandler;

    public function __construct(VenueHandler $venueHandler)
    {
        $this->middleware('auth:admin');
        $this->venueHandler = $venueHandler;
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $venues = $this->venueHandler->fetch($request);

        return view('venues.index', compact('venues'));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        return view('venues.create');
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Factory|View
     */
    public function edit(int $id, Request $request)
    {
        $order = new VenueResource(Venue::find($id));
        $page = (int) $request->get('page', 1);

        return view('venues.edit', [
            'page' => $page,
            'order' => json_encode($order)
        ]);
    }

    /**
     * @param int $id
     * @return Factory|View
     */
    public function show(int $id)
    {
        $venue = $this->venueHandler->findById($id);

        return view('venues.show', compact('venue'));
    }
}
