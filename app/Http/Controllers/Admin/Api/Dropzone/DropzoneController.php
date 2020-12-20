<?php

namespace App\Http\Controllers\Admin\Api\Dropzone;

use App\Handlers\DropzoneHandler;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DropzoneController extends Controller
{
    /**
     * @var DropzoneHandler
     */
    private $handler;

    public function __construct(DropzoneHandler $handler)
    {
        // $this->middleware('auth:admin');

        $this->handler = $handler;
    }

    /**
     * Dropzone 로딩시, 필요한 정보 전달
     *
     * @param Request $request
     * @return \Illuminate\Support\Collection
     */
    public function index(Request $request)
    {
        $data = $request->get('data');

        return $this->handler->getCurrentDropzoneImages($data);
    }

    /**
     * Dropzone 에서 public 스토리지에 임시 저장
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'file' => ['required', 'image']
        ]);

        $file = $request->file('file');

        try {
            $result = $this->handler->persist($file);
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }

        return response()->json($result);
    }

    public function destroy(Request $request)
    {
        $name = $request->get('name');
        if ($name) {
            $this->handler->delete($name);
        }

        return response([], 204);
    }
}
