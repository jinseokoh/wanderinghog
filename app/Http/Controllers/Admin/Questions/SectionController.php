<?php

namespace App\Http\Controllers\Admin\Questions;

use App\Handlers\DropzoneHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\SectionStoreRequest;
use App\Models\Magazine;
use App\Handlers\MagazineHandler;
use App\Models\Section;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Spatie\MediaLibrary\Models\Media;

class SectionController extends Controller
{
    private $dropzoneHandler;
    private $magazineHandler;

    public function __construct(
        DropzoneHandler $dropzoneHandler,
        MagazineHandler $magazineHandler
    ) {
        $this->middleware('auth:admin');

        $this->dropzoneHandler = $dropzoneHandler;
        $this->magazineHandler = $magazineHandler;
    }

    /**
     * @return Factory|View
     */
    public function create(int $mid) ///
    {
        $magazine = Magazine::findOrFail($mid);

        $order = request()->input('order', 1);

        return view('magazines.sections.create', compact('magazine', 'order'));
    }

    /**
     * @param SectionStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store($mid, SectionStoreRequest $request)
    {
        $payload = [
                'order' => $request->getOrder(),
                'title' => $request->getTitle(),
                'body' => $request->getBody(),
            ];
        $magazine = Magazine::findOrFail($mid);
        $section = Section::make($payload);
        $magazine->sections()->save($section);

        // media 저장
        foreach ($request->get('media', []) as $file) {
            $path = $this->dropzoneHandler->getLocalImagePath($file);
            $section->addMedia($path)->toMediaCollection('sections');
            $this->dropzoneHandler->removeThumb($file);
        }

        return redirect('/admin/magazines/'.$mid)->with('flash', 'success');
    }

    /**
     * @param int $mid
     * @param int $sid
     * @return Factory|View
     */
    public function edit(int $mid, int $sid)
    {
        $magazine = Magazine::findOrFail($mid);
        $section = $magazine->sections()->findOrFail($sid);

        return view('magazines.sections.edit', compact(
            'magazine',
            'section'
        ));
    }

    public function update(int $mid, int $sid, SectionStoreRequest $request)
    {
        $payload = [
            'order' => $request->getOrder(),
            'title' => $request->getTitle(),
            'body' => $request->getBody(),
        ];
        $section = tap(Section::find($sid))->update($payload);

        $originalMedia = $section->images();
        $requestMedia = $request->get('media', []);
        $idsToBeDeleted = $this->getRemovedMediaIds($originalMedia, $requestMedia);
        if (count($idsToBeDeleted)) {
            Media::whereIn('id', $idsToBeDeleted)->delete();
        }

        // media 저장
        foreach ($requestMedia as $file) {
            if (strpos($file, 'http') !== 0) { // if $file does not begin with http
                $path = $this->dropzoneHandler->getLocalImagePath($file);
                $section->addMedia($path)->toMediaCollection('sections');
                $this->dropzoneHandler->removeThumb($file);
            }
        }

        return redirect('/admin/magazines/'.$mid)->with('flash', 'success');
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
