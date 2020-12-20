<?php

namespace App\Http\Controllers\Admin\Users;

use App\Handlers\UserHandler;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
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
        $users = $this->userHandler->fetchUsers();

        return view('users.index', compact('users'));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        return view('users.create');
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

        return view('users.edit', [
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

        return view('users.show', [
            'page' => $page,
            'user' => new UserResource($user)
        ]);
    }
}
