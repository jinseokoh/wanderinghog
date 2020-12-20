<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin', ['only' => ['index', 'edit']]);
    }

    public function index()
    {
        $users = User::all()->count();
        $questions = Question::all()->count();

        return view('dashboard.index', compact('users', 'questions'));
    }

    public function create()
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
