<?php

namespace App\Http\Controllers\Admin\Api\Products;

use App\Http\Requests\MapStoreRequest;
use App\Models\Map;
use App\Models\Product;
use App\Handlers\ProductHandler;
use App\Http\Controllers\Controller;

class MapController extends Controller
{
    /**
     * @var ProductHandler
     */
    private $productHandler;

    public function __construct(ProductHandler $productHandler)
    {
        $this->middleware('auth:admin');

        $this->productHandler = $productHandler;
    }

    public function index(int $id)
    {
        /** @var Product $product */
        $product = $this->productHandler->findById($id);

        return $product->maps;
    }

    public function show(int $productId, int $mapId)
    {
        $map = Map::find($mapId);

        $payload = [
            'day' => $map->day,
            'title' => $map->title,
            'description' => $map->description,
            'title_en' => $map->getTranslation('title','en'),
            'description_en' => $map->getTranslation('description','en'),
        ];

        return response()->json($payload);

        // return new MapResource($map);
    }

    public function store(int $productId, MapStoreRequest $request)
    {
        $product = Product::findOrFail($productId);
        $map = Map::make([
            'day' => $request->getDay(),
        ])->setTranslations('title', [
            'en' => $request->getTitleEnglish(),
            'ko' => $request->getTitle()
        ])->setTranslations('description', [
            'en' => $request->getDescriptionEnglish(),
            'ko' => $request->getDescription()
        ]);
        $product->maps()->save($map);

        $product = $this->productHandler->findById($productId);

        return response()->json($product->maps, 200);
    }

    public function update(int $productId, int $mapId, MapStoreRequest $request)
    {
        /** @var Map $map */
        $map = tap(Map::find($mapId))->update([
            'day' => $request->getDay(),
        ]);
        $map->setTranslations('title', [
            'en' => $request->getTitleEnglish(),
            'ko' => $request->getTitle()
        ])->setTranslations('description', [
            'en' => $request->getDescriptionEnglish(),
            'ko' => $request->getDescription()
        ])->save();

        $product = $this->productHandler->findById($productId);

        return response()->json($product->maps, 200);
    }

    public function destroy(int $productId, int $mapId)
    {
        $map = Map::findOrFail($mapId);
        $map->delete();

        $product = $this->productHandler->findById($productId);

        return response()->json($product->maps, 200);
    }
}
