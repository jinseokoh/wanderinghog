<?php

namespace App\Repositories;

use Aws\Credentials\Credentials;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

class DynamoDbRepository
{
    protected $client;
    protected $marshaler;

    public function __construct()
    {
        $this->client = new DynamoDbClient([
            'endpoint'   => 'http://dynamodb-local:8000',
            'region' => 'ap-northeast-2',
            'version'  => 'latest',
            'credentials' => new Credentials(
                config('aws.credentials.key'),
                config('aws.credentials.secret'),
            ),
            'stats' => true,
        ]);
        $this->marshaler = new Marshaler();
    }

    /**
     * @param $params
     * @return mixed
     */
    public function get($table, $room)
    {
        $key = $this->marshaler->marshalJson(json_encode([
            "room" => $room,
        ]));
        $params = [
            'TableName' => $table,
            'Key' => $key,
        ];

        try {
            $result = $this->client->getItem($params);
        } catch (DynamoDbException $e) {
            \Log::error("[Dynamo] {$e->getMessage()}");
        }

        return $result;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function put($table, $room, $users = [])
    {
        $args = [
            'room' => $room,
            'users' => $users,
            'message' => null,
            'active' => true
        ];
        $item = $this->marshaler->marshalJson(json_encode($args));
        $params = [
            'TableName' => $table,
            'Item' => $item,
        ];

        try {
            $result = $this->client->putItem($params);
        } catch (DynamoDbException $e) {
            \Log::error("[Dynamo] {$e->getMessage()}");
        }

        return $result;
    }
}
