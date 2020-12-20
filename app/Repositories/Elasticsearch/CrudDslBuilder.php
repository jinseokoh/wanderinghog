<?php

namespace App\Repositories\Elasticsearch;

use App\Dtos\ExclusionListDto;
use App\Dtos\HighRatingListDto;
use App\Dtos\UserUpsertDto;

class CrudDslBuilder
{
    // this value can be adjusted depending on the # of 409 errors we will get
    const RETRY_TIMES = 3;

    // ================================================================================
    // 일반 검색용
    // ================================================================================

    /**
     * GET
     *
     * 일반 검색용 (사용자, 제외 리스트, ...)
     *
     * @param $index
     * @param $id
     * @return array
     */
    public function setGeneralGetParams($index, $id)
    {
        return [
            'id' => $id,
            'index' => $index,
            'type' => 'doc',
        ];
    }

    // ================================================================================
    // 사용자 리스트
    // ================================================================================

    /**
     * INDEX (using external versioning)
     *
     * 사용자 저장용 (외부 버저닝 사용)
     *
     * @param UserUpsertDto $dto
     * @return array
     */
    public function setUserIndexParams(UserUpsertDto $dto)
    {
        $timestamp = time();
        $matchTypes = count($dto->getMatchTypes()) > 0 ? $dto->getMatchTypes() : [];
        sort($matchTypes);

        return [
            'id'    => $dto->getId(),
            'index' => $dto->getIndex(),
            'type'  => 'doc',
            'version' => $timestamp,
            'version_type' => 'external',
            'body' => [
                'id'           => $dto->getId(),
                'name'         => $dto->getName(),
                'gender'       => $dto->getGender(),
                'location'     => $dto->getLocation(),
                'birthday'     => $dto->getBirthday(),
                'rating_score' => $dto->getRatingScore(),
                'height'       => $dto->getHeight(),
                'body_type'    => $dto->getBodyTypeCode(),
                'drinking'     => $dto->getDrinkingCode(),
                'smoking'      => $dto->getSmokingCode(),
                'religion'     => $dto->getReligionCode(),
                'region'       => $dto->getRegionCode(),
                'etc_state'    => $dto->isEtcStateFlag(),
                'active'       => $dto->isActiveFlag(),
                'join_active'  => $dto->isJoinActiveFlag(),
                'match_types'  => $matchTypes,
            ]
        ];
    }

    /**
     * BULK
     *
     * 사용자 벌크 저장용
     *
     * @param UserUpsertDto $dto
     * @param array $params
     * @return array
     */
    public function setUserBulkParams(UserUpsertDto $dto, array &$params)
    {
        $matchTypes = count($dto->getMatchTypes()) > 0 ? $dto->getMatchTypes() : [];
        sort($matchTypes);

        $params['body'][] = [
            'index' => [
                '_id'    => $dto->getId(),
                '_index' => $dto->getIndex(),
                '_type'  => 'doc',
            ]
        ];
        $params['body'][] = [
            'id'           => $dto->getId(),
            'name'         => $dto->getName(),
            'gender'       => $dto->getGender(),
            'location'     => $dto->getLocation(),
            'birthday'     => $dto->getBirthday(),
            'rating_score' => $dto->getRatingScore(),
            'height'       => $dto->getHeight(),
            'body_type'    => $dto->getBodyTypeCode(),
            'drinking'     => $dto->getDrinkingCode(),
            'smoking'      => $dto->getSmokingCode(),
            'religion'     => $dto->getReligionCode(),
            'region'       => $dto->getRegionCode(),
            'etc_state'    => $dto->isEtcStateFlag(),
            'active'       => $dto->isActiveFlag(),
            'join_active'  => $dto->isJoinActiveFlag(),
            'match_types'  => $matchTypes,
        ];

        return $params;
    }

    /**
     * UPDATE (부분 변경)
     *
     * 사용자 정보중 active 를 변경
     *
     * @param int $id
     * @param bool $val
     * @return array
     */
    public function setUserUpdateActiveParams(int $id, bool $val)
    {
        return [
            'id' => $id,
            'index' => 'users',
            'type' => 'doc',
            'body' => [
                'script' => [
                    'source' => <<<EOT
if (ctx._source.active == params.x) {ctx.op = 'noop'}
ctx._source.active = params.x
EOT
                    ,
                    'lang' => 'painless',
                    'params' => [ 'x' => $val ]
                ],
                'upsert' => [ 'id' => $id, 'active' => $val ]
            ]
        ];
    }

