<?php

namespace App\Http\Dtos\Elasticsearch;

class SearchDto
{
    private $index;
    private $introType;
    private $matchTypes;
    private $reward;
    private $localOnly;
    private $test;
    private $size;
    private $id;
    private $maxAge;
    private $minAge;
    private $maxHeight;
    private $minHeight;
    private $maxRatingScore;
    private $minRatingScore;
    private $lat;
    private $lng;
    private $distance;
    private $gender;

    public function __construct(
        string $index,
        string $introType,
        array $matchTypes,
        bool $reward,
        bool $localOnly,
        bool $test,
        int $size,
        int $id,
        int $maxAge,
        int $minAge,
        int $maxHeight,
        int $minHeight,
        float $maxRatingScore,
        float $minRatingScore,
        float $lat,
        float $lng,
        int $distance,
        string $gender
    ) {
        $this->index = $index;
        $this->introType = $introType;
        $this->matchTypes = $matchTypes;
        $this->reward = $reward;
        $this->localOnly = $localOnly;
        $this->test = $test;
        $this->size = $size;
        $this->id = $id;
        $this->maxAge = $maxAge;
        $this->minAge = $minAge;
        $this->maxHeight = $maxHeight;
        $this->minHeight = $minHeight;
        $this->maxRatingScore = $maxRatingScore;
        $this->minRatingScore = $minRatingScore;
        $this->lat = $lat;
        $this->lng = $lng;
        $this->distance = $distance;
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getIndex(): string
    {
        return $this->index;
    }

    /**
     * @return string
     */
    public function getIntroType(): string
    {
        return $this->introType;
    }

    /**
     * @return array
     */
    public function getMatchTypes(): array
    {
        return $this->matchTypes;
    }

    /**
     * @return bool
     */
    public function isReward(): bool
    {
        return $this->reward;
    }

    /**
     * @return bool
     */
    public function isLocalOnly(): bool
    {
        return $this->localOnly;
    }

    /**
     * @return bool
     */
    public function isTest(): bool
    {
        return $this->test;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getMaxAge(): int
    {
        return $this->maxAge;
    }

    /**
     * @return int
     */
    public function getMinAge(): int
    {
        return $this->minAge;
    }

    /**
     * @return int
     */
    public function getMaxHeight(): int
    {
        return $this->maxHeight;
    }

    /**
     * @return int
     */
    public function getMinHeight(): int
    {
        return $this->minHeight;
    }

    /**
     * @return float
     */
    public function getMaxRatingScore(): float
    {
        return $this->maxRatingScore;
    }

    /**
     * @return float
     */
    public function getMinRatingScore(): float
    {
        return $this->minRatingScore;
    }

    /**
     * @return float
     */
    public function getLat(): float
    {
        return $this->lat;
    }

    /**
     * @return float
     */
    public function getLng(): float
    {
        return $this->lng;
    }

    /**
     * @return int
     */
    public function getDistance(): int
    {
        return $this->distance;
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }
}
