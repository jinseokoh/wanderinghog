<?php

namespace App\Repositories\Elasticsearch;

use Elasticsearch\Client;

abstract class ElasticsearchRepository
{
    /** @var Client $client */
    protected $client;

    /**
     * @param $params
     * @return mixed
     */
    public function get($params)
    {
        try {
            $response = $this->client->get($params);
        } catch (\Throwable $e) {
            \Log::error("[ELASTIC-GET] {class_basename($e)} ".$e->getMessage());
            throw new \Exception($e->getMessage());
        }

        return $response;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function index($params)
    {
        try {
            $response = $this->client->index($params);
        } catch (\Throwable $e) {
            \Log::error("[ELASTIC-INDEX] {class_basename($e)} ".$e->getMessage());
            throw new \Exception($e->getMessage());
        }

        return $response;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function bulk($params)
    {
        try {
            $responses = $this->client->bulk($params);
        } catch (\Throwable $e) {
            \Log::error("[ELASTIC-BULK] {class_basename($e)} ".$e->getMessage());
            throw new \Exception($e->getMessage());
        }

        return $responses;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function update($params)
    {
        try {
            $response = $this->client->update($params);
        } catch (\Throwable $e) {
            \Log::error("[ELASTIC-UPDATE] {class_basename($e)} ".$e->getMessage());
            throw new \Exception($e->getMessage());
        }

        return $response;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function delete($params)
    {
        try {
            $response = $this->client->delete($params);
        } catch (\Throwable $e) {
            \Log::error("[ELASTIC-DELETE] {class_basename($e)} ".$e->getMessage());
            throw new \Exception($e->getMessage());
        }

        return $response;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function search($params)
    {
        try {
            $response = $this->client->search($params);
        } catch (\Throwable $e) {
            \Log::error("[ELASTIC-SEARCH] {class_basename($e)} ".$e->getMessage());
            throw new \Exception($e->getMessage());
        }

        return $response;
    }

    // ================================================================================
    // magic methods
    // ================================================================================

    /**
     * 이 클래스에 정의 안된 ES 메서드를 호출하는 magic method
     *
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function __call(string $method, array $params)
    {
        try {
            $response = call_user_func_array([$this->client, $method], $params);
        } catch (\Throwable $e) {
            \Log::error("[ELASTIC-__MAGIC__] {class_basename($e)} ".$e->getMessage());
            throw new \Exception($e->getMessage());
        }

        return $response;
    }
}
