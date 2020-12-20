<?php

namespace App\Handlers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Storage;

class DropzoneHandler
{
    private $localPath;
    private $urlPath;

    public function __construct()
    {
        $this->localPath = storage_path('app/public/dropzone');
        $this->urlPath = 'dropzone';
    }

    public function getCurrentDropzoneImages(array $data)
    {
        return collect($data)->map(function ($name) {
            return [
                [
                    'processing' => true,
                    'accepted' => true,
                    'name' => $name,
                    'size' => filesize($this->localPath.'/'.$name),
                    'type' => mime_content_type($this->localPath.'/'.$name),
                ],
                $this->getThumbnailUrl($name),
                [
                    'status' => 'success',
                    'name' => $name
                ]
            ];
        });
    }

    public function persist(UploadedFile $file): array
    {
        if (! file_exists($this->localPath)) {
            try {
                mkdir($this->localPath, 0777, true);
            } catch (\Exception $e) {
                // let it go
                \Log::info($e->getMessage());
            }
        }

        $randomString = Str::random(32);
        $extension = $file->getClientOriginalExtension();
        $size = $file->getSize();
        $imageName = $randomString . '.' . $extension;
        $thumbName = $randomString . '-xs.jpg';

        $file->move($this->localPath, $imageName);
        $this->generateThumbnail($imageName, $thumbName);

        return [
            'name' => $imageName,
            'size' => $size,
        ];
    }

    public function delete(string $name)
    {
        $this->removeImage($name);
        $this->removeThumb($name);
    }

    public function getLocalImagePath(string $name): string
    {
        return $this->localPath . '/' . $name;
    }

    public function removeImage(string $name)
    {
        $imagePath = $this->getLocalImagePath($name);
        unlink($imagePath);
    }

    public function removeThumb(string $name)
    {
        $thumbPath = $this->getLocalImagePath($this->getThumbnailNameFromImageName($name));
        unlink($thumbPath);
    }

    // ================================================================================
    // private methods
    // ================================================================================

    private function generateThumbnail($imageName, $thumbName): void
    {
        try {
            Image::make($this->getLocalImagePath($imageName))
                ->resize(200,200)
                ->save($this->getLocalImagePath($thumbName));
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }
    }

    private function getThumbnailUrl($imageName): string
    {
        return Storage::disk('public')->url(
            $this->urlPath . '/' . $this->getThumbnailNameFromImageName($imageName)
        );
    }

    private function getThumbnailNameFromImageName(string $imageName): string
    {
        return preg_replace('/\..+/', null, $imageName).'-xs.jpg';
    }
}