    /**
     * UPDATE (부분 변경)
     *
     * 사용자 정보중 join_active 를 변경
     *
     * @param int $id
     * @param bool $val
     * @return array
     */
    public function setUserUpdateJoinActiveParams(int $id, bool $val)
    {
        return [
            'id' => $id,
            'index' => 'users',
            'type' => 'doc',
            'body' => [
                'script' => [
                    'source' => <<<EOT
if (ctx._source.join_active == params.x) {ctx.op = 'noop'}
ctx._source.join_active = params.x
EOT
                    ,
                    'lang' => 'painless',
                    'params' => [ 'x' => $val ]
                ],
                'upsert' => [ 'id' => $id, 'join_active' => $val ]
            ]
        ];
    }

    /**
     * UPDATE (부분 변경)
     *
     * 사용자 정보중 match_types 배열 전체를 변경
     *
     * @param int $id
     * @param array $match_types
     * @return array
     */
    public function setUserUpdateMatchTypesParams(int $id, array $match_types)
    {
        sort($match_types);

        return [
            'id' => $id,
            'index' => 'users',
            'type' => 'doc',
            'body' => [
                'script' => [
                    'source' => <<<EOT
if (ctx._source.match_types == params.x) {ctx.op = 'noop'}
ctx._source.match_types = params.x
EOT
                    ,
                    'lang' => 'painless',
                    'params' => [ 'x' => $match_types ]
                ],
                'upsert' => [ 'id' => $id, 'match_types' => $match_types ]
            ]
        ];
    }

    /**
     * UPDATE (부분 변경)
     *
     * 사용자 정보중 match_types 배열에서 특정속성만 추가
     *
     * @param int $id
     * @param string $match_type
     * @return array
     */
    public function setUserAddMatchTypeParams(int $id, string $match_type)
    {
        return [
            'id' => $id,
            'index' => 'users',
            'type' => 'doc',
            'body' => [
                'script' => [
                    'source' => <<<EOT
if (ctx._source.containsKey('match_types')) {
    if (ctx._source.match_types.contains(params.x)) {ctx.op = 'noop'}
    ctx._source.match_types.add(params.x)
} else {
    ctx._source.match_types = [ params.x ]
}
EOT
                    ,
                    'lang' => 'painless',
                    'params' => [ 'x' => $match_type ]
                ],
                'upsert' => [ 'id' => $id, 'match_types' => [ $match_type ] ]
            ]
        ];
    }

    /**
     * UPDATE (부분 변경)
     *
     * 사용자 정보중 match_types 배열에서 특정속성만 제거
     *
     * @param int $id
     * @param string $match_type
     * @return array
     */
    public function setUserRemoveMatchTypeParams(int $id, string $match_type)
    {
        return [
            'id' => $id,
            'index' => 'users',
            'type' => 'doc',
            'body' => [
                'script' => [
                    'source' => <<<EOT
if (ctx._source.containsKey('match_types')) {
    if (!ctx._source.match_types.contains(params.x)) {ctx.op = 'noop'}
    ctx._source.match_types.removeAll(Collections.singleton(params.x))
} else {
    ctx._source['match_types'] = []
}
EOT
                    ,
                    'lang' => 'painless',
                    'params' => [ 'x' => $match_type ]
                ],
                'upsert' => [ 'id' => $id, 'match_types' => [] ]
            ]
        ];
    }

    /**
     * UPDATE (부분 변경)
     *
     * 사용자 정보중 name 만 변경
     *
     * @param int $id
     * @param string $name
     * @return array
     */
    public function setUserUpdateNameParams(int $id, string $name)
    {
        return [
            'id' => $id,
            'index' => 'users',
            'type' => 'doc',
            'body' => [
                'script' => [
                    'source' => <<<EOT
if (ctx._source.name == params.x) {ctx.op = 'noop'}
ctx._source.name = params.x
EOT
                    ,
                    'lang' => 'painless',
                    'params' => [ 'x' => $name ]
                ],
                'upsert' => [ 'id' => $id, 'name' => $name ]
            ]
        ];
    }

