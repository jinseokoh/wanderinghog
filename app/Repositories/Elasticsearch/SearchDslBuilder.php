<?php

namespace App\Repositories\Elasticsearch;

use App\Http\Dtos\Elasticsearch\SearchDto;
use Illuminate\Support\Carbon;

class SearchDslBuilder
{
    private $filters;
    private $sorts;
    private $terms;
    private $dto;

    public function __construct(SearchDto $dto)
    {
        $this->dto = $dto;
    }

    // ================================================================================
    // term filters
    // ================================================================================

    /**
     * CandidateSearchDto 로 전달한 매칭타입 조건 배열을 사용하며, 해당 조건들은 모두 AND 로 필터링한다.
     *
     * @return $this
     */
    public function addTermFilterForMatchTypes()
    {
        if ($this->isMatchTypeSearch()) {
            foreach ($this->dto->getMatchTypes() as $matchType) {
                $this->filters[] = [
                    'term' => [
                        'match_types' => $matchType
                    ]
                ];
            }
        }

        return $this;
    }

    /**
     * CandidateSearchDto 로 전달한 Gender 정보로 필터링한다.
     *
     * @return $this
     */
    public function addTermFilterForGender()
    {
        $this->filters[] = [
            'term' => [
                'gender' => $this->dto->getGender()
            ]
        ];

        return $this;
    }

    /**
     * CandidateSearchDto 로 전달한 EtcState 정보로 필터링한다.
     *
     * @return $this
     */
    public function addTermFilterForEtcState()
    {
        if ($this->isThisOneOfAdditionalIntroTypes()) {
            $this->filters[] = [
                'term' => [
                    'etc_state' => false
                ]
            ];
        }

        return $this;
    }

    /**
     * CandidateSearchDto 로 전달한 Active 정보로 필터링한다.
     *
     * @return $this
     */
    public function addTermFilterForActiveFlag()
    {
        $this->filters[] = [
            'term' => [
                'active' => true
            ]
        ];

        return $this;
    }

    /**
     * CandidateSearchDto 로 전달한 JoinActive 정보로 필터링한다.
     *
     * @return $this
     */
    public function addTermFilterForJoinActiveFlag()
    {
        $this->filters[] = [
            'term' => [
                'join_active' => true
            ]
        ];

        return $this;
    }

    // ================================================================================
    // range filters
    // ================================================================================

    /**
     * 한국식/서양식 나이계산 보정은 Dto 로 전달하기 전에 미리 계산하여 전달한다.
     *
     * @return $this
     */
    public function addRangeFilterForAge()
    {
        $dt = Carbon::now();
        $start = $dt->copy()->subYears($this->dto->getMinAge())->endOfYear()->toDateString();
        $end = $dt->copy()->subYears($this->dto->getMaxAge())->startOfYear()->toDateString();

        $this->filters[] = [
            'range' => [
                'birthday' => [
                    'gte' => $end,
                    'lte' => $start,
                ]
            ]
        ];

        return $this;
    }

