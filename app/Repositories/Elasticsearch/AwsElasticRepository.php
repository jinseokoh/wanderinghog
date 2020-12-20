<?php

namespace App\Repositories\Elasticsearch;

use Aws\ElasticsearchService\ElasticsearchPhpHandler;
use Elasticsearch\ClientBuilder;
use Monolog\Logger;

class AwsElasticRepository extends ElasticsearchRepository
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

        $handler = new ElasticsearchPhpHandler(config('aws.region'));
        $this->client = ClientBuilder::create()
            ->setHandler($handler)
            ->setLogger($logger)
            ->setHosts($hosts)
            ->build();
    }
}
