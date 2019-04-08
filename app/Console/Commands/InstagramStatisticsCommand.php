<?php

namespace App\Console\Commands;

use App\Jobs\ScrapeInstagramUser;
use App\Models\InstagramUser;
use Illuminate\Console\Command;

class InstagramStatisticsCommand extends Command
{
    const INSTAGRAM_QUEUE_NAME = 'instagram-queue-%d';

    protected $signature = 'instagram:statistics';

    protected $description = 'Daily job to fetch user related information from the Instagram API';

    /**
     * Variable to indicate how many specific instagram queues there is.
     *
     * @var int
     */
    private $instagramQueues;

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
     * @return mixed
     */
    public function handle(): void
    {
        $instagramUsers = InstagramUser::all();

        while ($instagramUsers->isNotEmpty()) {
            foreach (range(1, $this->instagramQueues) as $queue) {
                $job = new ScrapeInstagramUser($instagramUsers->shift());
                $job->onQueue(sprintf(static::INSTAGRAM_QUEUE_NAME, $queue));
                dispatch($job);
            }
        }
    }
}
