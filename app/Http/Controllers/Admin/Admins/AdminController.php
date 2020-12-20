<?php

namespace App\Http\Controllers\Admin\Admins;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(int $id, Request $request)
    {
        $user = $request->user('admin');
        $admin = Admin::find($id);

        return view('admins.index', compact('user', 'admin'));
    }

    public function update(int $id, Request $request)
    {
        $id = $request->get('id');
        $admin = Admin::find($id);

        $payload = [
            'name' => $request->get('name'),
            'job_title' => $request->get('job_title'),
            'introduction' => $request->get('introduction'),
        ];
        $product = tap($admin)->update($payload);

        return redirect('/admin/admins/'.$id)->with('flash', '성공적으로 수정했습니다.');
    }
}
