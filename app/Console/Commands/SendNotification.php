<?php

namespace App\Console\Commands;

use App\Jobs\SendPushNotification;
use Illuminate\Console\Command;

class SendNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:notify {to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send test notifications to users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $to = (int) $this->argument('to');

        dispatch(new SendPushNotification($to));

        return 0;
    }
}
