<?php

namespace App\Repositories\Elasticsearch;

use Elasticsearch\ClientBuilder;
use Monolog\Logger;

class LocalElasticRepository extends ElasticsearchRepository
{
    public function __construct()
    {
        $hosts = [
            [
                'host'   => config('services.elasticsearch.host'),
                'port'   => config('services.elasticsearch.port'),
                'scheme' => config('services.elasticsearch.scheme'),
                'user'   => config('services.elasticsearch.user'),
                'pass'   => config('services.elasticsearch.pass'),
            ]
        ];

        /** @var Logger $logger */
        $logger = ClientBuilder::defaultLogger(
            storage_path('logs/elastic.log'),
            Logger::CRITICAL
        );

        $this->client = ClientBuilder::create()
            ->setHosts($hosts)
            ->setLogger($logger)
            ->build();
    }
}
