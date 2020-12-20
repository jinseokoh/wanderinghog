<?php

namespace App\Support\RawSqs;

use App\Jobs\HandleChatMessage;
use Aws\Sqs\SqsClient;
use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\Job as JobContract;
use Illuminate\Queue\CallQueuedHandler;
use Illuminate\Queue\Jobs\SqsJob;

/**
 * ref) https://github.com/pawprintdigital/laravel-queue-raw-sqs
 *
 * Class RawSqsJob
 * @package App\Support\RawSqs
 */
class RawSqsJob extends SqsJob implements JobContract
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $routes;

    /**
     * Create a new job instance.
     *
     * @param  \Illuminate\Container\Container  $container
     * @param  \Aws\Sqs\SqsClient  $sqs
     * @param  array   $job
     * @param  string  $connectionName
     * @param  string  $queue
     * @return void
     */
    public function __construct(
        Container $container,
        SqsClient $sqs,
        array $job,
        $connectionName,
        $queue,
        $routes
    ) {
        parent::__construct(
            $container,
            $sqs,
            $job,
            $connectionName,
            $queue
        );
        $this->routes = collect($routes);
    }

    /**
     * Get the name of the queued job class.
     *
     * @return string
     */
    public function getName()
    {
        $rawPayload = $this->payload();
        return array_key_exists("TopicArn", $rawPayload) ?
            $rawPayload['TopicArn'] :
            HandleChatMessage::class;
    }

    /**
     * Fire the job.
     *
     * @return void
     */
    public function fire()
    {
        $rawPayload = $this->payload();

        $topic = $this->getTopicFromPayload($rawPayload);
        $message = $this->getDecodedMessageFromPayload($rawPayload);
        $topicClass = $this->getTopicClass($topic, $message);
        $serializedClass = serialize($topicClass);

        $data = [
            'command' => $serializedClass
        ];

        $class = CallQueuedHandler::class;
        ($this->instance = $this->resolve($class))->call($this, $data);
    }

    // ================================================================================
    // borrowed the code from the package, pawprintdigital/laravel-queue-raw-sqs, which
    // is supposed to handle queue data from Amazon SNS. To be able to handle data from
    // my chat-server, I had to customize the following protected methods.
    // ================================================================================

    protected function getTopicClass($topic, $message)
    {
        $filtered = $this->routes->filter(function($routeClass, $routeTopic) use ($topic) {
            return (fnmatch($routeTopic, $topic)) ? true : false;
        });

        if ($filtered->count()) {
            $className = $filtered->first();
        } else {
            $className = 'App\\Jobs\\'.$topic;
        }

        return $this->container->make($className, ['data' => $message]);
    }

    protected function getTopicFromPayload($payload)
    {
        if (array_key_exists("TopicArn", $payload) && isset($payload['TopicArn'])) {
            return last(explode(':', $payload['TopicArn']));
        } else {
            return 'custom';
        }
    }

    protected function getDecodedMessageFromPayload($payload)
    {
        if (array_key_exists("Message", $payload) && isset($payload['Message'])) {
            return json_decode($payload['Message'], true);
        } else {
            return $payload;
        }
    }
}