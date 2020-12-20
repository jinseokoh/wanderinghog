<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Resources\ThemeResource;
use App\Models\Theme;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function index(Request $request)
    {
        $themes = Theme::all();

        return ThemeResource::collection($themes);
    }

    public function show($id)
    {
        $theme = Theme::findOrFail($id);

        $images = $theme->images();

        return response()->json([
            'data' => $images
        ]);
    }
}