    /**
     * UPDATE (부분 변경)
     *
     * 사용자 정보중 gender 만 변경
     *
     * @param int $id
     * @param string $gender
     * @return array
     */
    public function setUserUpdateGenderParams(int $id, string $gender)
    {
        return [
            'id' => $id,
            'index' => 'users',
            'type' => 'doc',
            'body' => [
                'script' => [
                    'source' => <<<EOT
if (ctx._source.gender == params.x) {ctx.op = 'noop'}
ctx._source.gender = params.x
EOT
                    ,
                    'lang' => 'painless',
                    'params' => [ 'x' => $gender ]
                ],
                'upsert' => [ 'id' => $id, 'gender' => $gender ]
            ]
        ];
    }
    /**
     * UPDATE (부분 변경)
     *
     * 사용자 정보중 location 만 변경
     *
     * @param int $id
     * @param string $location
     * @return array
     */
    public function setUserUpdateLocationParams(int $id, string $location)
    {
        return [
            'id' => $id,
            'index' => 'users',
            'type' => 'doc',
            'body' => [
                'script' => [
                    'source' => <<<EOT
if (ctx._source.location == params.x) {ctx.op = 'noop'}
ctx._source.location = params.x
EOT
                    ,
                    'lang' => 'painless',
                    'params' => [ 'x' => $location ]
                ],
                'upsert' => [ 'id' => $id, 'location' => $location ]
            ]
        ];
    }

    /**
     * UPDATE (부분 변경)
     *
     * 사용자 정보중 birthday 만 변경
     *
     * @param int $id
     * @param string $birthday
     * @return array
     */
    public function setUserUpdateBirthdayParams(int $id, string $birthday)
    {
        return [
            'id' => $id,
            'index' => 'users',
            'type' => 'doc',
            'body' => [
                'script' => [
                    'source' => <<<EOT
if (ctx._source.birthday == params.x) {ctx.op = 'noop'}
ctx._source.birthday = params.x
EOT
                    ,
                    'lang' => 'painless',
                    'params' => [ 'x' => $birthday ]
                ],
                'upsert' => [ 'id' => $id, 'birthday' => $birthday ]
            ]
        ];
    }

    /**
     * UPDATE (부분 변경)
     *
     * 사용자 정보중 rating_score 만 변경
     *
     * @param int $id
     * @param float score
     * @return array
     */
    public function setUserUpdateRatingScoreParams(int $id, float $score)
    {
        return [
            'id' => $id,
            'index' => 'users',
            'type' => 'doc',
            'body' => [
                'script' => [
                    'source' => <<<EOT
if (ctx._source.rating_score == params.x) {ctx.op = 'noop'}
ctx._source.rating_score = params.x
EOT
                    ,
                    'lang' => 'painless',
                    'params' => [ 'x' => $score ]
                ],
                'upsert' => [ 'id' => $id, 'rating_score' => $score ]
            ]
        ];
    }

    /**
     * UPDATE (부분 변경)
     *
     * 사용자 정보중 smoking 만 변경
     *
     * @param int $id
     * @param int $smoking
     * @return array
     */
    public function setUserUpdateSmokingParams(int $id, int $smoking)
    {
        return [
            'id' => $id,
            'index' => 'users',
            'type' => 'doc',
            'body' => [
                'script' => [
                    'source' => <<<EOT
if (ctx._source.smoking == params.x) {ctx.op = 'noop'}
ctx._source.smoking = params.x
EOT
                    ,
                    'lang' => 'painless',
                    'params' => [ 'x' => $smoking ]
                ],
                'upsert' => [ 'id' => $id, 'smoking' => $smoking ]
            ]
        ];
    }

    /**
     * UPDATE (부분 변경)
     *
     * 사용자 정보중 religion 만 변경
     *
     * @param int $id
     * @param int $religion
     * @return array
     */
    public function setUserUpdateReligionParams(int $id, int $religion)
    {
        return [
            'id' => $id,
            'index' => 'users',
            'type' => 'doc',
            'body' => [
                'script' => [
                    'source' => <<<EOT
if (ctx._source.religion == params.x) {ctx.op = 'noop'}
ctx._source.religion = params.x
EOT
                    ,
                    'lang' => 'painless',
                    'params' => [ 'x' => $religion ]
                ],
                'upsert' => [ 'id' => $id, 'religion' => $religion ]
            ]
        ];
    }

    /**
     * UPDATE (부분 변경)
     *
     * 사용자 정보중 region 만 변경
     *
     * @param int $id
     * @param int $region
     * @return array
     */
    public function setUserUpdateRegionParams(int $id, int $region)
    {
        return [
            'id' => $id,
            'index' => 'users',
            'type' => 'doc',
            'body' => [
                'script' => [
                    'source' => <<<EOT
if (ctx._source.region == params.x) {ctx.op = 'noop'}
ctx._source.region = params.x
EOT
                    ,
                    'lang' => 'painless',
                    'params' => [ 'x' => $region ]
                ],
                'upsert' => [ 'id' => $id, 'region' => $region ]
            ]
        ];
    }

