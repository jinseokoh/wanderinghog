<?php

namespace App\Http\Controllers\Admin\Api\Products;

use App\Http\Requests\ReminderStoreRequest;
use App\Models\Reminder;
use App\Models\Product;
use App\Handlers\ProductHandler;
use App\Http\Controllers\Controller;

class ReminderController extends Controller
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

        return $product->reminders;
    }

    public function show(int $productId, int $reminderId)
    {
        $reminder = Reminder::find($reminderId);

        $payload = [
            'title' => $reminder->title,
            'description' => $reminder->description,
            'title_en' => $reminder->getTranslation('title','en'),
            'description_en' => $reminder->getTranslation('description','en'),
        ];

        return response()->json($payload);
    }

    public function store(int $productId, ReminderStoreRequest $request)
    {
        $product = Product::find($productId);
        $reminder = new Reminder;
        $reminder->setTranslations('title', [
            'en' => $request->getTitleEnglish(),
            'ko' => $request->getTitle()
        ])->setTranslations('description', [
            'en' => $request->getDescriptionEnglish(),
            'ko' => $request->getDescription()
        ]);
        $product->reminders()->save($reminder);

        return response([], 204);
    }

    public function update(int $productId, int $reminderId, ReminderStoreRequest $request)
    {
        \Log::info($productId);
        \Log::info($reminderId);
        \Log::info(print_r($request->all(), 1));

        /** @var Reminder $reminder */
        $reminder = Reminder::find($reminderId)->setTranslations('title', [
            'en' => $request->getTitleEnglish(),
            'ko' => $request->getTitle()
        ])->setTranslations('description', [
            'en' => $request->getDescriptionEnglish(),
            'ko' => $request->getDescription()
        ])->save();

        return response([], 200);
    }

    public function destroy(int $productId, int $reminderId)
    {
        $map = Reminder::findOrFail($reminderId);
        $map->delete();

        return response([], 200);
    }
}