    /**
     * @return $this
     */
    public function addRangeFilterForHeight()
    {
        if (! $this->isMatchTypeSearchForHeight()) {
            $this->filters[] = [
                'range' => [
                    'height' => [
                        'gte' => $this->dto->getMinHeight(),
                        'lte' => $this->dto->getMaxHeight(),
                    ]
                ]
            ];
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function addRangeFilterForRatingScore()
    {
        if (! $this->isMatchTypeSearchForDiamond()) {
            $this->filters[] = [
                'range' => [
                    'rating_score' => [
                        'gte' => $this->dto->getMinRatingScore(),
                        'lte' => $this->dto->getMaxRatingScore()
                    ]
                ]
            ];
        }

        return $this;
    }

    // ================================================================================
    // geo distance filters
    // ================================================================================

    /**
     * @return $this
     */
    public function addGeoDistanceFilterForGeoCoordinates()
    {
        if ($this->dto->isLocalOnly()) {
            $this->filters[] = [
                'geo_distance' => [
                    'distance' => strval($this->dto->getDistance()) . "km",
                    'distance_type' => 'plane',
                    'location' => [
                        'lat' => $this->dto->getLat(),
                        'lon' => $this->dto->getLng(),
                    ]
                ]
            ];
        }

        return $this;
    }

    // ================================================================================
    // terms filters
    // ================================================================================

    /**
     * CandidateSearchDto 로 전달한 id 에게 제외해야할 사용자들을 필터링
     *
     * @return $this
     */
    public function addTermsFilterForExclusions()
    {
        $this->terms[] = [
            'terms' => [
                'id' => [
                    'index' => 'exclusions',
                    'type' => 'doc',
                    'id' => $this->dto->getId(),
                    'path' => 'list',
                ]
            ],
        ];

        return $this;
    }

    /**
     * CandidateSearchDto 로 전달한 id 에게 제외해야할 과평점 사용자들을 필터링
     *
     * @return $this
     */
    public function addTermsFilterForHighRatings()
    {
        if ($this->isThisOneOfAdditionalIntroTypes()) {
            $this->terms[] = [
                'terms' => [
                    'id' => [
                        'index' => 'high_ratings',
                        'type' => 'doc',
                        'id' => $this->dto->getId(),
                        'path' => 'list',
                    ]
                ],
            ];
        }

        return $this;
    }

    /**
     * 소개 제한수를 넘은 사용자들을 필터링
     *
     * @return $this
     */
    public function addTermsFilterForIntroLimits()
    {
        if ($this->isRegularMatchLimitCheckNeeded()) {
            $this->terms[] = [
                'terms' => [
                    'id' => [
                        'index' => 'intro_limits',
                        'type' => 'doc',
                        'id' => 100,
                        'path' => 'list',
                    ]
                ],
            ];
        } elseif ($this->isAdditionalMatchLimitCheckNeeded()) {
            $this->terms[] = [
                'terms' => [
                    'id' => [
                        'index' => 'intro_limits',
                        'type' => 'doc',
                        'id' => 300,
                        'path' => 'list',
                    ]
                ],
            ];
        }

        return $this;
    }

    // ================================================================================
    // sort conditions
    // ================================================================================

    public function addSortConditions()
    {
        $this->sorts = [
            [
                '_geo_distance' => [
                    'location' => [
                        'lat' => $this->dto->getLat(),
                        'lon' => $this->dto->getLng(),
                    ],
                    'order' => 'asc',
                    'unit' => 'm'
                ]
            ],
            [
                'rating_score' => [
                    'order' => 'desc'
                ]
            ]

        ];

        return $this;
    }

    // ================================================================================
    // DSL params
    // ================================================================================

    /**
     * @return array
     */
    public function getCandidateSearchParams()
    {
        return [
            'index' => $this->dto->getIndex(),
            'type' => 'doc',
            'body' => [
                'size' => $this->dto->getSize(),
                'query' => [
                    'bool' => [
                        'must_not' => $this->terms,
                        'filter' => $this->filters
                    ]
                ],
                // '_source' => ['id', 'name', 'rating_score'],
                'sort' => $this->sorts
            ]
        ];
    }

    // ================================================================================
    // private methods (conditions)
    // ================================================================================

    /**
     * @return bool
     */
    private function isRegularMatchLimitCheckNeeded(): bool
    {
        if ($this->dto->isReward()) {
            return false;
        }

        switch ($this->dto->getIntroType()) {
            case IntroductionType::Join:
            case IntroductionType::Additional:
            case IntroductionType::AdditionalWithType:
            case IntroductionType::Fill:
            case IntroductionType::FillFromNew:
                return false;
            default:
                return true;
        }
    }

    /**
     * @return bool
     */
    private function isAdditionalMatchLimitCheckNeeded(): bool
    {
        if ($this->dto->isReward()) {
            return false;
        }

        switch ($this->dto->getIntroType()) {
            case IntroductionType::Join:
            case IntroductionType::Additional:
            case IntroductionType::Fill:
            case IntroductionType::FillFromNew:
                return true;
            case IntroductionType::AdditionalWithType:
                if (in_array(MatchFilterType::FemaleDiamond, $this->dto->getMatchTypes()) ||
                    in_array(MatchFilterType::MaleDiamond, $this->dto->getMatchTypes())
                ) {
                    return false;
                }
                return true;
            default:
                return false;
        }
    }

    /**
     * 추가소개 타입들 중 하나입니까?
     *
     * @return bool
     */
    private function isThisOneOfAdditionalIntroTypes(): bool
    {
        switch ($this->dto->getIntroType()) {
            case IntroductionType::Additional:
            case IntroductionType::AdditionalWithType:
            case IntroductionType::Fill:
            case IntroductionType::FillFromNew:
            case IntroductionType::Irregular:
                return true;
            default:
                return false;
        }
    }

    /**
     * 다이아몬드 조건 맞춤소개 입니까?
     *
     * @return bool
     */
    private function isMatchTypeSearchForDiamond(): bool
    {
        if ($this->dto->getIntroType() == IntroductionType::AdditionalWithType) {
            if (in_array(MatchFilterType::FemaleDiamond, $this->dto->getMatchTypes())) {
                return true;
            }
            if (in_array(MatchFilterType::MaleDiamond, $this->dto->getMatchTypes())) {
                return true;
            }
        }

        return false;
    }

    /**
     * 키 조건 맞춤소개 입니까?
     *
     * @return bool
     */
    private function isMatchTypeSearchForHeight(): bool
    {
        if ($this->dto->getIntroType() == IntroductionType::AdditionalWithType) {
            if (in_array(MatchFilterType::FemaleLow, $this->dto->getMatchTypes())) {
                return true;
            }
            if (in_array(MatchFilterType::FemaleHigh, $this->dto->getMatchTypes())) {
                return true;
            }
            if (in_array(MatchFilterType::MaleHigh, $this->dto->getMatchTypes())) {
                return true;
            }
        }

        return false;
    }

    /**
     * 맞춤소개 입니까?
     *
     * @return bool
     */
    private function isMatchTypeSearch(): bool
    {
        if ($this->dto->getIntroType() == IntroductionType::AdditionalWithType) {
            if (count($this->dto->getMatchTypes()) > 0) {
                return true;
            }
        }

        return false;
    }

}
