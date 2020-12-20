<?php

namespace App\Jobs;

use App\Handlers\AwsSnsPushHandler;
use App\Models\DeviceToken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Inspiring;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPushNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(AwsSnsPushHandler $handler)
    {
        $deviceToken = DeviceToken::find($this->id);

        $quote = Inspiring::quote();
        $title = "바른연애생활";
        $message = $quote;

        echo "[debug] ".$deviceToken->token."\n";
        echo "[debug] ".$deviceToken->arn."\n";
        echo "[debug] ".$quote."\n";

        $handler->send($deviceToken, $title, $message);
    }
}
