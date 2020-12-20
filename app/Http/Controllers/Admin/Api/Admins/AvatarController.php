<?php

namespace App\Http\Controllers\Admin\Api\Admins;

use App\Handlers\MediaHandler;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class AvatarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Store a new user avatar.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(int $id, Request $request, MediaHandler $mediaHandler)
    {
        $request->validate([
            'id' => ['integer'],
            'avatar' => ['required', 'image']
        ]);

        $admin = Admin::find($id); // auth('admin')->user();
        if ($admin->avatar) {
            $mediaHandler->remove($admin->avatar);
        }

        $file = $request->file('avatar');
        $url = $mediaHandler->saveAvatar($file, 'admins', $admin->id);
        $admin = tap($admin)->update([ 'avatar' => $url ]);

        return response([ 'data' => $admin ], 200);
    }
}
