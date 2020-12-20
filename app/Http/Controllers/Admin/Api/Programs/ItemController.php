<?php

namespace App\Http\Controllers\Admin\Api\Programs;

use App\Http\Requests\ItemStoreRequest;
use App\Models\Item;
use App\Models\Program;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function show($programId, $itemId)
    {
        $item = Item::findOrFail($itemId);

        $payload = [
            'order' => $item->order,
            'type' => [
                'name' => $item->name,
                'slug' => $item->slug,
            ],
            'title' => $item->title,
            'description' => $item->description,
            'title_en' => $item->getTranslation('title','en'),
            'description_en' => $item->getTranslation('description','en'),
        ];

        return response()->json($payload);
    }

    public function store($programId, ItemStoreRequest $request)
    {
        $program = Program::findOrFail($programId);
        $item = Item::make([
            'order' => $request->getOrder(),
            'name' => $request->getName(),
            'slug' => $request->getSlug(),
        ])->setTranslations('title', [
            'en' => $request->getTitleEnglish(),
            'ko' => $request->getTitle()
        ])->setTranslations('description', [
            'en' => $request->getDescriptionEnglish(),
            'ko' => $request->getDescription()
        ]);
        $program->items()->save($item);

        return response([], 204);
    }

    public function update($programId, $itemId, ItemStoreRequest $request)
    {
        /** @var Item $item */
        $item = tap(Item::find($itemId))->update([
            'order' => $request->getOrder(),
            'name' => $request->getName(),
            'slug' => $request->getSlug(),
        ]);
        $item->setTranslations('title', [
            'en' => $request->getTitleEnglish(),
            'ko' => $request->getTitle()
        ])->setTranslations('description', [
            'en' => $request->getDescriptionEnglish(),
            'ko' => $request->getDescription()
        ])->save();

        return response([], 200);
    }

    public function destroy($programId, $itemId)
    {
        $item = Item::with('media')->findOrFail($itemId);
        $item->delete();

        return response([], 200);
    }
}
