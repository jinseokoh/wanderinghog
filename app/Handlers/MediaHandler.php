<?php

namespace App\Handlers;

use App\Models\User;
use App\Support\ImageMetaReader;
use Illuminate\Support\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaHandler
{
    // ================================================================================
    // avatars
    // ================================================================================

    /**
     * @param $user
     * @param UploadedFile $uploadedFile
     * @param array $properties
     * @return Media
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     * @throws \Exception
     */
    public function saveAvatarFromUploadedFile($user, UploadedFile $uploadedFile, array $properties = []): Media
    {
        $mimeType = $uploadedFile->getClientMimeType();
        $randomizedName = (new ImageMetaReader())->randomizedName($mimeType);
        $media = $user->addMedia($uploadedFile)
            ->withCustomProperties($properties)
            ->preservingOriginal()
            ->usingFileName($randomizedName)
            ->toMediaCollection('avatars');

        $user->update(['avatar' => $media->getFullUrl('thumb')]);

        return $media;
    }

    // ================================================================================
    // cards
    // ================================================================================

    /**
     * @param $user
     * @param UploadedFile $uploadedFile
     * @return Media
     * @throws \Exception
     */
    public function saveUserMediaFromUploadedFile($user, UploadedFile $uploadedFile, array $properties = []): Media
    {
        $mimeType = $uploadedFile->getClientMimeType();
        $randomizedName = (new ImageMetaReader())->randomizedName($mimeType);
        return $user->addMedia($uploadedFile)
            ->withCustomProperties($properties)
            ->preservingOriginal()
            ->usingFileName($randomizedName)
            ->toMediaCollection('cards');
    }

    // ================================================================================
    // general models
    // ================================================================================

    /**
     * @param mixed $model
     * @param UploadedFile $uploadedFile
     * @return Media
     * @throws \Exception
     */
    public function saveModelMediaFromUploadedFile($model, UploadedFile $uploadedFile, array $properties = []): Media
    {
        $collectionName = Str::plural(Str::snake(class_basename($model)));
        $mimeType = $uploadedFile->getClientMimeType();
        $randomizedName = (new ImageMetaReader())->randomizedName($mimeType);
        return $model->addMedia($uploadedFile)
            ->withCustomProperties($properties)
            ->preservingOriginal()
            ->usingFileName($randomizedName)
            ->toMediaCollection($collectionName);
    }

    /**
     * @param mixed $model
     * @param string $link
     * @return Media
     * @throws \Exception
     */
    public function saveModelMediaFromUrl($model, string $link): Media
    {
        $collectionName = Str::plural(Str::snake(class_basename($model)));
        $mimeType = $this->getMimeTypeOfRemoteFile(get_headers($link));
        $randomizedName = (new ImageMetaReader())->randomizedName($mimeType);
        return $model->addMediaFromUrl($link)
            ->usingFileName($randomizedName)
            ->toMediaCollection($collectionName);
    }

    // ================================================================================
    // photos
    // ================================================================================

    /**
     * @param mixed $user
     * @param UploadedFile $uploadedFile
     * @return Media
     * @throws \Exception
     */
    public function savePhotoMediaFromUploadedFile($user, UploadedFile $uploadedFile): Media
    {
        $mimeType = $uploadedFile->getClientMimeType();
        $randomizedName = (new ImageMetaReader())->randomizedName($mimeType);
        return $user->addMedia($uploadedFile)
            ->preservingOriginal()
            ->usingFileName($randomizedName)
            ->toMediaCollection('photos');
    }

    /**
     * @param int $id
     * @param mixed $user
     * @param UploadedFile $uploadedFile
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function replacePhotoMedia(int $id, $user, UploadedFile $uploadedFile): void
    {
        $medias = $user->getMedia('photos');
        if (! $this->checkIfCollectionContainsId($id, $medias)) {
            throw new \Exception('The image associated with the id is not found.');
        }

        $media = $this->pluckMediaFromCollection($id, $medias);
        $media->delete();

        $newMedia = $this->savePhotoMediaFromUploadedFile($user, $uploadedFile);
        $list = $this->replaceOldIdWithNewIdInTheListOfIds($medias, $id, $newMedia->id);
        Media::setNewOrder($list);
    }

    /**
     * @param int $id
     * @param mixed $user
     * @throws \Exception
     */
    public function removePhotoMediaById(int $id, $user): void
    {
        $medias = $user->getMedia('photos');

        if (! $this->checkIfCollectionContainsId($id, $medias)) {
            throw new \Exception('The image associated with the id is not found.');
        }

        $media = $this->pluckMediaFromCollection($id, $medias);
        $media->delete();
    }

    /**
     * @param int $id
     * @param User $user
     * @throws \Exception
     */
    public function reorderPhotoMedia(int $id, User $user): void
    {
        $medias = $user->getMedia('photos');

        if (! $this->checkIfCollectionContainsId($id, $medias)) {
            throw new \Exception('The image is not found.');
        }

        $list = $this->prependIdToTheListOfIds($id, $medias);
        Media::setNewOrder($list);
    }

    /**
     * @param $user
     * @return int
     */
    public function getPhotoMediaCount($user): int
    {
        return $user
            ->refresh()
            ->getMedia('photos')
            ->count();
    }

    // ================================================================================
    // helpers
    // ================================================================================

    private function getMimeTypeOfRemoteFile(array $headers): ?string
    {
        foreach ($headers as $header) {
            if (preg_match('/Content-Type:\s([a-z\-\/]+)/i', $header, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    private function checkIfCollectionContainsId(int $id, Collection $media): bool
    {
        return $media
            ->map(function ($item) {
                return $item->id;
            })
            ->contains($id);
    }

    private function pluckMediaFromCollection($id, Collection $media): Media
    {
        return $media
            ->first(function (Media $media) use ($id) {
                return $media->id === $id;
            });
    }

    private function replaceOldIdWithNewIdInTheListOfIds(Collection $media, int $oldId, int $newId): array
    {
        return $media
            ->map(function ($item) use ($oldId, $newId) {
                return ($item->id === $oldId) ? $newId : $item->id;
            })
            ->toArray();
    }

    private function prependIdToTheListOfIds(int $id, Collection $media): array
    {
        return $media
            ->filter(function ($item) use ($id) {
                return $item->id !== $id;
            })
            ->map(function ($item) {
                return $item->id;
            })
            ->prepend($id)
            ->toArray();
    }
}
