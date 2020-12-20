<?php

namespace App\Http\Dtos;

use JsonSerializable;

class VenueStoreDto implements JsonSerializable
{
    /**
     * @var string
     */
    private string $place_id;
    /**
     * @var string
     */
    private string $title;
    /**
     * @var string
     */
    private string $address;
    /**
     * @var string
     */
    private string $description;
    /**
     * @var float
     */
    private float $latitude;
    /**
     * @var float
     */
    private float $longitude;
    /**
     * @var array|null
     */
    private array $photo_refs;

    public function __construct(
        string $place_id,
        string $title,
        string $address,
        string $description,
        float $latitude,
        float $longitude,
        ?array $photo_refs
    ) {
        $this->place_id = $place_id;
        $this->title = $title;
        $this->address = $address;
        $this->description = $description;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->photo_refs = $photo_refs;
    }

    /**
     * @return string
     */
    public function getPlaceId(): string
    {
        return $this->place_id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @return array|null
     */
    public function getPhotoRefs(): ?array
    {
        return $this->photo_refs;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'place_id' => $this->getPlaceId(),
            'title' => $this->getTitle(),
            'address' => $this->getAddress(),
            'description' => $this->getDescription(),
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude(),
            'photo_refs' => $this->getPhotoRefs(),
        ];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
