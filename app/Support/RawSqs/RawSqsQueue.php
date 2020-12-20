<?php

namespace App\Support\RawSqs;

use Aws\Sqs\SqsClient;
use Illuminate\Queue\SqsQueue;

/**
 * ref) pawprintdigital/laravel-queue-raw-sqs
 *
 * Class RawSqsQueue
 * @package App\Support\RawSqs
 */
class RawSqsQueue extends SqsQueue {

    protected $routes = [];

    /**
     * Create a new Amazon SQS queue instance.
     *
     * @param  \Aws\Sqs\SqsClient  $sqs
     * @param  string  $default
     * @param  string  $prefix
     * @return void
     */
    public function __construct(SqsClient $sqs, $default, $prefix = '', $routes)
    {
        parent::__construct($sqs, $default, $prefix);
        $this->routes = $routes;
    }

    /**
     * Pop the next job off of the queue.
     *
     * @param  string  $queue
     * @return \Illuminate\Contracts\Queue\Job|null
     */
    public function pop($queue = null)
    {
        $response = $this->sqs->receiveMessage([
            'QueueUrl' => $queue = $this->getQueue($queue),
            'AttributeNames' => ['ApproximateReceiveCount'],
        ]);

        if ($response['Messages'] && count($response['Messages']) > 0) {
            return new RawSqsJob(
                $this->container,
                $this->sqs,
                $response['Messages'][0],
                $this->connectionName,
                $queue,
                $this->routes
            );
        }

        return null;
    }
}