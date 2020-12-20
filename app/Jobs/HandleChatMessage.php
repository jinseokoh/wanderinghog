<?php

namespace App\Jobs;

use App\Handlers\AwsSnsPushHandler;
use App\Handlers\UserHandler;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class HandleChatMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    /**
     * @param $data
     *        - receiver
     *        - sender
     *        - message
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @param UserHandler $userHandler
     * @param AwsSnsPushHandler $snsHandler
     * @return void
     */
    public function handle(UserHandler $userHandler, AwsSnsPushHandler $snsHandler)
    {
        if (array_key_exists("receiver", $this->data) && isset($this->data['receiver'])) {
            try {
                $user = $userHandler->findByName($this->data['receiver']);
            } catch (\Throwable $e) {
                \Log::info("a user named {$this->data['receiver']} not found");
                return;
            }

            if ($token = $user->deviceToken) {
                $title = "바른연애생활";
                $message = $this->data['sender'].' '.$this->data['message'];
                $snsHandler->send($token, $title, $message);
                \Log::info('Push notification sent.');
            }
        } else {
            \Log::info("not in a chat-message format");
        }
    }
}
