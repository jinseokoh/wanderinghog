<?php

namespace App\Http\Controllers\Admin\Api\Items;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function store($id, Request $request)
    {
        request()->validate([
            'file' => ['required', 'image']
        ]);

        $file = $request->file('file');
        $item = Item::with('media')->findOrFail($id);
        $item->clearMediaCollection('items');
        $item->addMedia($file)
            ->usingFileName($file->hashName())
            ->toMediaCollection('items');

        return response([], 204);
    }

    public function destroy($id)
    {
        $item = Item::with('media')->findOrFail($id);
        $item->clearMediaCollection('items');

        return response([], 200);
    }
}
