<?php

namespace App\Http\Dtos;

use Illuminate\Http\UploadedFile;

class ExperienceStoreDto
{
    /**
     * @var string|null
     */
    private ?string $title;
    /**
     * @var array
     */
    private array $locations;
    /**
     * @var array
     */
    private array $tags;
    /**
     * @var UploadedFile
     */
    private UploadedFile $image;

    public function __construct(
        ?string $title,
        array $locations,
        array $tags,
        UploadedFile $image
    ) {
        $this->title = $title;
        $this->locations = $locations;
        $this->tags = $tags;
        $this->image = $image;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return array
     */
    public function getLocations(): array
    {
        return $this->locations;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return UploadedFile
     */
    public function getImage(): UploadedFile
    {
        return $this->image;
    }
}
