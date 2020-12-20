<?php

namespace App\Http\Controllers\Admin\Products;

use App\Handlers\CategoryHandler;
use App\Handlers\DropzoneHandler;
use App\Handlers\KeywordHandler;
use App\Handlers\LanguageHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Models\Accommodation;
use App\Models\Flight;
use App\Models\Meal;
use App\Models\Product;
use App\Models\Ticket;
use App\Models\TourGuide;
use App\Models\TourService;
use App\Models\Transportation;
use App\Handlers\ProductHandler;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\MediaLibrary\Models\Media;

class ProductController extends Controller
{
    private $categoryHandler;
    private $keywordHandler;
    private $languageHandler;
    private $dropzoneHandler;
    private $productHandler;

    public function __construct(
        CategoryHandler $categoryHandler,
        KeywordHandler $keywordHandler,
        LanguageHandler $languageHandler,
        DropzoneHandler $dropzoneHandler,
        ProductHandler $productHandler
    ) {
        $this->middleware('auth:admin');

        $this->categoryHandler = $categoryHandler;
        $this->keywordHandler = $keywordHandler;
        $this->languageHandler = $languageHandler;
        $this->dropzoneHandler = $dropzoneHandler;
        $this->productHandler = $productHandler;
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function index()
    {
        $products = $this->productHandler->fetch('id', [], false);

        return view('products.index', compact('products'));
    }

    /**
     * @param int $id
     * @return Factory|View
     */
    public function show(int $id)
    {
        /** @var Product $product */
        $product = $this->productHandler->findById($id);

        $keywords = implode(', ', $product->keywords->pluck('name')->toArray());
        $languages = implode(', ', $product->languages->pluck('name')->toArray());
        $services = $this->productHandler->getServices($product);

        return view('products.show', compact(
            'product',
            'keywords',
            'languages',
            'services'
        ));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * @param ProductStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProductStoreRequest $request)
    {
        $payload = [
            'code' => $this->categoryHandler->getProductCode($request->getCategories())
        ] + [
            'departure' => $request->getDeparture(),
            'trip_pace' => $request->getTripPace(),
            'nights' => $request->getNights(),
            'days' => $request->getDays(),
            'base_price' => $request->getBasePrice(),
        ];

        $product = Product::make($payload)
            ->setTranslations('title', [
                'en' => $request->getTitleEnglish(),
                'ko' => $request->getTitle()
            ])->setTranslations('subtitle', [
                'en' => $request->getSubtitleEnglish(),
                'ko' => $request->getSubtitle()
            ])->setTranslations('description', [
                'en' => $request->getDescriptionEnglish(),
                'ko' => $request->getDescription()
            ]);

        $admin = $request->user('admin');
        $admin->products()->save($product);

        // media 저장
        foreach ($request->get('media', []) as $file) {
            $path = $this->dropzoneHandler->getLocalImagePath($file);
            $product->addMedia($path)->toMediaCollection('products');
            $this->dropzoneHandler->removeThumb($file);
        }

        // relations 저장
        $this->buildRelations($product, $request);

        return redirect('/admin/products')->with('flash', 'success');
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Factory|View
     */
    public function edit(int $id)
    {
        $product = $this->productHandler->findById($id);

        $categories = $product->categories->pluck('slug')->toArray();
        $keywords = $product->keywords->pluck('slug')->toArray();

        $accommodations = $product->accommodations->pluck('context')->toArray();
        $flights = $product->flights->pluck('context')->toArray();
        $meals = $product->meals->pluck('context')->toArray();
        $tickets = $product->tickets->pluck('context')->toArray();
        $transportation = $product->transportation->pluck('context')->toArray();
        $tourGuides = $product->tourGuides->pluck('context')->toArray();
        $tourServices = $product->tourServices->pluck('context')->toArray();

        // return view('products.edit', []);
        return view('products.edit', compact(
            'product',
            'categories',
            'keywords',
            'accommodations',
            'flights',
            'meals',
            'tickets',
            'transportation',
            'tourGuides',
            'tourServices'
        ));
    }

    private function getRemovedMediaIds($originalMedia, $requestMedia)
    {
        $removedIds = [];

        foreach ($originalMedia as $media) {
            if (! in_array($media['src'], $requestMedia)) {
                $removedIds[] = $media['id'];
            }
        }

        return $removedIds;
    }

    public function update(int $id, ProductStoreRequest $request)
    {
        /** @var Product $product */
        $product = tap(Product::find($id))->update([
            'code' => $request->getCode(),
            'departure' => $request->getDeparture(),
            'trip_pace' => $request->getTripPace(),
            'nights' => $request->getNights(),
            'days' => $request->getDays(),
            'base_price' => $request->getBasePrice(),
            'is_active' => $request->isActive(),
        ]);
        $product->setTranslations('title', [
                'en' => $request->getTitleEnglish(),
                'ko' => $request->getTitle()
            ])->setTranslations('subtitle', [
                'en' => $request->getSubtitleEnglish(),
                'ko' => $request->getSubtitle()
            ])->setTranslations('description', [
                'en' => $request->getDescriptionEnglish(),
                'ko' => $request->getDescription()
            ])->save();

        $originalMedia = $product->images();
        $requestMedia = $request->get('media', []);

        $idsToBeDeleted = $this->getRemovedMediaIds($originalMedia, $requestMedia);
        if (count($idsToBeDeleted)) {
            Media::whereIn('id', $idsToBeDeleted)->delete();
        }

        // media 저장
        foreach ($requestMedia as $file) {
            if (strpos($file, 'http') !== 0) { // if $file does not begin with http
                $path = $this->dropzoneHandler->getLocalImagePath($file);
                $product->addMedia($path)->toMediaCollection('products');
                $this->dropzoneHandler->removeThumb($file);
            }
        }

        // relations 저장
        $this->removeServiceRelations($product);
        $this->buildRelations($product, $request);

        return redirect('/admin/products/'.$id)->with('flash', 'success');
    }

    public function destroy($id)
    {
        //
    }

    // ================================================================================
    // private methods
    // ================================================================================

    private function buildRelations(Product $product, ProductStoreRequest $request)
    {
        $categoryIds = $this->categoryHandler->getFamilyBranchIds($request->getCategories());
        $product->categories()->sync($categoryIds);
        $keywordIds = $this->keywordHandler->getIds($request->getKeywords());
        $product->keywords()->sync($keywordIds);
        $languageIds = $this->languageHandler->getIds($request->getLanguages());
        $product->languages()->sync($languageIds);

        foreach ($request->getAccommodations() as $context) {
            $accommodation = new Accommodation();
            $accommodation->context = $context;
            $product->accommodations()->save($accommodation);
        }
        foreach ($request->getFlights() as $context) {
            $flight = new Flight();
            $flight->context = $context;
            $product->flights()->save($flight);
        }
        foreach ($request->getTransportation() as $context) {
            $transportation = new Transportation();
            $transportation->context = $context;
            $product->transportation()->save($transportation);
        }
        foreach ($request->getMeals() as $context) {
            $meal = new Meal();
            $meal->context = $context;
            $product->meals()->save($meal);
        }
        foreach ($request->getTickets() as $context) {
            $ticket = new Ticket();
            $ticket->context = $context;
            $product->tickets()->save($ticket);
        }
        foreach ($request->getTourGuides() as $context) {
            $tourGuide = new TourGuide();
            $tourGuide->context = $context;
            $product->tourGuides()->save($tourGuide);
        }
        foreach ($request->getTourServices() as $context) {
            $tourService = new TourService();
            $tourService->context = $context;
            $product->tourServices()->save($tourService);
        }
    }

    private function removeServiceRelations(Product $product)
    {
        $product->accommodations()->delete();
        $product->flights()->delete();
        $product->transportation()->delete();
        $product->meals()->delete();
        $product->tickets()->delete();
        $product->tourGuides()->delete();
        $product->tourServices()->delete();
    }
}
