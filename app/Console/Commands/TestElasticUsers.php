<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Queue;
use SebastianBergmann\Timer\Timer;
use Symfony\Component\Console\Helper\ProgressBar;

class TestElasticUsers extends Command
{
    private ProgressBar $progressBar;
    private $total;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:elastic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send custom payload to elastic queue connection';

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
        $this->preTask();
        $this->mainTask();
        $this->postTask();

        return 0;
    }

    // ================================================================================
    // private methods
    // ================================================================================

    /**
     * initial task
     */
    private function preTask()
    {
        Timer::start();
        $this->total = User::all()->count();
        $this->progressBar = new ProgressBar($this->getOutput());
        $this->progressBar->setMaxSteps($this->total);
        $this->progressBar->setFormat('very_verbose');
        $this->progressBar->start();
    }

    /**
     * main task
     */
    private function mainTask()
    {
        foreach (User::with('profile')
                     ->where('is_active', true)
                     ->cursor() as $item) {

            $this->progressBar->advance();
            $payload = [
                'api' => 'index',
                'index' => 'users_v1',
                'id' => $item->id,
                'body' => [
                    'username' => $item->username,
                    'name' => $item->name,
                    'gender' => $item->gender,
                    'dob' => $item->dob->format('Y-m-d'),
                    'height' => $item->profile->height,
                    'location' => "{$item->profile->latitude},{$item->profile->longitude}",
                    'smoking' => $item->profile->smoking,
                    'drinking' => $item->profile->drinking,
                    'updated_at' => $item->updated_at->toJSON(),
                    'active' => $item->is_active,
                ]
            ];

            Queue::connection('elastic')
                ->pushRaw(json_encode($payload), 'elastic');
        }
    }

    /**
     * final task
     */
    private function postTask()
    {
        $this->progressBar->finish();

        $this->info(
            PHP_EOL .
            "took " .
            number_format(Timer::stop(), 2) .
            " secs to precess " .
            $this->total .
            " records."
        );
    }
}
