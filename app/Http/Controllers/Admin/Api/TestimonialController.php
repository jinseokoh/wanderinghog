<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Testimonial;
use App\Http\Resources\Testimonial as TestimonialResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

// save it for reference purpose only
class TestimonialController extends Controller
{
    /**
     * 리스트 조회
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        /** @var LengthAwarePaginator $testimonials */
        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('id', 'DESC')
            ->paginate(5);

        return TestimonialResource::collection($testimonials)
            ->response();
    }

    /**
     * 생성
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        $user = $request->user('admin');
        $title = $request->get('title');
        $tags = $request->get('tags');
        $url_name = $request->get('url_name');
        $url = $request->get('url');
        $body = $request->get('body');
        $is_active = $request->get('is_active');
        $result = preg_match('/\[image\]\((.*?)\)/', $body, $matches);
        $tags = preg_replace('/\s*,\s*/', ',', trim($tags)); // sanitization

        $testimonial = new Testimonial();
        $testimonial->title = $title;
        $testimonial->tags = $tags;
        $testimonial->url_name = $url_name;
        $testimonial->url = $url;
        $testimonial->body = $body;
        $testimonial->is_active = $is_active;
        if ($result) {
            $testimonial->image = $matches[1];
        }

        $user->testimonials()->save($testimonial);

        return response()->json([
            'data' => true,
            'testimonial_id' => $testimonial->id,
        ]);
    }

    /**
     * 수정
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        $user = $request->user('admin');
        $title = $request->get('title');
        $tags = $request->get('tags');
        $url_name = $request->get('url_name');
        $url = $request->get('url');
        $body = $request->get('body');
        $is_active = $request->get('is_active');
        $result = preg_match('/\[image\]\((.*?)\)/', $body, $matches);
        $tags = preg_replace('/\s*,\s*/', ',', trim($tags)); // sanitization

        $testimonial = Testimonial::find($id);
        $testimonial->title = $title;
        $testimonial->tags = $tags;
        $testimonial->url_name = $url_name;
        $testimonial->url = $url;
        $testimonial->body = $body;
        $testimonial->is_active = $is_active;
        if ($result) {
            $testimonial->image = $matches[1];
        }

        $user->testimonials()->save($testimonial);

        return response()->json([
            'data' => true,
            'testimonial_id' => $testimonial->id,
        ]);
    }
}
