<?php

namespace App\Http\Dtos;

use JsonSerializable;

class AppointmentStoreDto implements JsonSerializable
{
    /**
     * @var int|null
     */
    private ?int $friend_id;
    /**
     * @var int|null
     */
    private ?int $relation_type;
    /**
     * @var int
     */
    private int $venue_id;
    /**
     * @var int
     */
    private int $theme_type;
    /**
     * @var string
     */
    private string $title;
    /**
     * @var string
     */
    private string $description;
    /**
     * @var array
     */
    private array $questions;
    /**
     * @var string
     */
    private string $expired_at;
    /**
     * @var int
     */
    private int $estimate;
    /**
     * @var string|null
     */
    private ?string $image_link;

    public function __construct(
        ?int $friend_id,
        ?int $relation_type,
        int $venue_id,
        int $theme_type,
        string $title,
        string $description,
        array $questions,
        string $expired_at,
        int $estimate,
        ?string $image_link
    ) {
        $this->friend_id = $friend_id;
        $this->relation_type = $relation_type;
        $this->venue_id = $venue_id;
        $this->theme_type = $theme_type;
        $this->title = $title;
        $this->description = $description;
        $this->questions = $questions;
        $this->expired_at = $expired_at;
        $this->estimate = $estimate;
        $this->image_link = $image_link;
    }

    /**
     * @return int|null
     */
    public function getFriendId(): ?int
    {
        return $this->friend_id;
    }

    /**
     * @return int|null
     */
    public function getRelationType(): ?int
    {
        return $this->relation_type;
    }

    /**
     * @return int
     */
    public function getVenueId(): int
    {
        return $this->venue_id;
    }

    /**
     * @return int
     */
    public function getThemeType(): int
    {
        return $this->theme_type;
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
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function getQuestions(): array
    {
        return $this->questions;
    }

    /**
     * @return string
     */
    public function getExpiredAt(): string
    {
        return $this->expired_at;
    }

    /**
     * @return int
     */
    public function getEstimate(): int
    {
        return $this->estimate;
    }

    /**
     * @return string|null
     */
    public function getImageLink(): ?string
    {
        return $this->image_link;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'friend_id' => $this->getFriendId(),
            'relation_type' => $this->getRelationType(),
            'venue_id' => $this->getVenueId(),
            'theme_type' => $this->getThemeType(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'questions' => $this->getQuestions(),
            'expired_at' => $this->getExpiredAt(),
            'estimate' => $this->getEstimate(),
            'image_link' => $this->getImageLink(),
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
