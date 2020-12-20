<?php

namespace App\Support;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class MediaPathGenerator implements PathGenerator
{
    /**
     * Get the path for the given media, relative to the root storage path.
     */
    public function getPath(Media $media): string
    {
        return $this->getPathPrefix($media).
            $this->getBasePath($media).
            '/';
    }

    /**
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getPathPrefix($media).
            $this->getBasePath($media).
            '/';
    }

    /*
     * Get the path for responsive images of the given media, relative to the root storage path.
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPathPrefix($media).
            $this->getBasePath($media).
            '/';
    }

    // ================================================================================
    // helpers
    // ================================================================================

    protected function getBasePath(Media $media): string
    {
        return $media->getKey();
    }

    protected function getPathPrefix(Media $media): string
    {
        return config('app.env').
            '-'.
            $media->collection_name.
            '/';
    }
}