    /**
     * UPDATE (부분 변경)
     *
     * 사용자 정보중 etc_state 만 변경
     *
     * @param int $id
     * @param bool $state
     * @return array
     */
    public function setUserUpdateEtcStateParams(int $id, bool $state)
    {
        return [
            'id' => $id,
            'index' => 'users',
            'type' => 'doc',
            'body' => [
                'script' => [
                    'source' => <<<EOT
if (ctx._source.etc_state == params.x) {ctx.op = 'noop'}
ctx._source.etc_state = params.x
EOT
                    ,
                    'lang' => 'painless',
                    'params' => [ 'x' => $state ]
                ],
                'upsert' => [ 'id' => $id, 'etc_state' => $state ]
            ]
        ];
    }

    // ================================================================================
    // 제외 리스트
    // ================================================================================

    /**
     * INDEX
     *
     * 제외 리스트 저장용
     *
     * @param int $id
     * @param array $list
     * @return array
     */
    public function setExclusionIndexParams(int $id, array $list)
    {
        return [
            'id' => $id,
            'index' => 'exclusions',
            'type' => 'doc',
            'body' => [
                'id' => $id,
                'list' => $list
            ]
        ];
    }

    /**
     * UPDATE (부분 변경)
     *
     * 제외 리스트 중복없이 한개만 추가용
     *
     * @param $id
     * @param $idToBeAdded
     * @return array
     */
    public function setExclusionAddParams($id, $idToBeAdded)
    {
        return [
            'id' => $id,
            'index' => 'exclusions',
            'type' => 'doc',
            // 'retry_on_conflict' => self::RETRY_TIMES,
            'body' => [
                'script' => [
                    'source' =><<<EOT
if (ctx._source.list.contains(params.x)) {ctx.op = 'noop'}
ctx._source.list.add(params.x)
EOT
                    ,
                    'lang' => 'painless',
                    'params' => [
                        'x' => $idToBeAdded
                    ]
                ],
                'upsert' => [
                    'id' => $id,
                    'list' => [ $idToBeAdded ]
                ]
            ]
        ];
    }

    /**
     * UPDATE (부분 변경)
     *
     * 제외 리스트 중복없이 리스트 추가용
     *
     * @param int $id
     * @param array $list
     * @return array
     */
    public function setExclusionAddListParams(int $id, array $list)
    {
        return [
            'id' => $id,
            'index' => 'exclusions',
            'type' => 'doc',
            // 'retry_on_conflict' => self::RETRY_TIMES,
            'body' => [
                'script' => [
                    'source' =><<<EOT
def a = new ArrayList();
a.addAll(ctx._source.list);
a.addAll(params.x);
ctx._source.list = a;
ctx._source.list = ctx._source.list.stream().distinct().sorted().collect(Collectors.toList())
EOT
                    ,
                    'lang' => 'painless',
                    'params' => [
                        'x' => $list
                    ]
                ],
                'upsert' => [
                    'id' => $id,
                    'list' => $list
                ]
            ]
        ];
    }

    /**
     * BULK
     *
     * 제외 리스트 중복없이 대량(array) 추가용
     *
     * @param ExclusionListDto $dto
     * @param array $params
     * @return array
     */
    public function setExclusionBulkParams(ExclusionListDto $dto, array &$params)
    {
        $params['body'][] = [
            'update' => [
                '_id'    => $dto->getId(),
                '_index' => 'exclusions',
                '_type'  => 'doc',
            ]
        ];
        $params['body'][] = [
            'script' => [
                'source' =><<<EOT
def a = new ArrayList();
a.addAll(ctx._source.list);
a.addAll(params.x);
ctx._source.list = a;
ctx._source.list = ctx._source.list.stream().distinct().sorted().collect(Collectors.toList())
EOT
                ,
                'lang' => 'painless',
                'params' => [
                    'x' => $dto->getList()
                ]
            ],
            'upsert' => [
                'id' => $dto->getId(),
                'list' => $dto->getList()
            ]
        ];

        return $params;
    }

    // ================================================================================
    // 과평점 사용자 리스트
    // ================================================================================

    /**
     * INDEX
     *
     * 과평점 사용자 리스트 저장용
     *
     * @param int $id
     * @param array $list
     * @return array
     */
    public function setHighRatingIndexParams(int $id, array $list)
    {
        return [
            'id' => $id,
            'index' => 'high_ratings',
            'type' => 'doc',
            'body' => [
                'id' => $id,
                'list' => $list,
            ]
        ];
    }

