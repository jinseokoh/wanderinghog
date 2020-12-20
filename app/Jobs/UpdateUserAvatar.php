<?php

namespace App\Jobs;

use App\Handlers\MediaHandler;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateUserAvatar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    private $user;
    /**
     * @var string
     */
    private $link;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param string $link
     */
    public function __construct(User $user, string $link)
    {
        $this->user = $user;
        $this->link = $link;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            (new MediaHandler())
                ->addUserMediaFromUrl($this->user, $this->link);
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());
        }
    }
}
