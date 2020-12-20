<?php

namespace App\Http\Controllers\Admin\Api\Products;

use App\Http\Requests\ProgramStoreRequest;
use App\Models\Program;
use App\Models\Product;
use App\Handlers\ProductHandler;
use App\Http\Controllers\Controller;

class ProgramController extends Controller
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

    public function index(int $productId)
    {
        /** @var Product $product */
        $product = $this->productHandler->findById($productId);

        return $product->programs;
    }

    public function show(int $productId, int $programId)
    {
        $program = Program::findOrFail($programId);

        $payload = [
            'day' => $program->day,
            'title' => $program->title,
            'description' => $program->description,
            'title_en' => $program->getTranslation('title','en'),
            'description_en' => $program->getTranslation('description','en'),
        ];

        return response()->json($payload);
    }

    public function store(int $productId, ProgramStoreRequest $request)
    {
        $product = Product::findOrFail($productId);
        $program = Program::make([
            'day' => $request->getDay(),
        ])->setTranslations('title', [
            'en' => $request->getTitleEnglish(),
            'ko' => $request->getTitle()
        ])->setTranslations('description', [
            'en' => $request->getDescriptionEnglish(),
            'ko' => $request->getDescription()
        ]);
        $product->programs()->save($program);

        return response([], 204);
    }

    public function update(int $productId, int $programId, ProgramStoreRequest $request)
    {
        /** @var Program $program */
        $program = tap(Program::find($programId))->update([
            'day' => $request->getDay(),
        ]);
        $program->setTranslations('title', [
            'en' => $request->getTitleEnglish(),
            'ko' => $request->getTitle()
        ])->setTranslations('description', [
            'en' => $request->getDescriptionEnglish(),
            'ko' => $request->getDescription()
        ])->save();

        return response([], 200);
    }

    public function destroy(int $productId, int $programId)
    {
        $program = Program::find($programId);
        $program->delete();

        return response([], 200);
    }
}
