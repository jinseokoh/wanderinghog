<?php

namespace App\Repositories;

use Illuminate\Cache\RedisStore;

class RedisRepository
{
    const PREFIX = 'intro_limit_';

    private $redis;

    public function __construct(RedisStore $redis)
    {
        $this->redis = $redis;
    }

    /**
     * 타입별 소개 limit 3개 모두 일괄 설정
     * - regular
     * - irregular
     * - additional
     *
     * @param array $limits
     * @param int $id
     */
    public function setLimits(array $limits, int $id)
    {
        foreach ($limits as $key => $value) {
            $cacheKey = $this->getCacheKey($key, $id);
            $this->redis->connection()->set($cacheKey, $value);
        }
    }

    /**
     * 소개된 횟수를 Redis 에서 처리 후, 소개 제한만큼 소개가 이뤄진 아이디들을 리턴
     *
     * @param string $introType
     * @param array $ids
     * @return array
     */
    public function manageLimit(string $introType, array $ids)
    {
        $excesses = [];

        foreach ($ids as $id) {
            $value = $this->decrease($introType, $id);
            if ($value == 0) {
                $excesses[] = $id;
            }
        }

        return $excesses;
    }

    // ================================================================================
    // private methods
    // ================================================================================

    /**
     * 타입별 소개 limit 감소
     *
     * @param string $introType
     * @param int $id
     * @return int
     */
    private function decrease(string $introType, int $id)
    {
        $cacheKey = $this->getCacheKey($introType, $id);

        return $this->redis->connection()->decr($cacheKey);
    }

    /**
     * 모두 삭제
     */
    private function purgeAllKeys()
    {
        $this->redis
            ->connection()
            ->flushall();
    }

    /**
     * @return mixed
     */
    private function findAllKeysWithPrefix()
    {
        return $this->redis
            ->connection()
            ->keys(self::PREFIX.'*');
    }

    /**
     * @param string $introType
     * @param int $id
     * @return string
     */
    private function getCacheKey(string $introType, int $id): string
    {
        return $this->getPrefix($introType).':'.$id;
    }

    /**
     * @param string $introType
     * @return string
     */
    private function getPrefix(string $introType): string
    {
        switch ($introType) {
            case 'regular':
                return self::PREFIX.'r'; // regular limit
            case 'irregular':
                return self::PREFIX.'i'; // irregular limit
            default:
                return self::PREFIX.'a'; // additional limit
        }
    }
}