    /**
     * UPDATE (부분 변경)
     *
     * 과평점 사용자 리스트 중복없이 한개만 추가용
     *
     * @param $id
     * @param $idToBeAdded
     * @return array
     */
    public function setHighRatingAddParams($id, $idToBeAdded)
    {
        return [
            'id' => $id,
            'index' => 'high_ratings',
            'type' => 'doc',
            // 'retry_on_conflict' => self::RETRY_TIMES,
            'body' => [
                'script' => [
                    'source' =><<<EOT
if (ctx._source.list.contains(params.x)) {ctx.op = 'noop'}
ctx._source.list.add(params.x)
EOT
                    ,
                    'lang' => 'painless',
                    'params' => [
                        'x' => $idToBeAdded
                    ]
                ],
                'upsert' => [
                    'id' => $id,
                    'list' => [ $idToBeAdded ]
                ]
            ]
        ];
    }

    /**
     * UPDATE (부분 변경)
     *
     * 과평점 사용자 리스트 중복없이 리스트 추가용
     *
     * @param int $id
     * @param array $list
     * @return array
     */
    public function setHighRatingAddListParams(int $id, array $list)
    {
        return [
            'id' => $id,
            'index' => 'high_ratings',
            'type' => 'doc',
            // 'retry_on_conflict' => self::RETRY_TIMES,
            'body' => [
                'script' => [
                    'source' =><<<EOT
def a = new ArrayList();
a.addAll(ctx._source.list);
a.addAll(params.x);
ctx._source.list = a;
ctx._source.list = ctx._source.list.stream().distinct().sorted().collect(Collectors.toList())
EOT
                    ,
                    'lang' => 'painless',
                    'params' => [
                        'x' => $list
                    ]
                ],
                'upsert' => [
                    'id' => $id,
                    'list' => $list
                ]
            ]
        ];
    }

    /**
     * BULK
     *
     * 과평점 사용자 리스트 중복없이 대량(array) 추가용
     *
     * @param HighRatingListDto $dto
     * @param array $params
     * @return array
     */
    public function setHighRatingBulkParams(HighRatingListDto $dto, array &$params)
    {
        $params['body'][] = [
            'update' => [
                '_id'    => $dto->getId(),
                '_index' => 'high_ratings',
                '_type'  => 'doc',
            ]
        ];
        $params['body'][] = [
            'script' => [
                'source' =><<<EOT
def a = new ArrayList();
a.addAll(ctx._source.list);
a.addAll(params.x);
ctx._source.list = a;
ctx._source.list = ctx._source.list.stream().distinct().sorted().collect(Collectors.toList())
EOT
                ,
                'params' => [
                    'x' => $dto->getList()
                ],
                'lang' => 'painless'
            ],
            'upsert' => [
                'id' => $dto->getId(),
                'list' => $dto->getList()
            ]
        ];

        return $params;
    }

    // ================================================================================
    // 소개 제한
    // ================================================================================

    /**
     * INDEX
     *
     * 소개 제한 리스트 저장용
     *
     * @param int $id
     * @param array $list
     * @return array
     */
    public function setIntroLimitIndexParams(int $id, array $list)
    {
        return [
            'id' => $id,
            'index' => 'intro_limits',
            'type' => 'doc',
            'body' => [
                'id' => $id,
                'list' => $list,
            ]
        ];
    }

    /**
     * DELETE
     *
     * 소개 제한 리스트 삭제용
     *
     * @param int $id
     * @return array
     */
    public function setIntroLimitDeleteParams(int $id)
    {
        return [
            'id' => $id,
            'index' => 'intro_limits',
            'type' => 'doc',
        ];
    }

    /**
     * UPDATE (부분 변경)
     *
     * 소개 제한 중복없이 한개만 추가용
     * PARTIAL UPDATE
     *
     * @param int $id (100, 200, 300)
     * @param int $idToBeAdded
     * @return array
     */
    public function setIntroLimitAddParams(int $id, int $idToBeAdded)
    {
        return [
            'id' => $id,
            'index' => 'intro_limits',
            'type' => 'doc',
            // 'retry_on_conflict' => self::RETRY_TIMES,
            'body' => [
                'script' => [
                    'source' =><<<EOT
if (ctx._source.list.contains(params.x)) {ctx.op = 'noop'}
ctx._source.list.add(params.x)
EOT
                    ,
                    'lang' => 'painless',
                    'params' => [
                        'x' => $idToBeAdded
                    ]
                ],
                'upsert' => [
                    'id' => $id,
                    'list' => [ $idToBeAdded ]
                ]
            ]
        ];
    }
}
