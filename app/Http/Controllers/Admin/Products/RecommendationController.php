<?php

namespace App\Http\Controllers\Admin\Products;

use App\Handlers\DropzoneHandler;
use App\Handlers\ProductHandler;
use App\Handlers\RecommendationHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecommendationStoreRequest;
use App\Models\Product;
use App\Models\Recommendation;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\MediaLibrary\Models\Media;

class RecommendationController extends Controller
{
    private $dropzoneHandler;
    private $productHandler;
    private $recommendationHandler;

    public function __construct(
        DropzoneHandler $dropzoneHandler,
        ProductHandler $productHandler,
        RecommendationHandler $recommendationHandler
    ) {
        $this->middleware('auth:admin');

        $this->dropzoneHandler = $dropzoneHandler;
        $this->productHandler = $productHandler;
        $this->recommendationHandler = $recommendationHandler;
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $products = $this->productHandler->fetch('id', [
            'categories',
            'recommendations',
        ]);

        return view('recommendations.index', compact('products'));
    }

    /**
     * @return Factory|View
     */
    public function create(int $pid)
    {
        $product = Product::find($pid);

        return view('recommendations.create', compact('product'));
    }

    /**
     * @param RecommendationStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(int $productId, RecommendationStoreRequest $request)
    {
        $product = Product::findOrFail($productId);
        $recommendation = Recommendation::make([
            'is_active' => true,
        ])->setTranslations('author', [
            'en' => $request->getAuthorEnglish(),
            'ko' => $request->getAuthor()
        ])->setTranslations('title', [
            'en' => $request->getTitleEnglish(),
            'ko' => $request->getTitle()
        ])->setTranslations('description', [
            'en' => $request->getDescriptionEnglish(),
            'ko' => $request->getDescription()
        ]);
        $product->recommendations()->save($recommendation);

        // media 저장
        foreach ($request->get('media', []) as $file) {
            $path = $this->dropzoneHandler->getLocalImagePath($file);
            $recommendation->addMedia($path)->toMediaCollection('recommendations');
            $this->dropzoneHandler->removeThumb($file);
        }

        $queryString = $request->getQueryString(); // for pagination

        return redirect("/admin/products/recommendations?".$queryString)->with('flash', 'success');
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Factory|View
     */
    public function edit(int $pid, int $rid)
    {
        $product = Product::find($pid);
        $recommendation = $this->recommendationHandler->findById($rid);

        return view('recommendations.edit', compact('product', 'recommendation'));
    }

    public function update(int $pid, int $rid, RecommendationStoreRequest $request)
    {
        /** @var Recommendation $recommendation */
        $recommendation = tap(Recommendation::find($rid))->update([
            'is_active' => $request->isActive(),
        ]);
        $recommendation->setTranslations('author', [
            'en' => $request->getAuthorEnglish(),
            'ko' => $request->getAuthor()
        ])->setTranslations('title', [
            'en' => $request->getTitleEnglish(),
            'ko' => $request->getTitle()
        ])->setTranslations('description', [
            'en' => $request->getDescriptionEnglish(),
            'ko' => $request->getDescription()
        ])->save();

        $originalMedia = $recommendation->images();
        $requestMedia = $request->get('media', []);

        $idsToBeDeleted = $this->getRemovedMediaIds($originalMedia, $requestMedia);
        if (count($idsToBeDeleted)) {
            Media::whereIn('id', $idsToBeDeleted)->delete();
        }

        // media 저장
        foreach ($requestMedia as $file) {
            if (strpos($file, 'http') !== 0) { // if $file does not begin with http
                $path = $this->dropzoneHandler->getLocalImagePath($file);
                $recommendation->addMedia($path)->toMediaCollection('recommendations');
                $this->dropzoneHandler->removeThumb($file);
            }
        }

        $queryString = $request->getQueryString(); // for pagination

        return redirect('/admin/products/recommendations?'.$queryString)->with('flash', 'success');
    }

    public function destroy($id)
    {
        //
    }

    // ================================================================================
    // private methods
    // ================================================================================

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
}
