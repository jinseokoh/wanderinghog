<?php

namespace App\Repositories\Elasticsearch;

use App\Repositories\ElasticRepository;

class RatingHandler
{
    /*
    private $builder;
    private $repo;
    private $highScore;

    public function __construct(
        ElasticDslBuilder $builder,
        ElasticRepository $repo
    ) {
        $this->builder = $builder;
        $this->repo = $repo;
        $this->highScore = config('constants.highScore');
    }

    public function addToExclusions($raterId, $rateeId)
    {
        $me = $raterId; // 내가
        $someone = $rateeId; // 평가한 사용자
        $params = $this->builder->setExclusionAddParams($me, $someone);

        return $this->repo->callUpdate($params);
    }

    public function addToHighRatings($raterId, $rateeId, $score)
    {
        if ($this->isUserRatedHigh($score)) {
            $me = $rateeId; // 나
            $someone = $raterId; // 나를 높게 평가한 사람
            $params = $this->builder->setHighRatingAddParams($me, $someone);

            return $this->repo->callUpdate($params);
        }

        return [
            "result" => "noop",
        ];
    }

    private function isUserRatedHigh(RatingAddDto $dto)
    {
        return ($dto->getScore() >= $this->highScore) ? true : false;
    }
    */
}
