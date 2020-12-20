<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Repositories\DynamoDbRepository;
use Illuminate\Console\Command;
use Queue;
use SebastianBergmann\Timer\Timer;
use Symfony\Component\Console\Helper\ProgressBar;

class TestDynamo extends Command
{
    private ProgressBar $progressBar;
    private $total;
    private $repository;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:dynamo';

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
    public function __construct(DynamoDbRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $a = $this->repository->get('Rooms', 'usa');
        return 0;
    }
}
