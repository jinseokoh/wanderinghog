<?php

namespace App\Providers;

use App\Support\RawSqs\RawSqsConnector;
use Illuminate\Support\ServiceProvider;

class RawSqsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Nothing to do here
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /** @var \Illuminate\Queue\QueueManager $queue */
        $queue = $this->app['queue'];
        $queue->addConnector('rawsqs', function () {
            return new RawSqsConnector();
        });
    }
}
