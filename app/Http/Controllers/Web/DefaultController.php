<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class DefaultController extends Controller
{
    /**
     * default welcome view
     */
    public function index()
    {
        return view('welcome');
    }

    public function status()
    {
        return response()->json([
            'online' => true,
            'version' => config('app.version')
        ]);
    }
}
