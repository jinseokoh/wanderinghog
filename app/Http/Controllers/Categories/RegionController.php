<?php

namespace App\Http\Controllers\Categories;

use App\Handlers\RegionHandler;
use App\Http\Controllers\Controller;
use App\Http\Resources\RegionResource;
use App\Http\Resources\RegionTreeResource;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * @param Request $request
     * @param RegionHandler $handler
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, RegionHandler $handler)
    {
        $slug = $request->input('slug');
        $category = ($slug) ? $handler->findNodeBySlug($slug) : $handler->findRoot();

        $collection = $category->descendants;

        return RegionResource::collection($collection);
    }

    /**
     * @param Request $request
     * @param RegionHandler $handler
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function tree(Request $request, RegionHandler $handler)
    {
        $slug = $request->get('slug');

        $category = ($slug) ? $handler->findNodeBySlug($slug) : $handler->findRoot();

        $collection = $category->descendants->toTree();

        return RegionTreeResource::collection($collection);
    }
}
