<?php

namespace App\Http\Controllers\Admin\Cards;

use App\Handlers\UserHandler;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserDetailResource;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CardController extends Controller
{
    private $userHandler;

    public function __construct(UserHandler $userHandler)
    {
        $this->middleware('auth:admin');

        $this->userHandler = $userHandler;
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        // $users = $this->userHandler->fetchUsers();

        $users = User::with([
            'profile',
            'socialProviders',
            'media'
        ])
            ->join('media', function ($join) {
                $join->on('users.id', '=', 'media.model_id')
                    ->where('media.collection_name', '=', 'cards');
            })
            ->selectRaw('users.*')
            ->orderBy('media.created_at', 'DESC')
            ->paginate(10);

        $userResource = UserDetailResource::collection($users);

        return view('cards.index', compact('userResource'));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        return view('cards.create');
    }

    /**
     * @return Factory|View
     */
    public function store(int $id, Request $request)
    {
        dd($request->all());
        return view('cards.create');
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Factory|View
     */
    public function edit(int $id, Request $request)
    {
        $user = new UserResource(User::find($id));
        $page = (int) $request->get('page', 1);

        return view('cards.edit', [
            'page' => $page,
            'user' => json_encode($user)
        ]);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Factory|View
     */
    public function show(int $id, Request $request)
    {
        $user = User::find($id);

        $page = (int) $request->get('page', 1);

        return view('cards.show', [
            'page' => $page,
            'user' => new UserResource($user)
        ]);
    }
}
